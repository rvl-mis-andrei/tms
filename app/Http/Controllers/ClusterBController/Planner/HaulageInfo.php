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
use App\Services\Phpspreadsheet;
use App\Services\Planner\HaulageList;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
            $query = TmsHaulageBlock::with(['block_unit'=> function($query) {
                $query->orderBy('unit_order', 'asc');
            }])->where([['batch',$rq->batch],['haulage_id',$id],['is_deleted',null]])->get();
            $array = [];
            if($query){
                foreach($query as $data){
                    $block_unit = [];
                    $dealer_arr = [];
                    $dealer_code_arr = [];
                    foreach($data->block_unit as $row){
                        $dealer = $row->dealer;
                        if (!in_array($dealer->name, $dealer_arr)){
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
                            'remarks'=>$row->remarks ?? '--',
                            'status'=>$row->status,
                        ];
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
            $query   = TmsHaulageBlockUnit::where('hub', 'LIKE', "%$rq->hub%")->where([['haulage_id',$id],['is_deleted',null]])->get();
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
                            'remarks'=>$data->remarks ?? '--',
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
                    $this->haulage_block_unit[] = [
                        'status'=>0,
                        'block_id'=>null,
                        'haulage_id'=>$haulage_id,
                        'dealer_id' => $dealer_arr[$dealer_code],
                        'car_model_id' =>$car_model_id,
                        'cs_no' => $cs_no,
                        'color_description' => $sheet->getCell("E$row")->getCalculatedValue(),
                        'updated_location' => $sheet->getCell("C$row")->getCalculatedValue(),
                        'invoice_date' =>$class->excelDateToPhpDate($sheet->getCell("F$row")->getCalculatedValue()),
                        'invoice_time' =>$class->excelTimeToPhpTime($sheet->getCell("G$row")->getCalculatedValue()),
                        'planning_cutoff' =>$sheet->getCell("H$row")->getCalculatedValue(),
                        'vdn_number' =>$sheet->getCell("I$row")->getCalculatedValue(),
                        'vld_instruction' =>$sheet->getCell("J$row")->getCalculatedValue(),
                        'hub' => $sheet->getCell("K$row")->getCalculatedValue(),
                        'assigned_lsp' =>$sheet->getCell("L$row")->getCalculatedValue(),
                        'vld_planner_confirmation' =>$sheet->getCell("M$row")->getCalculatedValue(),
                        'created_by'=>$user_id,
                    ];
                }else{
                    TmsHaulageBlockUnit::insert($this->haulage_block_unit);
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
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
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
                        'invoice_date' =>$class->excelDateToPhpDate($sheet->getCell("G$row")->getCalculatedValue()),
                        'inspected_start' =>$class->excelTimeToPhpTime($sheet->getCell("J$row")->getCalculatedValue()),
                        'inspected_end' => $sheet->getCell("K$row")->getCalculatedValue(),
                        'remarks' => $sheet->getCell("O$row")->getCalculatedValue(),
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
                            'batch'=>$rq->batch,
                            'created_by'=>$user_id,
                            'status'=>1,
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
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update_block_units(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $units = json_decode($rq->units,true);
            $units_arr = [];

            foreach($units as $data){
                $haulage_id = Crypt::decrypt($data['haulage_id']);
                $block_id = $data['block_id']!=null?Crypt::decrypt($data['block_id']):null;
                $car_model_id = Crypt::decrypt($data['unit_id']);
                $status = $data['status'];
                $unit_order = isset($data['unit_order'])?$data['unit_order']:null;

                $query = TmsHaulageBlockUnit::find($car_model_id);

                // Re-assign unit order
                if($unit_order){
                    $temp_query = TmsHaulageBlockUnit::where([['block_id',$block_id],['unit_order',$unit_order],['car_model_id','!=',$car_model_id]])->first();
                    if($temp_query){
                        $temp_query->unit_order = $query->unit_order;
                        $temp_query->save();
                    }
                }

                $query->block_id = $block_id;
                $query->haulage_id = $haulage_id;
                $query->status = $status;
                $query->updated_by = $user_id;
                $query->unit_order = $unit_order;
                $query->save();

                $units_arr[]=[
                    'dealer_code'=>$query->dealer->code,
                    'inspection_time'=>$query->inspected_start?date('m/d/Y',strtotime($query->inspected_start)):'--',
                    'hub'=>$query->hub,
                    'remarks'=>$query->remarks??'--',
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
            TmsHaulageBlock::where([['haulage_id',$haulage_id],['is_deleted',null],['status',1],['batch',$rq->batch]])
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
            ];
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
                'remarks'=>$rq->remarks,
                'invoice_date'=>Carbon::createFromFormat('m-d-Y',$rq->invoice_date)->format('Y-m-d'),
                'created_by'=>Auth::user()->emp_id
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Added Successfully']);

        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function remove_tripblock(Request $rq)
    {
        try{
            $haulage_id = Crypt::decrypt($rq->id);
            $block_id = Crypt::decrypt($rq->block_id);
            $user_id = Auth::user()->emp_id;
            TmsHaulageBlock::where([['batch',$rq->batch],['haulage_id',$haulage_id],['id',$block_id]])->update([
                'is_deleted'=>1,
                'deleted_by'=>$user_id,
                'deleted_at'=>Carbon::now()
            ]);
            TmsHaulageBlockUnit::where('block_id',$block_id)->update([
                'is_deleted'=>1,
                'deleted_by'=>$user_id,
                'deleted_at'=>Carbon::now()
            ]);
            return ['status'=>'success','message' =>'success'];
        }catch(Exception $e) {
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
            $id = Crypt::decrypt($rq->unit_id);
            $haulage_id = Crypt::decrypt($rq->id);
            $user_id = Auth::user()->emp_id;
            TmsHaulageBlockUnit::where([['id',$id],['haulage_id',$haulage_id]])->update([
                'is_deleted'=>1,
                'deleted_by'=>$user_id,
                'deleted_at'=>Carbon::now()
            ]);
            return ['status'=>'success','message' =>'Unit remove successfully'];
        }catch(Exception $e) {
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
            $query = TmsHaulageBlock::where([['haulage_id',$haulage_id],['batch',$rq->batch]]);
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
            return self::hauling_plan($rq);
        }catch(Exception $e){
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
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update_block_status(Request $rq)
    {
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
            return ['status'=>'success','message' =>'success'];
        }
    }

}
