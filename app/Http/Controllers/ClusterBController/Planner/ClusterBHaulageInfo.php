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
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $rq->validate([  'masterlist' => 'required|mimes:xlsx,xls,xlsm,csv|max:20480' ]);
        $dealer = TmsClientDealership::all()->pluck('id', 'code')->toArray();
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $car_model = TmsClusterCarModel::where('cluster_id',$cluster_id)->get()->pluck('id', 'car_model')->toArray();

        $filePath = $rq->file('masterlist')->getRealPath();
        $reader = IOFactory::createReader(IOFactory::identify($filePath));
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();
        unset($reader);

        $data = [];
        for ($row = 3; $row <= $highestRow; $row++) {
            if($sheet->getCell("A$row")->getCalculatedValue() == null){
                break;
            }
            $data[]=[
                'cluster_id' =>$cluster_id,
                'haulage_id' =>Crypt::decrypt($rq->id),
                'dealer_id' =>$dealer[$sheet->getCell("A$row")->getCalculatedValue()],
                'invoice_date' =>$this->excelDateToPhpDate($sheet->getCell("F$row")->getCalculatedValue()),
                'invoice_time' =>$this->excelTimeToPhpTime($sheet->getCell("G$row")->getCalculatedValue()),
                'planning_cutoff' =>$sheet->getCell("H$row")->getCalculatedValue(),
                'vld_instruction' =>$sheet->getCell("J$row")->getCalculatedValue(),
                'updated_location' =>$sheet->getCell("C$row")->getCalculatedValue(),
                'vdn_number' =>$sheet->getCell("I$row")->getCalculatedValue(),
                'vld_planner_confirmation' =>$sheet->getCell("M$row")->getCalculatedValue(),
                'hub' =>$sheet->getCell("K$row")->getCalculatedValue(),
                'assigned_lsp' =>$sheet->getCell("L$row")->getCalculatedValue(),
                // 'car_model_id' =>$car_model[$sheet->getCell("D$row")->getCalculatedValue()],
                'cs_no' =>$sheet->getCell("B$row")->getCalculatedValue(),
            ];
        }
        unset($spreadsheet);
        dd($data);
        return response()->json(['status' => 'success', 'message' => 'File uploaded and processed successfully']);
    }

    public function hauling_plan(Request $rq)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $rq->validate([  'hauling_plan' => 'required|mimes:xlsx,xls,xlsm|max:20480' ]);

        $filePath = $rq->file('hauling_plan')->getRealPath();
        $reader = IOFactory::createReader(IOFactory::identify($filePath));
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();
        unset($reader);

        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $dealer = TmsClientDealership::all()->pluck('id', 'code')->toArray();
        $car_model = TmsClusterCarModel::where('cluster_id',$cluster_id)->get()->pluck('id', 'car_model')->toArray();
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

        $haulage_dealer_data = [];
        for ($row = 4; $row <= $highestRow; $row++) {

            $dealer_code = $sheet->getCell("B$row")->getCalculatedValue();
            $plate_no = $sheet->getCell("H$row")->getCalculatedValue();

            if ($dealer_code === null && $sheet->getCell("C$row")->getCalculatedValue()===null){

                // $haulage_dealers_id = '';
                // foreach($haulage_dealer_data  as $haulage_dealers){
                //     $haulage_dealers_id = TmsHaulageDealer::create($haulage_dealers);
                //     foreach($haulage_dealers['tms_haulage_unit'] as $haulage_unit){
                //         $haulage_unit['haulage_dealer_id'] = $haulage_dealers_id;
                //         TmsHaulageUnit::create($haulage_unit);
                //     }
                // }

                // $haulage_dealer = [];  $haulage_unit = [];
                // if($sheet->getCell("B"+($row+1))->getCalculatedValue()==null &&
                //    $sheet->getCell("C"+($row+1))->getCalculatedValue()==null){
                //     break;
                // }
                DB::transaction(function () use (&$haulage_dealer_data) {
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
                });

                $haulage_dealer_data = [];

                // Check next row to decide if breaking
                if ($sheet->getCell("B" . ($row + 1))->getCalculatedValue() === null &&
                    $sheet->getCell("C" . ($row + 1))->getCalculatedValue() === null) {
                    break;
                }
            }else{
                $haulage_unit_data = [
                    'haulage_dealer_id'=>null,
                    'color_description' => $sheet->getCell("E$row")->getCalculatedValue(),
                    'location' => $sheet->getCell("F$row")->getCalculatedValue(),
                    'invoice_date' =>$this->excelDateToPhpDate($sheet->getCell("G$row")->getCalculatedValue()),
                    'inspection_start' =>$this->excelTimeToPhpTime($sheet->getCell("J$row")->getCalculatedValue()),
                    'inspection_end' => $sheet->getCell("K$row")->getCalculatedValue(),
                    'cs_no' => $sheet->getCell("C$row")->getCalculatedValue(),
                    // 'car_model_id' =>$car_model[$sheet->getCell("D$row")->getCalculatedValue()],
                    'remarks' => $sheet->getCell("O$row")->getCalculatedValue(),
                ];

                if (isset($haulage_dealer_data[$dealer_code])) {
                    $haulage_dealer_data[$dealer_code]['tms_haulage_unit'][] = $haulage_unit_data;
                } else {
                    $haulage_dealer_data[$dealer_code] = [
                        'haulage_id' =>Crypt::decrypt($rq->id),
                        'dealer_id' => $dealer[$dealer_code],
                        'plate_no' => $plate_no,
                        'haulage_id' =>Crypt::decrypt($rq->id),
                        'tractor_id' =>$tractor_arr[$plate_no]['tractor_id'],
                        'trailer_id' =>$tractor_arr[$plate_no]['trailer_id'],
                        'pdriver' =>$tractor_arr[$plate_no]['pdriver'],
                        'sdriver' =>$tractor_arr[$plate_no]['sdriver'],
                        'tms_haulage_unit' => [$haulage_unit_data],
                    ];
                }
            }
        }

        unset($spreadsheet);
        return response()->json(['status' => 'success', 'message' => 'File uploaded and processed successfully']);
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

    public function process_csv($rq,$dealer,$car_model,$cluster_id)
    {
        $filePath = $rq->file('masterlist')->getRealPath();
        $reader = IOFactory::createReader('Csv');
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Determine the highest row and column
        $highestRow = $sheet->getHighestDataRow();
        $data = [];
        for ($row = 3; $row <= $highestRow; $row++) {

            $data[]=[
                'cluster_id' =>$cluster_id,
                'haulage_id' =>Crypt::decrypt($rq->id),
                'dealer_id' =>$dealer[$sheet->getCell("A$row")->getValue()],
                'invoice_date' =>$sheet->getCell("F$row")->getValue(),
                'invoice_time' =>$sheet->getCell("G$row")->getValue(),
                'planning_cutoff' =>$sheet->getCell("H$row")->getValue(),
                'vld_instruction' =>$sheet->getCell("J$row")->getValue(),
                'updated_location' =>$sheet->getCell("C$row")->getValue(),
                'vdn_number' =>$sheet->getCell("I$row")->getValue(),
                'vld_planner_confirmation' =>$sheet->getCell("M$row")->getValue(),
                'hub' =>$sheet->getCell("K$row")->getValue(),
                'assigned_lsp' =>$sheet->getCell("L$row")->getValue(),
                // 'car_model_id' =>$car_model[$sheet->getCell("D$row")->getValue()],
                'cs_no' =>$sheet->getCell("B$row")->getValue(),
            ];
        }
        unset($spreadsheet);
        return $data;
    }


    public function process_excel($rq,$dealer,$car_model,$cluster_id)
    {
        $filePath = $rq->file('masterlist')->getRealPath();
        $reader = IOFactory::createReader(IOFactory::identify($filePath));
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();
        unset($reader);

        $data = [];
        for ($row = 3; $row <= $highestRow; $row++) {
            if($sheet->getCell("A$row")->getCalculatedValue() == null){
                break;
            }
            $data[]=[
                'cluster_id' =>$cluster_id,
                'haulage_id' =>Crypt::decrypt($rq->id),
                'dealer_id' =>$dealer[$sheet->getCell("A$row")->getCalculatedValue()],
                'invoice_date' =>$this->excelDateToPhpDate($sheet->getCell("F$row")->getCalculatedValue()),
                'invoice_time' =>$this->excelTimeToPhpTime($sheet->getCell("G$row")->getCalculatedValue()),
                'planning_cutoff' =>$sheet->getCell("H$row")->getCalculatedValue(),
                'vld_instruction' =>$sheet->getCell("J$row")->getCalculatedValue(),
                'updated_location' =>$sheet->getCell("C$row")->getCalculatedValue(),
                'vdn_number' =>$sheet->getCell("I$row")->getCalculatedValue(),
                'vld_planner_confirmation' =>$sheet->getCell("M$row")->getCalculatedValue(),
                'hub' =>$sheet->getCell("K$row")->getCalculatedValue(),
                'assigned_lsp' =>$sheet->getCell("L$row")->getCalculatedValue(),
                // 'car_model_id' =>$car_model[$sheet->getCell("D$row")->getCalculatedValue()],
                'cs_no' =>$sheet->getCell("B$row")->getCalculatedValue(),
            ];
        }
        unset($spreadsheet);
        return $data;
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
