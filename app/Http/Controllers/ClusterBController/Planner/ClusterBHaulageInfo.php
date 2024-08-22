<?php

namespace App\Http\Controllers\ClusterBController\Planner;

use App\Models\TmsClientDealership;
use App\Models\TmsClusterCarModel;
use App\Models\TmsHaulageDealer;
use App\Models\TmsHaulageUnit;
use App\Models\TractorTrailerDriver;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ClusterBHaulageInfo extends Controller
{
    public function list(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = TmsHaulageDealer::with('haulage_unit')->find($id);
            // $payload = base64_encode(json_encode([
            //     'name' =>$query->name,
            //     'remarks' =>$query->remarks,
            //     'status' =>$query->status,
            //     'planning_date' =>$query->planning_date,
            //     'created_by'=>$query->employee->fullname ?? 'No record found',
            //     'created_at'=>Carbon::parse($query->created_at)->format('F j, Y'),
            // ]));
            $payload = base64_encode(json_encode([]));
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
        try{
            DB::beginTransaction();
            ini_set('memory_limit', '-1');
            set_time_limit(0);
            $rq->validate([  'masterlist' => 'required|mimes:xlsx,xls,xlsm|max:20480' ]);
            $filePath = $rq->file('masterlist')->getRealPath();
            $reader = IOFactory::createReader(IOFactory::identify($filePath));
            $reader->setReadEmptyCells(false);
            $reader->setLoadSheetsOnly("RVL");
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestDataRow();
            unset($reader);
            $cluster_id = Auth::user()->emp_cluster->cluster_id;
            $dealer = TmsClientDealership::all()->pluck('id', 'code')->toArray();
            $car_model_arr = TmsClusterCarModel::where('cluster_id',$cluster_id)->get()->pluck('id', 'car_model')->toArray();
            $haulage_dealer_data = [];
            for ($row = 4; $row <= $highestRow; $row++) {
                $dealer_code = $sheet->getCell("A$row")->getCalculatedValue();
                if ($dealer_code === null && $sheet->getCell("C$row")->getCalculatedValue()===null){
                    foreach ($haulage_dealer_data as $dealerData) {
                        $haulageDealer = TmsHaulageDealer::create($dealerData);
                        $haulageDealerId = $haulageDealer->id;
                        $unitsData = array_map(function ($unit) use ($haulageDealerId) {
                            $unit['haulage_dealer_id'] = $haulageDealerId;
                            return $unit;
                        }, $dealerData['tms_haulage_unit']);
                        if (!empty($unitsData)) {
                            TmsHaulageUnit::insert($unitsData);
                        }
                    }
                    $haulage_dealer_data = [];
                    if ($sheet->getCell("B".($row + 4))->getCalculatedValue() === null &&
                        $sheet->getCell("C".($row + 4))->getCalculatedValue() === null) {
                        break;
                    }
                }else{
                    $car_model = $sheet->getCell("D$row")->getCalculatedValue();
                    $car_model_id = isset($car_model_arr[strtoupper($car_model)])?$car_model_arr[strtoupper($car_model)]:false;
                    if(!$car_model_id){
                        return [
                            'status'=>'error',
                            'message' =>'Car model '.$car_model.' does not exist in the car list',
                        ];
                    }
                    $haulage_unit_data = [
                        'haulage_dealer_id'=>null,
                        'color_description' => $sheet->getCell("E$row")->getCalculatedValue(),
                        'updated_location' => $sheet->getCell("C$row")->getCalculatedValue(),
                        'invoice_date' =>$this->excelDateToPhpDate($sheet->getCell("F$row")->getCalculatedValue()),
                        'invoice_time' =>$this->excelTimeToPhpTime($sheet->getCell("G$row")->getCalculatedValue()),
                        'planning_cutoff' =>$this->excelTimeToPhpTime($sheet->getCell("H$row")->getCalculatedValue()),
                        'vdn_number' =>$sheet->getCell("I$row")->getCalculatedValue(),
                        'vld_instruction' =>$sheet->getCell("J$row")->getCalculatedValue(),
                        'assigned_lsp' =>$sheet->getCell("L$row")->getCalculatedValue(),
                        'vld_planner_confirmation' =>$sheet->getCell("M$row")->getCalculatedValue(),
                        'inspected_start' =>$this->excelTimeToPhpTime($sheet->getCell("J$row")->getCalculatedValue()),
                        'inspected_end' => $sheet->getCell("K$row")->getCalculatedValue(),
                        'cs_no' => $sheet->getCell("C$row")->getCalculatedValue(),
                        'car_model_id' =>$car_model_id,
                        'remarks' => $sheet->getCell("O$row")->getCalculatedValue(),
                        'hub' => $sheet->getCell("K$row")->getCalculatedValue(),
                    ];
                    if (isset($haulage_dealer_data[$dealer_code])) {
                        $haulage_dealer_data[$dealer_code]['tms_haulage_unit'][] = $haulage_unit_data;
                    } else {
                        $haulage_dealer_data[$dealer_code] = [
                            'haulage_id' =>Crypt::decrypt($rq->id),
                            'dealer_id' => $dealer[$dealer_code],
                            'haulage_id' =>Crypt::decrypt($rq->id),
                            'tms_haulage_unit' => [$haulage_unit_data],
                            'batch'=>$rq->batch
                        ];
                    }
                }
            }
        unset($spreadsheet);
        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'File uploaded successfully']);

        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function hauling_plan(Request $rq)
    {
        try{
            DB::beginTransaction();
            ini_set('memory_limit', '-1');
            set_time_limit(0);
            $rq->validate([  'hauling_plan' => 'required|mimes:xlsx,xls,xlsm|max:20480' ]);
            $filePath = $rq->file('hauling_plan')->getRealPath();
            $reader = IOFactory::createReader(IOFactory::identify($filePath));
            $reader->setReadEmptyCells(false);
            $reader->setLoadSheetsOnly("PDI");
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestDataRow();
            unset($reader);
            $cluster_id = Auth::user()->emp_cluster->cluster_id;
            $dealer = TmsClientDealership::all()->pluck('id', 'code')->toArray();
            $car_model_arr = TmsClusterCarModel::where('cluster_id',$cluster_id)->get()->pluck('id', 'car_model')->toArray();
            $tractors = TractorTrailerDriver::with('tractor:id,plate_no')->get();
            $tractor_arr = [];
            foreach ($tractors as $data) {
                $tractor_arr[str_replace(' ', '',$data->tractor->plate_no)] = [
                    'tractor_id' => $data->tractor_id,
                    'trailer_id' => $data->trailer_id,
                    'pdriver' => $data->pdriver,
                    'sdriver' => $data->sdriver,
                ];
            }
            $haulage_dealer_data = []; $block = 1;
            for ($row = 4; $row <= $highestRow; $row++) {
                $dealer_code = $sheet->getCell("B$row")->getCalculatedValue();
                if ($dealer_code === null && $sheet->getCell("C$row")->getCalculatedValue()===null){
                    foreach ($haulage_dealer_data as $dealerData) {
                        $haulageDealer = TmsHaulageDealer::create($dealerData);
                        $haulageDealerId = $haulageDealer->id;
                        $unitsData = array_map(function ($unit) use ($haulageDealerId) {
                            $unit['haulage_dealer_id'] = $haulageDealerId;
                            return $unit;
                        }, $dealerData['tms_haulage_unit']);
                        if (!empty($unitsData)) {
                            TmsHaulageUnit::insert($unitsData);
                        }
                    }
                    $haulage_dealer_data = [];  $block++;
                    if ($sheet->getCell("B".($row + 4))->getCalculatedValue() === null &&
                        $sheet->getCell("C".($row + 4))->getCalculatedValue() === null) {
                        break;
                    }
                }else{
                    $car_model = $sheet->getCell("D$row")->getCalculatedValue();
                    $car_model_id = isset($car_model_arr[strtoupper($car_model)])?$car_model_arr[strtoupper($car_model)]:false;
                    if(!$car_model_id){
                        return [
                            'status'=>'error',
                            'message' =>'Car model '.$car_model.' does not exist in the car list',
                        ];
                    }
                    $haulage_unit_data = [
                        'block_number' =>$block,
                        'haulage_dealer_id'=>null,
                        'color_description' => $sheet->getCell("E$row")->getCalculatedValue(),
                        'updated_location' => $sheet->getCell("F$row")->getCalculatedValue(),
                        'invoice_date' =>$this->excelDateToPhpDate($sheet->getCell("G$row")->getCalculatedValue()),
                        'inspected_start' =>$this->excelTimeToPhpTime($sheet->getCell("J$row")->getCalculatedValue()),
                        'inspected_end' => $sheet->getCell("K$row")->getCalculatedValue(),
                        'cs_no' => $sheet->getCell("C$row")->getCalculatedValue(),
                        'car_model_id' =>$car_model_id,
                        'remarks' => $sheet->getCell("O$row")->getCalculatedValue(),
                        'hub' => $sheet->getCell("A$row")->getCalculatedValue(),
                    ];
                    if (isset($haulage_dealer_data[$dealer_code])) {
                        $haulage_dealer_data[$dealer_code]['tms_haulage_unit'][] = $haulage_unit_data;
                    } else {
                        $plate_no = $sheet->getCell("H$row")->getCalculatedValue() ?? $this->merged_cell_value($sheet,$sheet->getCell("H$row")->getCoordinate());
                        $no_of_trips = $sheet->getCell("I$row")->getCalculatedValue() ?? $this->merged_cell_value($sheet,$sheet->getCell("I$row")->getCoordinate());
                        if(empty($plate_no) || $plate_no ==null){
                            return [
                                'status'=>'error',
                                'message' =>'Empty tractor plate number on row '.$row
                            ];
                        }
                        $plate_no = str_replace(' ', '',$plate_no);
                        if(!isset($tractor_arr[$plate_no])){
                            return [
                                'status'=>'error',
                                'message' =>'Tractor plate number '.$plate_no.$row.' does not exist in the tractor list'
                            ];
                        }
                        $haulage_dealer_data[$dealer_code] = [
                            'haulage_id' =>Crypt::decrypt($rq->id),
                            'dealer_id' => $dealer[$dealer_code],
                            'haulage_id' =>Crypt::decrypt($rq->id),
                            'tractor_id' =>$tractor_arr[$plate_no]['tractor_id'],
                            'trailer_id' =>$tractor_arr[$plate_no]['trailer_id'],
                            'pdriver' =>$tractor_arr[$plate_no]['pdriver'],
                            'sdriver' =>$tractor_arr[$plate_no]['sdriver'],
                            'no_of_trips'=>  $no_of_trips,
                            'tms_haulage_unit' => [$haulage_unit_data],
                            'batch'=>$rq->batch
                        ];
                    }
                }
            }
        unset($spreadsheet);
        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'File uploaded successfully']);

        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function create(Request $rq)
    {

    }


    public function update(Request $rq)
    {

    }

    public function delete(Request $rq)
    {

    }

    function excelDateToPhpDate($excelDate) {
        if (!is_numeric($excelDate)) {
            $date = DateTime::createFromFormat('m/d/Y', $excelDate);
            if ($date) {
                return $date->format('Y-m-d');
            }
        }

        $unixDate = ($excelDate - 25569) * 86400;
        return gmdate("Y-m-d", $unixDate);
    }

    function excelTimeToPhpTime($excelTime) {
        if (!is_numeric($excelTime)) {
            $time = DateTime::createFromFormat('g:i:s A', $excelTime);
            if ($time) {
                return $time->format('H:i:s');
            }
        }
        $hours = floor($excelTime * 24);
        $minutes = floor(($excelTime * 24 - $hours) * 60);
        $seconds = floor((($excelTime * 24 - $hours) * 60 - $minutes) * 60);

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
