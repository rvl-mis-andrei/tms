<?php

namespace App\Services\Dispatcher;

use App\Models\TmsClusterClient;
use App\Models\TmsHaulage;
use App\Models\TmsHaulageAttendance;
use App\Services\Planner\HaulageList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class DispatcherPage
{
    public function client_info($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $data = (new ClusterClientList)->info($rq);
            $data = json_decode(base64_decode($data['payload']),true);
            return view('layout.dispatcher.shared.resources.client_info', compact('data'))->render();
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
            return view('layout.dispatcher.shared.resources.tractor_trailer_info', compact('data'))->render();
        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function haulage_info($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $data = TmsHaulage::with('haulage_attendance')->find($id);
            $data->filenames = json_decode($data->filenames,true);
            if ($data && $data->haulage_attendance && $data->haulage_attendance->isNotEmpty()) {
                $data->is_final_attendance = $data->haulage_attendance->every(fn($att) => $att->is_final == 1) ? 1 : 0;
            } else {
                $data->is_final_attendance = 0;
            }

            $user = Auth::user();
            $pendingAttendance = TmsHaulageAttendance::where([
                ['cluster_id',$user->emp_cluster->cluster_id],
                ['haulage_id','!=',$id],
                ['is_final',0]
            ])->exists();

            return view('cluster_b.dispatcher.dispatch.hauling_plan_info', compact('data','pendingAttendance'))->render();
        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }
}
