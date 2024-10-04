<?php

namespace App\Http\Controllers\ClusterBController\Planner;

use App\Models\TmsClientDealership;
use App\Models\TmsClusterCarModel;
use App\Models\TractorTrailerDriver;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\HaulingPlanRequest;
use App\Models\TmsHaulage;
use App\Models\TmsHaulageBlock;
use App\Models\TmsHaulageBlockUnit;
use App\Services\DTServerSide;
use App\Services\Phpspreadsheet;
use App\Services\Planner\HaulageList;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Str;

class HaulageInfo extends Controller
{
    protected $tractor_arr = [];
    protected $haulage_block = [];
    protected $haulage_block_unit=[];
    protected $block = 1;

    public function tripblock(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $batch = $rq->batch!=='All Batch'?$rq->batch:false;
            $query = TmsHaulageBlock::with(['block_unit'=> function($query) {
                $query->orderByRaw("CASE WHEN vld_instruction LIKE '%MP%' THEN 1 ELSE 0 END, id");
                // ->orderBy('unit_order', 'asc')
            }])
            ->when($batch,function($query,$batch){
                $query->where('batch',$batch);
            })
            ->where([['haulage_id',$id],['is_deleted',null]])->orderBy('block_number', 'asc')->get();
            $array = [];
            if($query){
                foreach($query as $data){
                    $block_unit = [];
                    $dealer_arr = [];
                    $dealer_code_arr = [];
                    $is_multipickup = false;
                    $units_count =0;
                    foreach($data->block_unit as $row){
                        $dealer = $row->dealer;
                        if (!in_array($dealer->name, $dealer_arr)){
                            $dealer_arr[] = $dealer->name;
                            $dealer_code_arr[] = $dealer->code;
                        }

                        if (stripos($row->vld_instruction, 'MP') !== false) {
                            $is_multipickup = true;
                        }

                        $block_unit[]=[
                            'encrypted_id'=>Crypt::encrypt($row->id),
                            'dealer_code'=>$dealer->code,
                            'model'=>$row->car->short_name ?? $row->car->car_model,
                            'cs_no'=>$row->cs_no,
                            'color_description'=>$row->color_description,
                            'invoice_date'=>date('m/d/Y',strtotime($row->invoice_date)),
                            'updated_location'=>$row->updated_location,
                            'inspection_start'=>$row->inspected_start?date('g:i A',strtotime($row->inspected_start)):'--',
                            'hub'=>$row->hub ??'--',
                            'remarks'=>$row->vld_instruction??'-',
                            'status'=>$row->status,
                            'is_transfer'=>$row->is_transfer,
                        ];
                        $units_count++;
                    }
                    $array[]=[
                        'encrypted_id'=>Crypt::encrypt($data->id),
                        'block_number'=>$data->block_number,
                        'batch'=>$data->batch,
                        'no_of_trips'=>$data->no_of_trips,
                        'dealer'=>$dealer_arr?implode(', ', $dealer_arr):null,
                        'dealer_code'=>$dealer_code_arr?implode(', ', $dealer_code_arr):null,
                        'block_units'=>$block_unit,
                        'status'=>$data->status,
                        'created_by'=>$data->status,
                        'status'=>$data->status,
                        'is_multipickup'=>$is_multipickup,
                        'units_count'=>$units_count,
                        'remarks'=>$data->remarks,
                        'created_by'=>optional($data->created_by_emp)->fullname() ?? 'No record found',
                        'updated_by'=>optional($data->updated_by_emp)->fullname() ?? 'No record found',
                    ];
                }
            }

            $payload = base64_encode(json_encode($array));
            return ['status'=>'success','message' =>'success', 'payload' => $payload];
        }catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function for_allocation(Request $rq)
    {
        try{
            $id      = Crypt::decrypt($rq->id);
            $haulage = TmsHaulage::find($id);
            $search = isset($rq->search) ? $rq->search:null;
            $query = TmsHaulageBlockUnit::where('hub', 'LIKE', "%{$rq->hub}%")
            ->when($search, function($query, $search) {
                $query->where(function($query) use ($search) {
                    // Group the conditions to ensure proper logical behavior
                    $query->where('cs_no', 'LIKE', "%$search%")
                        ->orWhereHas('dealer', function($q) use ($search) {
                            $q->where('name', 'LIKE', "%$search%")
                            ->orWhere('code', 'LIKE', "%$search%");
                        })
                        ->orWhereHas('car', function($q) use ($search) {
                            $q->where('car_model', 'LIKE', "%$search%");
                        });
                });
            })
            ->where([['haulage_id', $id], ['is_deleted', null]])
            ->get();
            $array   = [];
            if($query){
                foreach($query as $data){
                    $dealer = $data->dealer;
                    $allocated = 0;
                    $unallocated = 0;
                    $unit=[];
                    if($data->status == 0){
                        $unit=[
                            'encrypted_id'=>Crypt::encrypt($data->id),
                            'dealer_code'=>$dealer->code,
                            'model'=>$data->car->short_name ?? $data->car->car_model,
                            'cs_no'=>$data->cs_no,
                            'color_description'=>$data->color_description,
                            'invoice_date'=>date('m/d/Y',strtotime($data->invoice_date)),
                            'updated_location'=>$data->updated_location,
                            'inspection_start'=>date('g:i A',strtotime($data->inspected_start)),
                            'hub'=>$data->hub ??'--',
                            'remarks'=>$data->vld_instruction ?? $data->remarks,
                            'status'=>$data->status,
                        ];
                        $unallocated++;
                    }else{
                        $allocated++;
                    }
                    $array[$dealer->code]['unit'][] = $unit;
                    $array[$dealer->code]['hub'] = $data->hub;
                    $array[$dealer->code]['allocated'] = ($array[$dealer->code]['allocated'] ?? 0) + $allocated;
                    $array[$dealer->code]['unallocated'] = ($array[$dealer->code]['unallocated'] ?? 0) + $unallocated;
                }
            }
            $payload = base64_encode(json_encode(
                [
                    'data'=>$array,
                    'status'=>$haulage->status
                ]
            ));
            return ['status'=>'success','message' =>'success', 'payload' => $payload];
        }catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function masterlist(Request $rq)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        try{
            DB::beginTransaction();
            $haulage_id = Crypt::decrypt($rq->id);
            $cluster_id    = Auth::user()->emp_cluster->cluster_id;

            $result = (new HaulageList)->move_file($rq,'masterlist','cluster_b/masterlist');
            if ($result['status'] != 'success' && $result['payload'] === false) {  return response()->json($result); }

            $class         = new Phpspreadsheet;
            [$sheet,$highestRow] = $class->read($result['payload'],true,true,'RVL');
            $encrypted_key = $sheet->getCell("AB1")->getCalculatedValue();

            if($highestRow <= 1){
                if (Storage::disk('public')->exists($result['payload'])) {
                    Storage::disk('public')->delete($result['payload']);
                }
                throw new \Exception("Sheet 'RVL' does not have any data.");
            }

            if($encrypted_key == null){
                throw new \Exception("Upload the file you downloaded in this hauling plan");
            }

            $keys = json_decode($result['upload_key'],true) ?? [];
            if(empty($keys)){
                throw new \Exception("Download the TMP file first before uploading");
            }

            if (!array_key_exists($encrypted_key,$keys)){
                throw new \Exception("Upload the file you downloaded in this hauling plan");
            }
            if($keys[$encrypted_key] == 'VISMIN'){
                throw new \Exception("Upload the excel file for TMP");
            }

            $dealer_arr    = TmsClientDealership::all()->pluck('id', 'code')->toArray();
            $car_model_arr = TmsClusterCarModel::where('cluster_id',$cluster_id)->get()->pluck('id', 'car_model')->toArray();
            $user_id = Auth::user()->emp_id;

            for ($row = 2; $row <= $highestRow; $row++) {
                $dealer_code = $sheet->getCell("A$row")->getCalculatedValue();
                $dealer_id    = isset($dealer_arr[$dealer_code]) ? $dealer_arr[$dealer_code] : false;
                $car_model = strtoupper($sheet->getCell("D$row")->getCalculatedValue());
                $car_model_id = $car_model_arr[$car_model] ?? false;
                $cs_no = $sheet->getCell("B$row")->getCalculatedValue();
                $color        = $sheet->getCell("E$row")->getCalculatedValue();
                if ($dealer_code !== null && $cs_no !== null){
                    if(!$car_model_id){
                        $insert = TmsClusterCarModel::create(['cluster_id' => $cluster_id, 'car_model'=>$car_model,'color_description' => $color,'is_active' =>1]);
                        $car_model_id = $insert->id;
                    }
                    if(!$dealer_id){
                        $insert = TmsClientDealership::create([ 'code' => $dealer_code, 'is_active' =>1 ]);
                        $dealer_id = $insert->id;
                    }
                    $invoice_date = $class->excelDateToPhpDate($sheet->getCell("F$row")->getCalculatedValue());
                    $invoice_time = $class->excelTimeToPhpTime($sheet->getCell("G$row")->getCalculatedValue());
                    if ($invoice_date == false && $invoice_time == false) {
                        return ['status'=>'error', 'message' =>'Invalid invoice date or invoice time on row '.$row];
                    }

                    $this->haulage_block_unit[] = [
                        'status'=>0,
                        'block_id'=>null,
                        'haulage_id'=>$haulage_id,
                        'dealer_id' => $dealer_id,
                        'car_model_id' =>$car_model_id,
                        'cs_no' => $cs_no,
                        'color_description' => $color,
                        'updated_location' => $sheet->getCell("C$row")->getCalculatedValue(),
                        'invoice_date' =>$invoice_date,
                        'invoice_time' =>$invoice_time,
                        'planning_cutoff' =>$sheet->getCell("H$row")->getCalculatedValue(),
                        'vdn_number' =>$sheet->getCell("I$row")->getCalculatedValue(),
                        'vld_instruction' =>$sheet->getCell("J$row")->getCalculatedValue(),
                        'hub' => $sheet->getCell("K$row")->getCalculatedValue(),
                        'assigned_lsp' =>$sheet->getCell("L$row")->getCalculatedValue(),
                        'vld_planner_confirmation' =>$sheet->getCell("M$row")->getCalculatedValue(),
                        'units_from' => 'TMP',
                        'created_by'=>$user_id,
                    ];
                }else{
                    $this->haulage_block_unit = self::filter_duplicates($haulage_id);
                    if($this->haulage_block_unit === false){
                        return ['status'=>400, 'message' =>'All records are duplicates'];
                        exit(0);
                    }
                    TmsHaulageBlockUnit::insert($this->haulage_block_unit,$haulage_id);
                    if ($sheet->getCell("B".($row + 1))->getCalculatedValue() === null &&
                        $sheet->getCell("D".($row + 1))->getCalculatedValue() === null &&
                        $sheet->getCell("B".($row + 2))->getCalculatedValue() === null &&
                        $sheet->getCell("D".($row + 2))->getCalculatedValue() === null &&
                        $sheet->getCell("B".($row + 3))->getCalculatedValue() === null &&
                        $sheet->getCell("D".($row + 3))->getCalculatedValue() === null
                        ) {
                        break;
                    }
                    $this->haulage_block = [];
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'File uploaded successfully','payload'=>$result['upload_count']]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function vismin(Request $rq)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        try{
            DB::beginTransaction();

            $result = (new HaulageList)->move_file($rq,'vismin','cluster_b/vismin');
            if ($result['status'] != 'success' && $result['payload'] === false) {  return response()->json($result); }

            $class   = new Phpspreadsheet;
            [$sheet,$highestRow] = $class->read($result['payload'],true,true,'VISMIN');
            $encrypted_key = $sheet->getCell("AB1")->getCalculatedValue();

            if($highestRow <= 1){
                if (Storage::disk('public')->exists($result['payload'])) {
                    Storage::disk('public')->delete($result['payload']);
                }
                throw new \Exception("Sheet 'VISMIN' does not have any data.");
            }

            if($encrypted_key == null){
                throw new \Exception("Upload the file you downloaded in this hauling plan");
            }

            $keys = json_decode($result['upload_key'],true) ?? [];
            if(empty($keys)){
                throw new \Exception("Download the VISMIN file first before uploading");
            }
            if (!array_key_exists($encrypted_key,$keys)){
                throw new \Exception("Upload the file you downloaded in this hauling plan");
            }
            if($keys[$encrypted_key] == 'TMP'){
                throw new \Exception("Upload the excel file for TMP");
            }

            $user_id       = Auth::user()->emp_id;
            $haulage_id    = Crypt::decrypt($rq->id);
            $cluster_id    = Auth::user()->emp_cluster->cluster_id;
            $dealer_arr    = TmsClientDealership::all()->pluck('id', 'code')->toArray();
            $car_model_arr = TmsClusterCarModel::where('cluster_id',$cluster_id)->get()->pluck('id', 'car_model')->toArray();

            for ($row = 2; $row <= $highestRow; $row++) {
                $dealer_code  = $sheet->getCell("C$row")->getCalculatedValue();
                $dealer_id    = isset($dealer_arr[$dealer_code]) ? $dealer_arr[$dealer_code] : false;
                $car_model    = strtoupper($sheet->getCell("G$row")->getCalculatedValue());
                $car_model_id = $car_model_arr[$car_model] ?? false;
                $cs_no        = $sheet->getCell("E$row")->getCalculatedValue();
                $color        = $sheet->getCell("H$row")->getCalculatedValue();

                $invoice_date = $class->excelDateToPhpDate($sheet->getCell("I$row")->getCalculatedValue());
                $invoice_time = $class->excelTimeToPhpTime($sheet->getCell("J$row")->getCalculatedValue());
                $booking_date = $class->excelDateToPhpDate($sheet->getCell("O$row")->getCalculatedValue());
                $etd          = $class->excelDateToPhpDate($sheet->getCell("R$row")->getCalculatedValue());
                $eta          = $class->excelDateToPhpDate($sheet->getCell("S$row")->getCalculatedValue());

                if ($dealer_code !== null && $cs_no !== null){
                    if(!$car_model_id){
                        $insert = TmsClusterCarModel::create(['cluster_id' => $cluster_id, 'car_model'=>$car_model,'color_description' => $color,'is_active' =>1]);
                        $car_model_id = $insert->id;
                    }
                    if(!$dealer_id){
                        $insert = TmsClientDealership::create([ 'code' => $dealer_code, 'is_active' =>1 ]);
                        $dealer_id = $insert->id;
                    }
                    if ($invoice_date == false || $booking_date == false || $etd == false || $eta == false) {
                        return ['status'=>'error', 'message' =>'Invalid date format on row '.$row];
                    }
                    if ($invoice_time == false) {
                        return ['status'=>'error', 'message' =>'Invalid invoice time format  on row '.$row];
                    }
                    $this->haulage_block_unit[] = [
                        'haulage_id'=>$haulage_id,
                        'dealer_id' => $dealer_id,
                        'car_model_id' =>$car_model_id,
                        'cs_no' => $cs_no,
                        'color_description' => $color,
                        'updated_location' => $sheet->getCell("F$row")->getCalculatedValue(),
                        'invoice_date' =>$invoice_date,
                        'invoice_time' =>$invoice_time,
                        'vdn_number' =>$sheet->getCell("D$row")->getCalculatedValue(),
                        'vld_instruction' =>$sheet->getCell("L$row")->getCalculatedValue(),
                        'hub' => $sheet->getCell("K$row")->getCalculatedValue(),
                        'assigned_lsp' =>$sheet->getCell("A$row")->getCalculatedValue(),
                        'vld_planner_confirmation' =>$sheet->getCell("U$row")->getCalculatedValue(),
                        'booking_date'=>$booking_date,
                        'shipping_lines'=>$sheet->getCell("P$row")->getCalculatedValue(),
                        'vessel_name'=>$sheet->getCell("Q$row")->getCalculatedValue(),
                        'etd'=>$etd,
                        'eta'=>$eta,
                        'origin_port'=>$sheet->getCell("T$row")->getCalculatedValue(),
                        'units_from' => 'VISMIN',
                        'created_by'=>$user_id,
                    ];
                }
            }

            $this->haulage_block_unit = self::filter_duplicates($haulage_id);
            if($this->haulage_block_unit === false){
                return ['status'=>400, 'message' =>'All records are duplicates'];
                exit(0);
            }
            TmsHaulageBlockUnit::insert($this->haulage_block_unit,$haulage_id);
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'File uploaded successfully','payload'=>$result['upload_count']]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function filter_duplicates($haulage_id)
    {
        $existingRecords = TmsHaulageBlockUnit::where('haulage_id', $haulage_id)
            ->where('is_deleted', null)
            ->get(['id', 'cs_no', 'dealer_id', 'car_model_id', 'haulage_id']);

        // Convert existing records to a lookup array for easier comparison
        $existingRecordsLookup = [];
        foreach ($existingRecords as $record) {
            $key = "{$record->cs_no}_{$record->dealer_id}_{$record->car_model_id}_{$record->haulage_id}";
            $existingRecordsLookup[$key] = true; // Store as key for faster lookup
        }

        // Filter out duplicates from block_units
        $filteredBlockUnits = [];
        $seenKeys = []; // To track unique keys in block_units

        foreach ($this->haulage_block_unit as $unit) {
            $key = "{$unit['cs_no']}_{$unit['dealer_id']}_{$unit['car_model_id']}_{$unit['haulage_id']}";

            // Check if the unit is in existing records or if it's already seen
            if (!isset($existingRecordsLookup[$key]) && !isset($seenKeys[$key])) {
                $seenKeys[$key] = true; // Mark this key as seen
                $filteredBlockUnits[] = $unit; // Add to filtered units
            }
        }

        return count($filteredBlockUnits) > 0 ? array_values($filteredBlockUnits) : false;
    }

    public function hauling_plan(Request $rq)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        try{
            DB::beginTransaction();

            $result = (new HaulageList)->move_file($rq,'hauling_plan','cluster_b/hauling_plan');
            if ($result['status'] != 'success' && $result['payload'] === false) {  return response()->json($result); }

            $class  = new Phpspreadsheet;
            [$sheet,$highestRow] = $class->read($result['payload'],false,true,'PDI');

            $haulage_id    = Crypt::decrypt($rq->id);
            $cluster_id    = Auth::user()->emp_cluster->cluster_id;
            $dealer_arr    = TmsClientDealership::all()->pluck('id', 'code')->toArray();
            $car_model_arr = TmsClusterCarModel::where('cluster_id',$cluster_id)->get()->pluck('id', 'car_model')->toArray();
            $tractors      = TractorTrailerDriver::with('tractor:id,plate_no')->get();
            $user_id = Auth::user()->emp_id;
            $unit_order=0;

            foreach ($tractors as $data) {
                if($data->tractor_id !=null){
                    $this->tractor_arr[str_replace(' ', '',$data->tractor->plate_no)] = [
                        'tractor_id' => $data->tractor_id,
                        'trailer_id' => $data->trailer_id,
                        'pdriver' => $data->pdriver,
                        'sdriver' => $data->sdriver,
                    ];
                }
            }

            for ($row = 4; $row <= $highestRow; $row++) {
                $dealer_code = $sheet->getCell("B$row")->getCalculatedValue();
                $plate_no = str_replace(' ', '',$sheet->getCell("H$row")->getCalculatedValue() ?? $class->mergecell_value($sheet,$sheet->getCell("H$row")->getCoordinate()));
                $no_of_trips = $sheet->getCell("I$row")->getCalculatedValue() ?? $class->mergecell_value($sheet,$sheet->getCell("I$row")->getCoordinate());
                $car_model = strtoupper($sheet->getCell("D$row")->getCalculatedValue());
                $car_model_id = $car_model_arr[$car_model] ?? false;
                $cs_no = $sheet->getCell("C$row")->getCalculatedValue();
                $unit_order++;
                if ($dealer_code !== null && $cs_no !== null){
                    if(!$car_model_id){
                        return ['status'=>'error', 'message' =>$car_model.' does not exist in the car list'];
                    }
                    if(!isset($dealer_arr[$dealer_code])){
                        return ['status'=>'error', 'message' =>'Dealer '.$dealer_code.' does not exist in the dealership list'];
                    }

                    $invoice_date = $class->excelDateToPhpDate($sheet->getCell("G$row")->getCalculatedValue());
                    $inspection_start = $class->excelTimeToPhpTime($sheet->getCell("J$row")->getCalculatedValue());
                    if ($invoice_date == false && $inspection_start == false) {
                        return ['status'=>'error', 'message' =>'Invalid invoice date format on row'.$row];
                    }

                    $this->haulage_block_unit = [
                        'status'=>1,
                        'block_id'=>null,
                        'status'=>1,
                        'unit_order'=>$unit_order,
                        'haulage_id' =>$haulage_id,
                        'dealer_id' => $dealer_arr[$dealer_code],
                        'car_model_id' =>$car_model_id,
                        'cs_no' => $cs_no,
                        'color_description' => $sheet->getCell("E$row")->getCalculatedValue(),
                        'updated_location' => $sheet->getCell("F$row")->getCalculatedValue(),
                        'invoice_date' =>$invoice_date,
                        'inspected_start' =>$inspection_start,
                        'inspected_end' => $sheet->getCell("K$row")->getCalculatedValue(),
                        'vld_instruction' => $sheet->getCell("O$row")->getCalculatedValue(),
                        'hub' => $sheet->getCell("A$row")->getCalculatedValue() ?? 'SVC',
                        'status'=>1,
                        'created_by'=>$user_id,
                    ];
                    if (isset($this->haulage_block[$this->block])) {
                        $this->haulage_block[$this->block]['block_units'][] = $this->haulage_block_unit;
                    } else {
                        if($plate_no == ''){
                            return ['status'=>'error',  'message' =>'Empty tractor plate number on row '.$row];
                        }
                        if(!isset($this->tractor_arr[$plate_no])){
                            return ['status'=>'error', 'message' =>'Plate No. '.$plate_no.$row.' does not exist in the tractor list'];
                        }
                        if(!isset($this->tractor_arr[$plate_no]['trailer_id'])){
                            return ['status'=>'error', 'message' =>'There is no trailer assign to tractor '.$plate_no];
                        }
                        $this->haulage_block[$this->block] = [
                            'haulage_id' =>$haulage_id,
                            'block_number' =>$this->block,
                            'dealer_id'=>null,
                            'tractor_id' =>$this->tractor_arr[$plate_no]['tractor_id'],
                            'trailer_id' =>$this->tractor_arr[$plate_no]['trailer_id'],
                            'pdriver' =>$this->tractor_arr[$plate_no]['pdriver'],
                            'sdriver' =>$this->tractor_arr[$plate_no]['sdriver'],
                            'no_of_trips'=>  $no_of_trips,
                            'block_units' => [$this->haulage_block_unit],
                            'batch'=>$rq->upload_batch,
                            'created_by'=>$user_id,
                            'status'=>1,
                            'is_exported' => 0,
                        ];
                    }
                }else{
                    foreach ($this->haulage_block as $haulage_blocks) {
                        $haulage_block_id = TmsHaulageBlock::create($haulage_blocks);
                        $block_id = $haulage_block_id->id;
                        $block_units = array_map(function ($unit) use ($block_id) {
                            $unit['block_id'] = $block_id;
                            return $unit;
                        }, $haulage_blocks['block_units']);
                        if (!empty($block_units)) {
                            TmsHaulageBlockUnit::insert($block_units);
                        }
                    }
                    if ($sheet->getCell("B".($row + 1))->getCalculatedValue() === null &&
                        $sheet->getCell("C".($row + 1))->getCalculatedValue() === null &&
                        $sheet->getCell("B".($row + 2))->getCalculatedValue() === null &&
                        $sheet->getCell("C".($row + 2))->getCalculatedValue() === null &&
                        $sheet->getCell("B".($row + 3))->getCalculatedValue() === null &&
                        $sheet->getCell("C".($row + 3))->getCalculatedValue() === null
                        ) {
                        break;
                    }
                    $this->haulage_block = []; $this->block++; $unit_order=0;
                }
            }

            unset($spreadsheet);
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'File uploaded successfully','payload'=>$result['upload_count']]);

        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update_block_units(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $units = json_decode($rq->units,true);
            $haulageID = Crypt::decrypt($rq->haulage_id);
            $units_arr = [];

            foreach($units as $data){
                $haulage_id = Crypt::decrypt($data['haulage_id']);
                $block_id = $data['block_id']!=null?Crypt::decrypt($data['block_id']):null;
                $car_model_id = Crypt::decrypt($data['unit_id']);
                $status = $data['status'];
                $new_unit_order = isset($data['unit_order']) && $block_id?$data['unit_order']:0;

                // Get the units in the block, excluding the current one
                $query = TmsHaulageBlockUnit::find($car_model_id);

                if($query->haulage_id != $haulage_id && $query->haulage_id !==null){
                    return ['status'=>'error', 'message' =>'Haulage ID Mismatch'];
                }

                //REMOVE AND RE-ORDER UNIT ORDER
                if ($new_unit_order == 0) {
                    // Adjust unit_order of remaining items
                    $temp_query = TmsHaulageBlockUnit::where('block_id', $query->block_id)
                        ->where('unit_order', '>', $query->unit_order)
                        ->where('id', '!=', $query->id)
                        ->orderBy('unit_order', 'asc')
                        ->get();
                    foreach ($temp_query as $temp) {
                        // Shift down unit_order for items greater than the removed item's order
                        $temp->unit_order = $temp->unit_order - 1;
                        $temp->save();
                    }
                } else {

                    $temp_query = TmsHaulageBlockUnit::where('block_id', $block_id)
                    ->where('unit_order', '<=', $new_unit_order)
                    ->where('id','!=',$query->id)
                    ->orderBy('unit_order', 'asc')
                    ->get();

                    // Calculate the max value of unit_order
                    $max_value = $temp_query->max('unit_order');
                    // Ensure that new_unit_order doesn't exceed max_value
                    $arr = [];
                    if ($new_unit_order <= $max_value) {
                        foreach ($temp_query as $temp) {
                            // if ($new_unit_order == 1){
                            //     if ($temp->unit_order >= 1) {
                            //         $old_unit_order = $temp->unit_order;
                            //         $temp->unit_order = $temp->unit_order + 1;
                            //         $temp->save();
                            //         $arr[] = [
                            //             'unit_order' => $temp->unit_order,
                            //             'old_unit_order' => $old_unit_order
                            //         ];
                            //     }
                            // }
                            // elseif($new_unit_order == $max_value){
                            //     if ($temp->unit_order <= $max_value) {
                            //         $old_unit_order = $temp->unit_order;
                            //         $temp->unit_order = $temp->unit_order - 1;
                            //         $temp->save();
                            //         $arr[] = [
                            //             'unit_order' => $temp->unit_order,
                            //             'old_unit_order' => $old_unit_order
                            //         ];
                            //     }
                            // }else{
                            // }

                                // Shift up unit_order
                                if ($new_unit_order > $query->unit_order && $temp->unit_order > $query->unit_order && $temp->unit_order <= $new_unit_order) {
                                    $old_unit_order = $temp->unit_order;
                                    $temp->unit_order = $temp->unit_order - 1;
                                    $temp->save();
                                    $arr[] = [
                                        'unit_order' => $temp->unit_order,
                                        'old_unit_order' => $old_unit_order
                                    ];

                                }
                                // Shift down unit_order
                                elseif ($new_unit_order < $query->unit_order && $temp->unit_order >= $new_unit_order) {
                                $old_unit_order = $temp->unit_order;
                                    $temp->unit_order = $temp->unit_order + 1;
                                    $temp->save();
                                    $arr[] = [
                                        'unit_order' => $temp->unit_order,
                                        'old_unit_order' => $old_unit_order
                                    ];
                                }

                            // if ($new_unit_order == 1){
                            //     if ($temp->unit_order >= 1) {
                            //         $temp->unit_order = $temp->unit_order + 1;
                            //         $temp->save();
                            //     }
                            // }
                            // else{
                            //     // Shift up unit_order
                            //     if ($new_unit_order > $query->unit_order && $temp->unit_order > $query->unit_order && $temp->unit_order <= $new_unit_order) {
                            //         $temp->unit_order = $temp->unit_order - 1;
                            //         $temp->save();
                            //     }
                            //     // Shift down unit_order
                            //     elseif ($new_unit_order < $query->unit_order && $temp->unit_order >= $new_unit_order) {
                            //         $temp->unit_order = $temp->unit_order + 1;
                            //         $temp->save();
                            //     }
                            // }

                        }
                    }
                }
                // Assign the new unit order to the current record
                $query->unit_order = $new_unit_order;
                $query->block_id = $block_id;
                $query->haulage_id = $haulage_id;
                $query->status = $status;
                $query->updated_by = $user_id;
                $query->vld_instruction = empty(trim($query->vld_instruction))? $data['multipickup']: $data['multipickup'] . ' ' . $query->vld_instruction;
                $query->save();

                // Prepare the response array
                $units_arr[] = [
                    'dealer_code' => $query->dealer->code,
                    'inspection_time' => $query->inspected_start ? date('m/d/Y', strtotime($query->inspected_start)) : '--',
                    'hub' => $query->hub,
                    'remarks' => $query->vld_instruction ?? '-',
                    'is_transfer' =>$query->is_transfer,
                    'encrypted_id' =>Crypt::encrypt($query->id),
                ];
            }

            $payload = base64_encode(json_encode($units_arr));
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'payload'=>$payload
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status'=>400,
                'message' =>$e->getMessage()
            ]);
        }
    }

    public function finalize_plan(Request $rq)
    {
        try{
            DB::beginTransaction();
            $haulage_id = Crypt::decrypt($rq->id);
            $user_id = Auth::user()->emp_id;
            $batch = $rq->batch;

            if ($batch == 'All Batch') {
                $batch_count = TmsHaulage::find($haulage_id)->batch_count;

                for($x=1;$x<=$batch_count;$x++){
                    // Check if batch exists
                    $batchExist = TmsHaulageBlock::where('haulage_id', $haulage_id)
                    ->where('batch',$x)
                    ->whereNull('is_deleted')
                    ->where('status', 1)
                    ->exists();

                    if (!$batchExist) {
                        return response()->json(['status' => 400, 'message' => 'There is no trip blocks in batch '.$x]);
                        exit(0);
                    }
                }
            }

            TmsHaulageBlock::when($batch!='All Batch', function($q) use($batch){
                $q->where('batch',$batch);
            })
            ->where([['haulage_id',$haulage_id],['is_deleted',null],['status',1]])
            ->update([
                'status'=>2,
                'updated_by'=>$user_id,
                'updated_at'=>Carbon::now()
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Hauling Plan is Saved']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function add_tripblock(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);
            $user_id = Auth::user()->emp_id;

            $block_number = TmsHaulageBlock::where([['haulage_id',$id],['batch',$rq->batch]])->max('block_number') ?? 0;
            $query = TmsHaulageBlock::create([
                'haulage_id' =>$id,
                'status'=>1,
                'block_number' =>$block_number+1,
                'batch' =>$rq->batch,
                'no_of_trips' =>1,
                'created_by'=>$user_id
            ]);

            $array[] = [
                'encrypted_id' =>Crypt::encrypt($query->id),
                'block_number' =>$query->block_number,
                'no_of_trips' =>$query->no_of_trips,
                'batch' =>$query->batch,
            ];
            $payload = base64_encode(json_encode($array));
            DB::commit();
            return ['status'=>'success','message' =>'success', 'payload' => $payload];
        }catch(Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function add_block_unit(Request $rq)
    {
        $cluster_id    = Auth::user()->emp_cluster->cluster_id;

        try {
            $dealer_id = Crypt::decrypt($rq->dealer);
        } catch (Exception $e) {
            $dealer_id = false;
        }

        try {
            $car_model_id = Crypt::decrypt($rq->model);
        } catch (Exception $e) {
            $car_model_id = false;
        }

        if(!$car_model_id){
            $insert = TmsClusterCarModel::create([
                'cluster_id' => $cluster_id,
                'car_model'=>$rq->model,
                'color_description' => $rq->color_description,
                'is_active' =>1
            ]);
            $car_model_id = $insert->id;
        }

        if(!$dealer_id){
            $insert = TmsClientDealership::create([
                'name' => $rq->dealer,
                'code' => $this->getInitials($rq->dealer),
                'is_active' =>1
            ]);
            $dealer_id = $insert->id;
        }

        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);

            TmsHaulageBlockUnit::create([
                'status'=>0,
                'haulage_id'=>$id,
                'dealer_id'=>$dealer_id,
                'car_model_id'=>$car_model_id,
                'cs_no'=>$rq->cs_no,
                'color_description'=>$rq->color_description,
                'updated_location'=>$rq->location,
                'hub'=>$rq->hub,
                'vld_instruction'=>$rq->remarks,
                'invoice_date'=>Carbon::createFromFormat('m-d-Y',$rq->invoice_date)->format('Y-m-d'),
                'created_by'=>Auth::user()->emp_id
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Added Successfully']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function remove_tripblock(Request $rq)
    {
        try{
            DB::beginTransaction();
            $haulage_id = Crypt::decrypt($rq->id);
            $block_id = Crypt::decrypt($rq->block_id);
            $user_id = Auth::user()->emp_id;
            TmsHaulageBlock::where([['haulage_id',$haulage_id],['id',$block_id]])->update([
                'is_deleted'=>1,
                'deleted_by'=>$user_id,
                'deleted_at'=>Carbon::now()
            ]);
            TmsHaulageBlockUnit::where('block_id',$block_id)->update([
                'is_deleted'=>1,
                'deleted_by'=>$user_id,
                'deleted_at'=>Carbon::now()
            ]);
            DB::commit();
            return ['status'=>'success','message' =>'success'];
        }catch(Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function remove_unit(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->unit_id);
            $haulage_id = Crypt::decrypt($rq->id);
            $user_id = Auth::user()->emp_id;
            TmsHaulageBlockUnit::where([['id',$id],['haulage_id',$haulage_id]])->update([
                'is_deleted'=>1,
                'deleted_by'=>$user_id,
                'deleted_at'=>Carbon::now()
            ]);
            DB::commit();
            return ['status'=>'success','message' =>'Unit remove successfully'];
        }catch(Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function reupload_hauling_plan(Request $rq)
    {
        try{
            DB::beginTransaction();
            $haulage_id = Crypt::decrypt($rq->id);
            $query = TmsHaulageBlock::where([['haulage_id',$haulage_id],['batch',$rq->upload_batch]]);
            $block_ids = $query->pluck('id');
            $query->update([
                'is_deleted' => 1,
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->emp_id,
            ]);
            TmsHaulageBlockUnit::whereIn('block_id',$block_ids)->update([
                'is_deleted' => 1,
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->emp_id,
            ]);
            $result = (new HaulageList)->reupload_hauling_plan($rq);
            if ($result['status'] != 'success') {  return response()->json($result); }
            DB::commit();
            return self::hauling_plan(
                $rq->merge(['reupload_haulage' => true])
            );
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function reupload_masterlist(Request $rq)
    {
        try{
            DB::beginTransaction();
            $haulage_id = Crypt::decrypt($rq->id);
            TmsHaulageBlock::where([['haulage_id',$haulage_id],['batch',$rq->batch]])->update([
                'is_deleted' => 1,
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->emp_id,
            ]);
            TmsHaulageBlockUnit::where('haulage_id',$haulage_id)->update([
                'is_deleted' => 1,
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->emp_id,
            ]);
            $result = (new HaulageList)->reupload_masterlist($rq);
            if ($result['status'] != 'success') {  return response()->json($result); }
            DB::commit();
            return self::masterlist($rq);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update_block_status(Request $rq)
    {
        try{
            DB::beginTransaction();
            if($rq->status == 1){
                for($i=1;$i<=2;$i++){
                    $newRequest = $rq->merge(['batch' => $i]);
                    $response = self::finalize_plan($newRequest);
                    $decodedResponse = json_decode($response->getContent(), true);
                    if($decodedResponse['status'] == '400'){
                        return response()->json([
                            'status' => '400',
                            'message' =>  'Something went wrong. Try again later'
                        ]);
                    }
                }
                DB::commit();
                return ['status'=>'success','message' =>'success'];
            }
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update_tripblock_position(Request $rq)
    {
        try{
            DB::beginTransaction();

            $dragged_tripblock = Crypt::decrypt($rq->draggedDataId);
            $swapped_triblock = Crypt::decrypt($rq->swappedDataId);

            $swap = TmsHaulageBlock::find($swapped_triblock);
            $drag = TmsHaulageBlock::find($dragged_tripblock);

            $temp_block_number = $drag->block_number;
            $drag->block_number = $swap->block_number;
            $drag->save();

            $swap->block_number = $temp_block_number;
            $swap->save();

            DB::commit();
            return ['status'=>'success','message' =>'success'];

        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update_transfer(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);
            $unit_id = Crypt::decrypt($rq->unit_id);
            $transfer = $rq->transfer;
            $query = TmsHaulageBlockUnit::where([['id',$unit_id],['haulage_id',$id]])->first();

            if(stripos($query->vld_instruction, 'MP') !== false){
                return ['status'=>'error','message' =>'You cant check "Transfer" if the remarks have "MP"'];
            }
            $query->is_transfer = $transfer;
            $query->save();
            DB::commit();
            return ['status'=>'success','message' =>'success'];
        } catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update_unit_remarks(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);
            $unit_id = Crypt::decrypt($rq->unit_id);
            $remarks = $rq->remarks;
            $query = TmsHaulageBlockUnit::where([['id',$unit_id],['haulage_id',$id]])->first();

            if((stripos($query->vld_instruction, 'MP') !== false || stripos($remarks, 'MP') !== false) && $query->is_transfer){
                return ['status'=>'error','message' =>'You cant put "MP" if the transfer is checked'];
            }

            $query->vld_instruction = $remarks;
            $query->save();

            DB::commit();
            return ['status'=>'success','message' =>'Remarks updated successfully'];
        } catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function tripblock_list(Request $rq)
    {
        $batch = isset($rq->batch) ? $rq->batch : null;
        $search = isset($rq->search) ? $rq->search : null;
        $filter = isset($rq->filter) ? $rq->filter : null;
        $id = Crypt::decrypt($rq->id);
        $cluster_id = Auth::user()->emp_cluster->cluster_id;

        $data = TmsHaulageBlock::with('block_unit')
        ->when($search, function($query, $search) {
            $query->where(function($query) use ($search) {
                // Search by block_number
                $query->where('block_number', 'LIKE', "%$search%");

                // Search by dealer's name or code in related models
                $query->orWhereHas('block_unit', function($q) use ($search) {
                    $q->whereHas('dealer', function($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%")
                        ->orWhere('code', 'LIKE', "%$search%");
                    });
                });
            });
        })
        ->when(isset($filter) && $filter != 'Show All', function($q) use ($filter) {
            return $q->where('is_exported', $filter);
        })
        ->when(isset($batch) && $batch != 'Show All', function($q) use ($batch) {
            return $q->where('batch', $batch);
        })
        ->whereHas('block_unit')
        ->where(function ($query) {
            // Handle soft delete logic
            $query->where('is_deleted', '!=', 1)->orWhereNull('is_deleted');
        })
        ->where('haulage_id', $id)->orderBy('block_number','ASC')
        ->get();

        $data = $data->transform(function ($item,$key) {
            $dealer_arr = [];
            $dealer_code_arr = [];
            $is_multipickup = false;
            $unit_count = 0;
            $item->count = $key+1;
            $is_mp = false;
            $is_tt = false;
            $hub  = 'SVC';

            foreach ($item->block_unit as $unit) {
                $dealer = $unit->dealer;
                if (!in_array($dealer->name, $dealer_arr)) {
                    $dealer_arr[] = $dealer->name;   // Add dealer's name to the array
                    $dealer_code_arr[] = $dealer->code; // Add dealer's code to the array
                }
                if($unit->hub == 'SVC' || ($unit->hub == 'BVC' && $unit->is_transfer)){
                    $hub = 'SVC';
                }elseif($unit->hub == 'BVC' || ($unit->hub == 'SVC' && $unit->is_transfer)){
                    $hub = 'BVC';
                }

                if($unit->is_transfer){
                    $is_tt = true;
                }

                if (stripos($unit->vld_instruction, 'MP') !== false) {
                    $is_mp = true;
                }
                $unit_count++;
            }

            // Assign the dealer names (or concatenated names) to $item->name
            $item->name = $dealer_code_arr ? implode(', ', $dealer_code_arr) : 'Trip Block #'.$key+1;
            $item->unit_count = $unit_count;
            $item->block_batch = $item->batch;
            $item->exported_at = $item->is_exported==1? Carbon::parse($item->exported_at)->format('m/d/Y g:i A'): '--';
            $item->hub = $hub;
            $item->is_transfer = $is_tt;
            $item->is_multipickup = $is_mp;
            $item->encrypt_id = Crypt::encrypt($item->id);
            return $item;
        });

        return response()->json([ 'status' => 'success', 'message' => 'success', 'payload' => base64_encode(json_encode($data)) ]);
    }

    public function export_reports(Request $rq)
    {
        try{
            $report = match($rq->format){
                '1', '2' => self::export_tripblock($rq),
                // '2' => $this->format_2($rq),
                'Hauling Plan' => self::export_haulage($rq),
                default => 'Handler Not Found',
            };

            if($report == 'Handler Not Found'){
                return response()->json(['status'=>'error','message' =>'Something went wrong. Try again later.']);
                exit(0);
            }

            if($report['status'] == 'success'){

                $tripblocks_ids = [];
                $haulage_id = Crypt::decrypt($rq->id);

                TmsHaulageBlock::with('block_unit')->when(isset($rq->tripblock_ids), function($q) use ($rq){
                    $tripblock_ids = json_decode(base64_decode($rq->tripblock_ids));
                    foreach($tripblock_ids as $tripblock_id){
                        $tripblocks_ids[] = Crypt::decrypt($tripblock_id);
                    }

                    $q->whereIn('id',$tripblocks_ids);
                })
                ->where('haulage_id',$haulage_id)->update([
                    'is_exported' => 1,
                    'exported_at' => Carbon::now(),
                    'exported_by' => Auth::user()->emp_id,
                ]);

                return response()->json($report);
            }else{
                return response()->json(['status'=>'error','message' =>$report['message']]);
            }

        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function export_tripblock($rq)
    {
        try{

            if(!isset($rq->tripblock_ids)){
                return ['status'=>400,'message' =>'No trip block selected'];
            }

            if(!isset($rq->id)){
                return ['status'=>400,'message' =>'No haulage selected'];
            }

            $filePath = 'cluster_b/template/TMP DOWNLOAD TEMPLATE.xlsx';
            $check_file = Storage::disk('public')->exists($filePath);

            if(!$check_file){
                return ['status'=>400,'message' =>'File not found'];
            }

            $haulage_id = Crypt::decrypt($rq->id);
            $tripblock_ids = json_decode(base64_decode($rq->tripblock_ids));
            $tripblocks_ids = [];

            foreach($tripblock_ids as $tripblock_id){
                $tripblocks_ids[] = Crypt::decrypt($tripblock_id);
            }
            $planning_date = TmsHaulage::find($haulage_id)->planning_date;
            $formatted_date = Carbon::parse($planning_date)->format('F j Y');
            $formatted_date = strtoupper(Carbon::parse($planning_date)->format('F')) . Carbon::parse($planning_date)->format(' j Y');

            $data = TmsHaulageBlock::with([
                'block_unit'=>function($q){
                    $q->orderByRaw("CASE WHEN vld_instruction LIKE '%MP%' THEN 1 ELSE 0 END, id");
                }
            ])->whereIn('id',$tripblocks_ids)->where('haulage_id',$haulage_id)->orderBy('block_number','ASC')->get();

            if($data->isEmpty()){
                return ['status'=>'error','message' =>'No data found'];
            }

            // Load the file
            $fullFilePath = Storage::disk('public')->path($filePath);
            $spreadsheet = IOFactory::load($fullFilePath);
            $sheet = $spreadsheet->getActiveSheet();

            $row = 4;
            $unit_count = 0;
            $blocks_number_arr = [];
            $temp_arr = [];
            $columnsToUnhide = ['M', 'N', 'O', 'P', 'Q'];
            $is_mp = false;
            foreach($data as $tripblock){

                $block_number = $tripblock->block_number;
                $block_units = $tripblock->block_unit;
                $site = 'SR';
                $batch = 'B-'.$tripblock->batch;

                if($is_mp == true){
                    $row++;
                }

                foreach($block_units as $block_unit){
                    $is_mp = false;
                    if(!in_array($block_number,$blocks_number_arr)){
                        if($row != 4){
                            $row++;
                        }
                        $blocks_number_arr[] = $block_number;
                    }

                    //format date
                    if($block_unit->invoice_date){
                        $invoice_date = Carbon::parse($block_unit->invoice_date)->format('m/d/Y');
                    }else{
                        $invoice_date = '';
                    }

                    $hub = strtolower($block_unit->hub);
                    $assigned_lsp = $block_unit->assigned_lsp;
                    $dealer_code = $block_unit->dealer->code;
                    $cs_no = $block_unit->cs_no;
                    $location = $block_unit->updated_location;
                    $car_model = $block_unit->car->car_model;
                    $color_description = $block_unit->color_description;
                    $invoice_date = date('m-d-Y',strtotime($block_unit->invoice_date));
                    $vld_instruction = $block_unit->vld_instruction !=0 ?$block_unit->vld_instruction:'--';
                    $is_transfer = $block_unit->is_transfer !=0 ?$block_unit->is_transfer:'';
                    // $est_departure_time = $block_unit->est_departure_time;
                    // $est_loading_time = $block_unit->est_loading_time;

                    if($hub == 'bvc'){
                        $site = 'BTG';
                    }else{
                        $site = 'SR';
                    }

                    if($is_transfer){
                        $is_transfer ='TT';
                        if($hub == 'svc'){
                            $hub = 'bvc';
                        }elseif($hub == 'bvc'){
                            $hub = 'svc';
                        }
                    }

                    if (stripos($vld_instruction, 'MP') !== false) {
                        if($is_transfer == 'TT'){
                            return ['status'=>400,'message' =>'Remarks have MP and Transer is checked. Please check the trip block'];
                            exit(0);
                        }
                        $is_transfer = 'MP';
                        $is_mp = true;
                    }

                    $sheet->setCellValue('A' . ($row+($is_mp?1:0)),  strtoupper($hub));
                    $sheet->setCellValue('B' . ($row+($is_mp?1:0)),$is_transfer);
                    $sheet->setCellValue('C' . ($row+($is_mp?1:0)),$assigned_lsp);
                    $sheet->setCellValue('D' . ($row+($is_mp?1:0)), $dealer_code);
                    $sheet->setCellValue('E' . ($row+($is_mp?1:0)), $cs_no);
                    $sheet->setCellValue('F' . ($row+($is_mp?1:0)), $location);
                    $sheet->setCellValue('G' . ($row+($is_mp?1:0)), $car_model);
                    $sheet->setCellValue('H' . ($row+($is_mp?1:0)), $color_description);
                    $sheet->setCellValue('I' . ($row+($is_mp?1:0)), $invoice_date);
                    // $sheet->setCellValue('J' . ($add_row+$row), $delivery_date);
                    // $sheet->setCellValue('K' . ($add_row+$row), $est_departure_time);
                    // $sheet->setCellValue('L' . ($add_row+$row), $est_loading_time);
                    $sheet->setCellValue('U' . ($row+($is_mp?1:0)), $vld_instruction);

                    if($block_unit->units_from == 'VISMIN'){
                        $dropoff_point = $block_unit->shipping_lines.'-'.$block_unit->origin_port;
                        $sheet->setCellValue('M' . ($row+($is_mp?1:0)), $dropoff_point);

                        foreach ($columnsToUnhide as $column) {
                            $sheet->getColumnDimension($column)->setVisible(true);
                        }
                    }
                    $row++;
                    $unit_count++;
                    $temp_arr[] = $block_unit->car->car_model;
                }

            }

            $sheet->setCellValue('L' .($sheet->getHighestDataRow()+1), $unit_count.' UNITS');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            // $filename = $site.' HUB '.$batch.' '.$formatted_date.'.xlsx';
            $filename = $rq->filename.'.xlsx';
            $tempFilePath = storage_path('app/public/'.$filename);
            $writer->save($tempFilePath);

            // Return the URL where the file can be downloaded
            $downloadUrl = asset('storage/'.$filename);
            return ['status' => 'success', 'payload' => $downloadUrl];


        }catch(Exception $e){
            return ['status'=>400,'message' =>$e->getMessage()];
        }
    }

    public function export_haulage($rq){
        try{

            $filePath = 'cluster_b/template/HAULING PLAN TEMPLATE.xlsx';
            $check_file = Storage::disk('public')->exists($filePath);

            if(!$check_file){
                return ['status'=>'error','message' =>'File not found'];
            }

            $haulage_id = Crypt::decrypt($rq->id);
            $planning_date = TmsHaulage::find($haulage_id)->planning_date;
            $formatted_date = Carbon::parse($planning_date)->format('F j Y');
            $formatted_date = strtoupper(Carbon::parse($planning_date)->format('F')) . Carbon::parse($planning_date)->format(' j Y');
            $data = TmsHaulageBlock::with([
                'block_unit'=>function($q){
                    $q->orderByRaw("CASE WHEN vld_instruction LIKE '%MP%' THEN 1 ELSE 0 END, id");
                }
            ])
            ->whereHas('block_unit')->where('haulage_id',$haulage_id)->where('is_deleted',null)->get();

            if($data->isEmpty()){
                return ['status'=>'error','message' =>'No data found'];
            }
            // Load the file
            $fullFilePath = Storage::disk('public')->path($filePath);
            $spreadsheet = IOFactory::load($fullFilePath);
            $sheet = $spreadsheet->getActiveSheet();

            $borderStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN, // You can change this to any border style you want
                        'color' => ['argb' => 'FF000000'], // Black color
                    ],
                ],
            ];

            $row = 4;
            $unit_count = 0;
            $blocks_number_arr = [];

            foreach($data as $tripblock){
                $block_number = $tripblock->block_number;
                $block_units = $tripblock->block_unit;
                // $first_row = 0;
                // $last_row = 0;

                foreach($block_units as $block_unit){
                    $add_row = 0;
                    if(!in_array($block_number,$blocks_number_arr)){
                        $first_row = $row+$add_row;
                        $blocks_number_arr[] = $block_number;
                        if($row != 4){
                            $add_row = 1;
                            $first_row = $row+$add_row;
                            $row++;
                        }
                    }

                    //format date
                    if($block_unit->invoice_date){
                        $invoice_date = Carbon::parse($block_unit->invoice_date)->format('m/d/Y');
                    }else{
                        $invoice_date = '';
                    }

                    $hub = $block_unit->hub;
                    $dealer_code = $block_unit->dealer->code;
                    $cs_no = $block_unit->cs_no;
                    $location = $block_unit->updated_location;
                    $car_model = $block_unit->car->car_model;
                    $color_description = $block_unit->color_description;
                    $vld_instruction = $block_unit->vld_instruction !=0 ?$block_unit->vld_instruction:'--';

                    $sheet->setCellValue('A' . ($row),  $hub);
                    $sheet->setCellValue('B' . ($row), $dealer_code);
                    $sheet->setCellValue('C' . ($row), $cs_no);
                    $sheet->setCellValue('D' . ($row), $car_model);
                    $sheet->setCellValue('E' . ($row), $color_description);
                    $sheet->setCellValue('F' . ($row), $location);
                    $sheet->setCellValue('G' . ($row), $invoice_date);
                    $sheet->setCellValue('O' . ($row), $vld_instruction);

                    // Check if the value contains the word 'priority'
                    if (stripos($vld_instruction, 'priority') !== false) {
                        // Apply red font color to the text
                        $sheet->getStyle('O' . ($add_row+$row))->getFont()->applyFromArray([
                            'color' => ['argb' => Color::COLOR_RED],
                        ]);
                    }

                    $unit_count++;
                    $last_row = $first_row+$unit_count;
                    $row++;
                }

                $sheet->mergeCells('H'.($first_row).':H'.($row-1));
                $sheet->mergeCells('I'.($first_row).':I'.($row-1));

                // Optionally set a value for the merged cell (e.g., a space or text)
                $sheet->setCellValue('H'.($first_row), '');
                $sheet->setCellValue('I'.($first_row), ' ');

                // Set background color to yellow for the merged range
                $sheet->getStyle('H'.($first_row).':H'.($row-1))->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => Color::COLOR_YELLOW,
                        ],
                    ],
                ]);

                $sheet->getStyle('I'.($first_row).':I'.($row-1))->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => Color::COLOR_YELLOW,
                        ],
                    ],
                ]);

                // if($first_row ==21){
                //     dd('B'.($first_row).':O'.($row-1));
                // }
                $sheet->getStyle('B'.($first_row).':O'.($row-1))->applyFromArray($borderStyle);
            }

            // $sheet->setCellValue('L' .$row, $unit_count.' UNITS');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'FINAL HAULING PLAN - '.$formatted_date.'.xlsx';
            $tempFilePath = storage_path('app/public/'.$filename);
            $writer->save($tempFilePath);

            // Return the URL where the file can be downloaded
            $downloadUrl = asset('storage/'.$filename);
            return ['status' => 'success', 'payload' => $downloadUrl];


        }catch(Exception $e){
            return ['status'=>400,'message' =>$e->getMessage()];
        }
    }

    public function download_tmp($id)
    {
        try {
            // Load the existing template from the storage folder
            $filePath = storage_path('app/public/cluster_b/template/TMP UPLOAD TEMPLATE.xlsx');
            if (!file_exists($filePath)) {
                return ['status' => 'error', 'message' => 'Template file not found'];
            }

            $id = Crypt::decrypt($id);
            $query = TmsHaulage::find($id);
            $keys = json_decode($query->upload_key,true) ?? [];

            $encryptedKey = null;
            // Loop until a unique encrypted key is generated
            do {
                $randomString = Str::random(10) . time();
                $encryptedKey = Crypt::encrypt($randomString);
            } while (in_array($encryptedKey, $keys));

            $keys[$encryptedKey] ='TMP';
            $query->upload_key = json_encode($keys);
            $query->save();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Encrypt the string and set it to N1
            $sheet->setCellValue('AB1', $encryptedKey);

            // Optionally hide the column N or make the font color white
            $sheet->getColumnDimension('AB')->setVisible(false);  // Hide column N
            $sheet->getStyle('AB1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE); // Make text invisible

            // Prepare the writer for the response without saving the file
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode('TMP_TEMPLATE.xlsx').'"');
            ob_end_clean();
            $writer->save('php://output');
            exit(0);

        } catch (Exception $e) {
            return ['status' => 400, 'message' => $e->getMessage()];
        }

    }

    public function download_vismin($id)
    {
        try {
            // Load the existing template from the storage folder
            $filePath = storage_path('app/public/cluster_b/template/VISMIN UPLOAD TEMPLATE.xlsx');
            if (!file_exists($filePath)) {
                return ['status' => 'error', 'message' => 'Template file not found'];
            }

            $id = Crypt::decrypt($id);
            $query = TmsHaulage::find($id);
            $keys = json_decode($query->upload_key,true) ?? [];

            $encryptedKey = null;
            // Loop until a unique encrypted key is generated
            do {
                $randomString = Str::random(10) . time();
                $encryptedKey = Crypt::encrypt($randomString);
            } while (in_array($encryptedKey, $keys));

            $keys[$encryptedKey] ='VISMIN';
            $query->upload_key = json_encode($keys);
            $query->save();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Encrypt the string and set it to N1
            $sheet->setCellValue('AB1', $encryptedKey);

            // Optionally hide the column N or make the font color white
            $sheet->getColumnDimension('AB')->setVisible(false);  // Hide column N
            $sheet->getStyle('AB1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE); // Make text invisible

            // Prepare the writer for the response without saving the file
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode('VISMIN_TEMPLATE.xlsx').'"');
            ob_end_clean();
            $writer->save('php://output');
            exit(0);

        } catch (Exception $e) {
            return ['status' => 400, 'message' => $e->getMessage()];
        }
    }
}
