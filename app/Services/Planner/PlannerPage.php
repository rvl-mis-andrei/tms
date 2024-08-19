<?php

namespace App\Services\Planner;

use Exception;
use Illuminate\Support\Facades\Crypt;

class PlannerPage
{
    public function hauling_plan_info($rq)
    {
        try{
            $data = (new HaulageList)->info($rq);
            $data = json_decode(base64_decode($data['payload']),true);
            return view('layout.planner.shared.hauling_plan_info', compact('data'))->render();
        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }
}
