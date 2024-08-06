<?php

namespace App\Services\Dispatcher;

use App\Models\TmsClusterClient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

class DispatcherPage
{
    public function client_info($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $data = (new ClusterClientList)->info($rq);
            $data = json_decode(base64_decode($data['payload']),true);
            return view('layout.shared.dispatcher.client_info', compact('data'))->render();
        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function tractor_trailer_info($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $data = (new TractorTrailerList)->info($rq);
            $data = json_decode(base64_decode($data['payload']),true);
            return view('layout.shared.dispatcher.tractor_trailer_info', compact('data'))->render();
        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }
}
