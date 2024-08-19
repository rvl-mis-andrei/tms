<?php

namespace App\Services\Planner;

use App\Models\TmsClientDealership;
use App\Models\TmsClusterCarModel;
use App\Models\TmsHaulageDealer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class ClusterBHaulageInfo
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

        // $rq->validate([  'masterlist' => 'required|mimes:xlsx,xls,xlsm,csv|max:20480' ]);
        $dealer = TmsClientDealership::all()->pluck('id', 'code')->toArray();
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $car_model = TmsClusterCarModel::where('cluster_id',$cluster_id)->get()->pluck('id', 'car_model')->toArray();

        $array = match ($rq->file('masterlist')->getClientOriginalExtension()) {

            'csv' => $this->process_csv($rq,$dealer,$car_model,$cluster_id),

            'xls', 'xlsx', 'xlsm' => $this->process_excel($rq,$dealer,$car_model,$cluster_id),

            default => throw new Exception('Unsupported file type.')

        };

        dd($array);

        return response()->json(['status' => 'success', 'message' => 'File uploaded and processed successfully']);
    }

    public function hauling_plan(Request $rq)
    {

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
        $reader->setDelimiter(','); // Set CSV delimiter
        $reader->setEnclosure('"'); // Set CSV enclosure character
        $reader->setEscape('\\'); // Set CSV escape character
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Determine the highest row and column
        $highestRow = $sheet->getHighestDataRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        $data = [];
        for ($row = 1; $row <= $highestRow; $row++) {
            $rowData = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $columnLetter = Coordinate::stringFromColumnIndex($col);
                $cellValue = $sheet->getCell($columnLetter . $row)->getValue();
                $rowData[$columnLetter] = $cellValue;
            }
            $data[] = $rowData;
        }
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
        dd($highestRow);
        $data = [];
        for ($row = 3; $row <= $highestRow; $row++) {
            if(!isset($dealer[$sheet->getCell("A$row")->getCalculatedValue()])){
                dd($sheet->getCell("A$row")->getCalculatedValue());
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
        $unixDate = ($excelDate - 25569) * 86400; // Convert Excel date to Unix timestamp
        return gmdate("Y-m-d", $unixDate); // Format as needed (Y-m-d, d/m/Y, etc.)
    }

    function excelTimeToPhpTime($excelTime) {
        $hours = floor($excelTime * 24);
        $minutes = floor(($excelTime * 24 - $hours) * 60);
        $seconds = floor((($excelTime * 24 - $hours) * 60 - $minutes) * 60);

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
