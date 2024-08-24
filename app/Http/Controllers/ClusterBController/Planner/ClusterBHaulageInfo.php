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
use App\Models\TmsHaulageBlock;
use App\Models\TmsHaulageBlockUnit;
use App\Services\Phpspreadsheet;

class ClusterBHaulageInfo extends Controller
{
    protected $tractor_arr = [];
    protected $haulage_block = [];
    protected $haulage_block_unit=[];
    protected $block = 1;

    public function tripblock(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = TmsHaulageBlock::with('block_unit')->where([['batch',$rq->batch],['haulage_id',$id]])->get();
            $array = [];
            if($query){
                foreach($query as $data){
                    $block_unit = [];
                    $dealer_arr = [];
                    foreach($data->block_unit as $row){
                        $dealer = $row->dealer;
                        if (!in_array($dealer->name, $dealer_arr)){
                            $dealer_arr[] = $dealer->name;
                        }
                        $block_unit[]=[
                            'encrypted_id'=>Crypt::encrypt($data->id),
                            'dealer_code'=>$dealer->code,
                            'model'=>$row->car->car_model,
                            'cs_no'=>$row->cs_no,
                            'color_description'=>$row->color_description,
                            'invoice_date'=>date('m/d/Y',strtotime($row->invoice_date)),
                            'updated_location'=>$row->updated_location,
                            'inspection_start'=>date('g:i A',strtotime($row->inspected_start)),
                            'hub'=>$row->hub,
                            'remarks'=>$row->remarks,
                        ];
                    }
                    $array[]=[
                        'encrypted_id'=>Crypt::encrypt($data->id),
                        'block_number'=>$data->block_number,
                        'batch'=>$data->batch,
                        'no_of_trips'=>$data->no_of_trips,
                        'dealer'=>implode(', ', $dealer_arr),
                        'block_units'=>$block_unit,
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

    public function allocation(Request $rq)
    {

    }

    public function underload(Request $rq)
    {

    }

    public function masterlist(Request $rq)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        try{
            DB::beginTransaction();
            $class         = new Phpspreadsheet;
            [$sheet,$highestRow] = $class->read($rq->file('masterlist')->getRealPath(),true,true,'RVL');
            $cluster_id    = Auth::user()->emp_cluster->cluster_id;
            $dealer_arr    = TmsClientDealership::all()->pluck('id', 'code')->toArray();
            $car_model_arr = TmsClusterCarModel::where('cluster_id',$cluster_id)->get()->pluck('id', 'car_model')->toArray();
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
                    $this->haulage_block_unit = [
                        'block_id'=>null,
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
                    ];
                    if (isset($this->haulage_block[$dealer_code])) {
                        $this->haulage_block[$dealer_code]['block_units'][] = $this->haulage_block_unit;
                    } else {
                        $this->haulage_block[$dealer_code] = [
                            'haulage_id' =>Crypt::decrypt($rq->id),
                            'dealer_id'=>null,
                            'block_units' => [$this->haulage_block_unit],
                            'batch'=>$rq->batch
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
                        $sheet->getCell("D".($row + 1))->getCalculatedValue() === null &&
                        $sheet->getCell("B".($row + 2))->getCalculatedValue() === null &&
                        $sheet->getCell("D".($row + 2))->getCalculatedValue() === null &&
                        $sheet->getCell("B".($row + 3))->getCalculatedValue() === null &&
                        $sheet->getCell("D".($row + 3))->getCalculatedValue() === null
                        ) {
                        break;
                    }
                    $this->haulage_block = []; $this->block++;
                }
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'File uploaded successfully']);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function hauling_plan(HaulingPlanRequest $rq)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        try{
            DB::beginTransaction();
            $class  = new Phpspreadsheet;
            [$sheet,$highestRow] = $class->read($rq->file('hauling_plan')->getRealPath(),false,true,'PDI');

            $cluster_id    = Auth::user()->emp_cluster->cluster_id;
            $dealer_arr    = TmsClientDealership::all()->pluck('id', 'code')->toArray();
            $car_model_arr = TmsClusterCarModel::where('cluster_id',$cluster_id)->get()->pluck('id', 'car_model')->toArray();
            $tractors      = TractorTrailerDriver::with('tractor:id,plate_no')->get();
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
                if ($dealer_code !== null && $cs_no !== null){
                    if(!$car_model_id){
                        return ['status'=>'error', 'message' =>$car_model.' does not exist in the car list'];
                    }
                    if(!isset($dealer_arr[$dealer_code])){
                        return ['status'=>'error', 'message' =>'Dealer '.$dealer_code.' does not exist in the dealership list'];
                    }
                    $this->haulage_block_unit = [
                        'block_id'=>null,
                        'dealer_id' => $dealer_arr[$dealer_code],
                        'car_model_id' =>$car_model_id,
                        'cs_no' => $cs_no,
                        'color_description' => $sheet->getCell("E$row")->getCalculatedValue(),
                        'updated_location' => $sheet->getCell("F$row")->getCalculatedValue(),
                        'invoice_date' =>$class->excelDateToPhpDate($sheet->getCell("G$row")->getCalculatedValue()),
                        'inspected_start' =>$class->excelTimeToPhpTime($sheet->getCell("J$row")->getCalculatedValue()),
                        'inspected_end' => $sheet->getCell("K$row")->getCalculatedValue(),
                        'remarks' => $sheet->getCell("O$row")->getCalculatedValue(),
                        'hub' => $sheet->getCell("A$row")->getCalculatedValue(),
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
                            'haulage_id' =>Crypt::decrypt($rq->id),
                            'block_number' =>$this->block,
                            'dealer_id'=>null,
                            'tractor_id' =>$this->tractor_arr[$plate_no]['tractor_id'],
                            'trailer_id' =>$this->tractor_arr[$plate_no]['trailer_id'],
                            'pdriver' =>$this->tractor_arr[$plate_no]['pdriver'],
                            'sdriver' =>$this->tractor_arr[$plate_no]['sdriver'],
                            'no_of_trips'=>  $no_of_trips,
                            'block_units' => [$this->haulage_block_unit],
                            'batch'=>$rq->batch
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
                    $this->haulage_block = []; $this->block++;
                }
            }
        unset($spreadsheet);
        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'File uploaded successfully']);

        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update_block(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Updated Successfully']);

        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update_block_units(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'success']);

        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }
}
