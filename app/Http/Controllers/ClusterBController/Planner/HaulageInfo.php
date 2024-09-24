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
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
                $query->orderBy('unit_order', 'asc');
            }])
            ->when($batch,function($query,$batch){
                $query->where('batch',$batch);
            })
            ->where([['haulage_id',$id],['is_deleted',null]])->get();
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
                            if(!empty($dealer_arr)){
                                $is_multipickup = true;
                            }
                            $dealer_arr[] = $dealer->name;
                            $dealer_code_arr[] = $dealer->code;
                        }
                        $block_unit[]=[
                            'encrypted_id'=>Crypt::encrypt($row->id),
                            'dealer_code'=>$dealer->code,
                            'model'=>$row->car->car_model,
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
                            'model'=>$data->car->car_model,
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

            $result = (new HaulageList)->move_file($rq,'masterlist');
            if ($result['status'] != 'success' && $result['payload'] === false) {  return response()->json($result); }

            $class         = new Phpspreadsheet;
            [$sheet,$highestRow] = $class->read($result['payload'],true,true,'RVL');

            $cluster_id    = Auth::user()->emp_cluster->cluster_id;
            $dealer_arr    = TmsClientDealership::all()->pluck('id', 'code')->toArray();
            $car_model_arr = TmsClusterCarModel::where('cluster_id',$cluster_id)->get()->pluck('id', 'car_model')->toArray();
            $user_id = Auth::user()->emp_id;

            for ($row = 3; $row <= $highestRow; $row++) {
                $dealer_code = $sheet->getCell("A$row")->getCalculatedValue();
                $car_model = strtoupper($sheet->getCell("D$row")->getCalculatedValue());
                $car_model_id = $car_model_arr[$car_model] ?? false;
                $cs_no = $sheet->getCell("B$row")->getCalculatedValue();
                if ($dealer_code !== null && $cs_no !== null){
                    if(!$car_model_id){
                        return ['status'=>'error', 'message' =>$car_model.' does not exist in the car list'];
                    }
                    if(!isset($dealer_arr[$dealer_code])){
                        return ['status'=>'error', 'message' =>'Dealer '.$dealer_code.' does not exist in the dealership list'];
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
                        'dealer_id' => $dealer_arr[$dealer_code],
                        'car_model_id' =>$car_model_id,
                        'cs_no' => $cs_no,
                        'color_description' => $sheet->getCell("E$row")->getCalculatedValue(),
                        'updated_location' => $sheet->getCell("C$row")->getCalculatedValue(),
                        'invoice_date' =>$invoice_date,
                        'invoice_time' =>$invoice_time,
                        'planning_cutoff' =>$sheet->getCell("H$row")->getCalculatedValue(),
                        'vdn_number' =>$sheet->getCell("I$row")->getCalculatedValue(),
                        'vld_instruction' =>$sheet->getCell("J$row")->getCalculatedValue(),
                        'hub' => $sheet->getCell("K$row")->getCalculatedValue(),
                        'assigned_lsp' =>$sheet->getCell("L$row")->getCalculatedValue(),
                        'vld_planner_confirmation' =>$sheet->getCell("M$row")->getCalculatedValue(),
                        'created_by'=>$user_id,
                    ];
                }else{
                    $this->haulage_block_unit = self::filter_duplicates($this->haulage_block_unit,$haulage_id);
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

    public function filter_duplicates($block_units, $haulage_id)
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

        foreach ($block_units as $unit) {
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
            $result = (new HaulageList)->move_file($rq);
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
            $query = TmsHaulageBlock::create([
                'haulage_id' =>$id,
                'status'=>1,
                'block_number' =>$rq->block_number,
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
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);
            $dealer_id = Crypt::decrypt($rq->dealer);
            $car_model_id = Crypt::decrypt($rq->model);
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

    public function update_transfer(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);
            $unit_id = Crypt::decrypt($rq->unit_id);
            $transfer = $rq->transfer;
            TmsHaulageBlockUnit::where([['id',$unit_id],['haulage_id',$id]])->update([
                'is_transfer' => $transfer
            ]);
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
        ->where('haulage_id', $id)
        ->get();

        $data = $data->transform(function ($item,$key) {
            $dealer_arr = [];
            $dealer_code_arr = [];
            $is_multipickup = false;
            $unit_count = 0;
            $item->count = $key+1;
            foreach ($item->block_unit as $unit) {
                $dealer = $unit->dealer;
                if (!in_array($dealer->name, $dealer_arr)) {
                    if (!empty($dealer_arr)) {
                        $is_multipickup = true; // If there are multiple dealers, set this flag
                    }
                    $dealer_arr[] = $dealer->name;   // Add dealer's name to the array
                    $dealer_code_arr[] = $dealer->code; // Add dealer's code to the array
                }
                $unit_count++;
            }

            // Assign the dealer names (or concatenated names) to $item->name
            $item->name = $dealer_code_arr ? implode(', ', $dealer_code_arr) : 'Trip Block #'.$key+1;
            $item->is_multipickup = $is_multipickup;
            $item->unit_count = $unit_count;
            $item->block_batch = $item->batch;
            $item->exported_at = $item->is_exported==1? Carbon::parse($item->exported_at)->format('m/d/Y'): '--';
            $item->encrypt_id = Crypt::encrypt($item->id);
            return $item;
        });

        return response()->json([ 'status' => 'success', 'message' => 'success', 'payload' => base64_encode(json_encode($data)) ]);
    }

    public function export_reports(Request $rq)
    {
        try{
            $report = match($rq->format){
                '1', '2' => $this->export_tripblock($rq),
                // '2' => $this->format_2($rq),
                'Hauling Plan' => $this->export_haulage($rq),
                default => false,
            };

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

            $filePath = 'cluster_b_export/Format_1.xlsx';
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

            $data = TmsHaulageBlock::with('block_unit')->whereIn('id',$tripblocks_ids)->where('haulage_id',$haulage_id)->get();

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


            foreach($data as $tripblock){

                $block_number = $tripblock->block_number;
                $block_units = $tripblock->block_unit;
                $add_row = 0;
                $site = 'SR';
                $batch = 'B-'.$tripblock->batch;

                foreach($block_units as $block_unit){

                    if(!in_array($block_number,$blocks_number_arr)){
                        if($row != 4){
                            $add_row = 1;   //add space if not the first block
                        }
                        $blocks_number_arr[] = $block_number;
                    }

                    //format date
                    if($block_unit->invoice_date){
                        $invoice_date = Carbon::parse($block_unit->invoice_date)->format('m/d/Y');
                    }else{
                        $invoice_date = '';
                    }

                    $hub = $block_unit->hub;
                    $assigned_lsp = $block_unit->assigned_lsp;
                    $dealer_code = $block_unit->dealer->code;
                    $cs_no = $block_unit->cs_no;
                    $location = $block_unit->updated_location;
                    $car_model = $block_unit->car->car_model;
                    $color_description = $block_unit->color_description;
                    $invoice_date = $block_unit->invoice_date;
                    $vld_instruction = $block_unit->vld_instruction;
                    $is_transfer = $block_unit->is_transfer?'TT':'';
                    // $est_departure_time = $block_unit->est_departure_time;
                    // $est_loading_time = $block_unit->est_loading_time;

                    if($hub == 'BVC'){
                        $site = 'BTG';
                    }else{
                        $site = 'SR';
                    }

                    $sheet->setCellValue('A' . ($add_row+$row),  $hub);
                    $sheet->setCellValue('B' . ($add_row+$row),$is_transfer);
                    $sheet->setCellValue('C' . ($add_row+$row),$assigned_lsp);
                    $sheet->setCellValue('D' . ($add_row+$row), $dealer_code);
                    $sheet->setCellValue('E' . ($add_row+$row), $cs_no);
                    $sheet->setCellValue('F' . ($add_row+$row), $location);
                    $sheet->setCellValue('G' . ($add_row+$row), $car_model);
                    $sheet->setCellValue('H' . ($add_row+$row), $color_description);
                    $sheet->setCellValue('I' . ($add_row+$row), $invoice_date);
                    // $sheet->setCellValue('J' . ($add_row+$row), $delivery_date);
                    // $sheet->setCellValue('K' . ($add_row+$row), $est_departure_time);
                    // $sheet->setCellValue('L' . ($add_row+$row), $est_loading_time);
                    $sheet->setCellValue('U' . ($add_row+$row), $vld_instruction);

                    $row++;
                    $unit_count++;
                    $add_row = 0;
                }

            }

            $sheet->setCellValue('L' .$row, $unit_count.' UNITS');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = $site.' HUB '.$batch.' '.$formatted_date.'.xlsx';
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

            $filePath = 'cluster_b_export/Hauling_Plan.xlsx';
            $check_file = Storage::disk('public')->exists($filePath);

            if(!$check_file){
                return ['status'=>'error','message' =>'File not found'];
            }

            $haulage_id = Crypt::decrypt($rq->id);
            $planning_date = TmsHaulage::find($haulage_id)->planning_date;
            $formatted_date = Carbon::parse($planning_date)->format('F j Y');
            $formatted_date = strtoupper(Carbon::parse($planning_date)->format('F')) . Carbon::parse($planning_date)->format(' j Y');
            $data = TmsHaulageBlock::with('block_unit')->whereHas('block_unit')->where('haulage_id',$haulage_id)->get();

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
                $add_row = 0;
                $first_row = 0;
                $last_row = 0;

                foreach($block_units as $block_unit){
                    $add_row = 0;
                    if(!in_array($block_number,$blocks_number_arr)){
                        if($row != 4){
                            $add_row = 1;
                        }
                        $first_row = $row+$add_row;
                        $blocks_number_arr[] = $block_number;
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
                    $vld_instruction = $block_unit->vld_instruction;

                    $sheet->setCellValue('A' . ($add_row+$row),  $hub);
                    $sheet->setCellValue('B' . ($add_row+$row), $dealer_code);
                    $sheet->setCellValue('C' . ($add_row+$row), $cs_no);
                    $sheet->setCellValue('D' . ($add_row+$row), $car_model);
                    $sheet->setCellValue('E' . ($add_row+$row), $color_description);
                    $sheet->setCellValue('F' . ($add_row+$row), $location);
                    $sheet->setCellValue('G' . ($add_row+$row), $invoice_date);
                    $sheet->setCellValue('O' . ($add_row+$row), $vld_instruction);

                    // Check if the value contains the word 'priority'
                    if (stripos($vld_instruction, 'priority') !== false) {
                        // Apply red font color to the text
                        $sheet->getStyle('O' . ($add_row+$row))->getFont()->applyFromArray([
                            'color' => ['argb' => Color::COLOR_RED],
                        ]);
                    }

                    $row++;
                    $unit_count++;
                    $last_row = $first_row+$unit_count;
                }

                // Merge cells from H$first_row to H$last_row and set a value (if needed)

                $sheet->mergeCells('H'.($first_row).':H'.($unit_count+3));
                $sheet->mergeCells('I'.($first_row).':I'.($unit_count+3));

                // Optionally set a value for the merged cell (e.g., a space or text)
                $sheet->setCellValue('H'.($first_row), '123');
                $sheet->setCellValue('I'.($first_row), ' 123');

                // Set background color to yellow for the merged range
                $sheet->getStyle('H'.($first_row).':H'.($unit_count+3))->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => Color::COLOR_YELLOW,
                        ],
                    ],
                ]);

                $sheet->getStyle('I'.($first_row).':I'.($unit_count+3))->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => Color::COLOR_YELLOW,
                        ],
                    ],
                ]);

                $sheet->getStyle('B'.($first_row).':O'.($unit_count+3))->applyFromArray($borderStyle);

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

}
