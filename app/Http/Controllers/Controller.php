<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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

}
