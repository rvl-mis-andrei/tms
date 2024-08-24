<?php

namespace App\Services;

use DateTime;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Phpspreadsheet
{
    public function read($filePath,$is_readonly,$is_reademptycells,$sheet)
    {
        $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($filePath);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
        $reader = self::validate_sheets($reader,$sheet,$filePath,$file_type);
        $reader->setReadDataOnly($is_readonly);
        $reader->setReadEmptyCells($is_reademptycells);
        $spreadsheet = $reader->load($filePath);
        $spreadsheet->getCalculationEngine()->setCalculationCacheEnabled(false);
        unset($reader);
        $spreadsheet = $spreadsheet->getActiveSheet();
        $row   = $spreadsheet->getHighestDataRow();
        $row <= 1 && throw new \Exception("Sheet '{$sheet}' does not have any data.");
        return [$spreadsheet, $row];
    }

    public function validate_sheets($reader,$sheet,$filePath,$file_type){

        if($file_type == 'Csv'){ return $reader; }

        $sheetNames = $reader->listWorksheetNames($filePath);
        if (!in_array($sheet, $sheetNames)) {
            throw new \Exception("Sheet '{$sheet}' does not exist in the file.");
        }else{
            return $reader->setLoadSheetsOnly($sheet);
        }
    }

    public function mergecell_value($sheet,$coordinate){
        $mergedCells = $sheet->getMergeCells();
        [$currentColLetter, $currentRow] = Coordinate::coordinateFromString($coordinate);
        $currentCol = $this->letter_to_number($currentColLetter);
        foreach ($mergedCells as $mergedRange) {
            [$startCell, $endCell] = explode(':', $mergedRange);
            [$startColLetter, $startRow] = Coordinate::coordinateFromString($startCell);
            [$endColLetter, $endRow] = Coordinate::coordinateFromString($endCell);
            $startCol = $this->letter_to_number($startColLetter);
            $endCol = $this->letter_to_number($endColLetter);
            if ($currentCol === $startCol && $currentRow >= $startRow && $currentRow <= $endRow) {
                return $sheet->getCell($startCell)->getCalculatedValue();
                break;
            }
        }
    }

    public function letter_to_number($columnLetter) {
        $columnLetter = strtoupper($columnLetter);
        $columnNumber = 0;
        $length = strlen($columnLetter);

        for ($i = 0; $i < $length; $i++) {
            $columnNumber = ($columnNumber * 26) + (ord($columnLetter[$i]) - ord('A') + 1);
        }

        return $columnNumber;
    }

    public function excelDateToPhpDate($excelDate) {
        if (!is_numeric($excelDate)) {
            $date = DateTime::createFromFormat('m/d/Y', $excelDate);
            if ($date) {
                return $date->format('Y-m-d');
            }
        }

        $unixDate = ($excelDate - 25569) * 86400;
        return gmdate("Y-m-d", $unixDate);
    }

    public function excelTimeToPhpTime($excelTime) {
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
