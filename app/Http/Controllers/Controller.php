<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function _response($message,$code,$status,$payload=null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'payload' => $payload
        ],$code)->throwResponse();
    }

    public function timestamp_format($date,$format="F j, Y")
    {
        return Carbon::parse($date)->format($format);
    }

    function getInitials($string) {
        return strtoupper(implode('', array_map(function($word) {
            return $word[0]; // Get the first letter of each word
        }, explode(' ', $string))));
    }

    // public function letter_to_number($columnLetter) {
    //     $columnLetter = strtoupper($columnLetter);
    //     $columnNumber = 0;
    //     $length = strlen($columnLetter);

    //     for ($i = 0; $i < $length; $i++) {
    //         $columnNumber = ($columnNumber * 26) + (ord($columnLetter[$i]) - ord('A') + 1);
    //     }

    //     return $columnNumber;
    // }

    // public function merged_cell_value($sheet,$cellCoordinate){
    //     $mergedCells = $sheet->getMergeCells();
    //     [$currentColLetter, $currentRow] = Coordinate::coordinateFromString($cellCoordinate);
    //     $currentCol = $this->letter_to_number($currentColLetter);
    //     foreach ($mergedCells as $mergedRange) {
    //         [$startCell, $endCell] = explode(':', $mergedRange);
    //         [$startColLetter, $startRow] = Coordinate::coordinateFromString($startCell);
    //         [$endColLetter, $endRow] = Coordinate::coordinateFromString($endCell);
    //         $startCol = $this->letter_to_number($startColLetter);
    //         $endCol = $this->letter_to_number($endColLetter);
    //         if ($currentCol === $startCol && $currentRow >= $startRow && $currentRow <= $endRow) {
    //             return $sheet->getCell($startCell)->getCalculatedValue();
    //             break;
    //         }
    //     }
    // }

    // function excelDateToPhpDate($excelDate) {
    //     if (!is_numeric($excelDate)) {
    //         $date = DateTime::createFromFormat('m/d/Y', $excelDate);
    //         if ($date) {
    //             return $date->format('Y-m-d');
    //         }
    //     }

    //     $unixDate = ($excelDate - 25569) * 86400;
    //     return gmdate("Y-m-d", $unixDate);
    // }

    // function excelTimeToPhpTime($excelTime) {
    //     if (!is_numeric($excelTime)) {
    //         $time = DateTime::createFromFormat('g:i:s A', $excelTime);
    //         if ($time) {
    //             return $time->format('H:i:s');
    //         }
    //     }
    //     $hours = floor($excelTime * 24);
    //     $minutes = floor(($excelTime * 24 - $hours) * 60);
    //     $seconds = floor((($excelTime * 24 - $hours) * 60 - $minutes) * 60);

    //     return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    // }

}
