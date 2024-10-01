<?php

namespace App\Services;

use DateTime;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Phpspreadsheet
{
    public function read($filePath,$is_readonly,$is_reademptycells,$sheet)
    {
        $fullPath = Storage::disk('public')->path($filePath);
        $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($fullPath);

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
        $reader = self::validate_sheets($reader,$sheet,$fullPath,$file_type);
        $reader->setReadDataOnly($is_readonly);
        $reader->setReadEmptyCells($is_reademptycells);
        $spreadsheet = $reader->load($fullPath);
        $spreadsheet->getCalculationEngine()->setCalculationCacheEnabled(false);
        unset($reader);
        $spreadsheet = $spreadsheet->getActiveSheet();
        $row   = $spreadsheet->getHighestDataRow();
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
        // Check if the input is not a number, and attempt to parse it as a date string
        if (!is_numeric($excelDate)) {
            // Try to create a DateTime object from the provided string (4-digit year)
            $date = DateTime::createFromFormat('m/d/Y', $excelDate);
            if ($date && $date->format('m/d/Y') === $excelDate) {
                return $date->format('Y-m-d'); // Return date in Y-m-d format
            }

            // Try with two-digit year
            $date = DateTime::createFromFormat('m/d/y', $excelDate);
            if ($date && $date->format('m/d/y') === $excelDate) {
                return $date->format('Y-m-d'); // Return date in Y-m-d format
            }

            // If no valid date, return false
            return false;
        }
        // If the input is numeric, treat it as an Excel date
        elseif (is_numeric($excelDate)) {
            // Excel's epoch starts on 1900-01-01, represented as 25569
            $unixDate = ($excelDate - 25569) * 86400; // Convert Excel date to Unix timestamp
            return gmdate("Y-m-d", $unixDate); // Return as Y-m-d format
        } else {
            return false; // If neither numeric nor a valid date string, return false
        }
    }

    public function excelTimeToPhpTime($excelTime) {
        // Check if the input is not numeric and attempt to parse it as a time string
        if (!is_numeric($excelTime)) {
            // Normalize the time string to uppercase AM/PM
            $excelTime = strtolower($excelTime); // Convert to lowercase first
            $excelTime = ucfirst($excelTime); // Capitalize 'am' or 'pm'

            // Try to create a DateTime object from the provided time string in the format 'g:i:s a'
            $time = DateTime::createFromFormat('g:i:s a', $excelTime);

            // Check if the time is valid and return in 'H:i:s' format
            if ($time && $time->format('g:i:s a') === $excelTime) {
                return $time->format('H:i:s'); // Return the time in 24-hour format (H:i:s)
            } else {
                return false; // Return false if the string is not a valid time
            }
        }

        // If the input is numeric, convert it assuming it's an Excel time
        if (is_numeric($excelTime)) {
            $hours = floor($excelTime * 24); // Convert the decimal time to hours
            $minutes = floor(($excelTime * 24 - $hours) * 60); // Convert the remainder to minutes
            $seconds = floor((($excelTime * 24 - $hours) * 60 - $minutes) * 60); // Convert the remainder to seconds

            // Return the time formatted as 'H:i:s'
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        // If neither numeric nor a valid time string, return false
        return false;
    }

}
