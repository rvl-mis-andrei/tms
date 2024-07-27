<?php

namespace App\Http\Controllers;

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

}
