<?php

namespace App\Services;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Phpspreadsheet
{
    public function read($file,$is_readonly,$is_reademptycells,$sheet){

        $filePath = $file->getRealPath();

        $reader = IOFactory::createReader(IOFactory::identify($file->getRealPath()));
        $reader->setReadDataOnly($is_readonly);
        $reader->setReadEmptyCells($is_reademptycells);
        $reader->setLoadSheetsOnly($sheet);

        $spreadsheet = $reader->load($filePath);
        unset($reader);

        return $spreadsheet->getActiveSheet();
    }

    public function write($file,$config){

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
