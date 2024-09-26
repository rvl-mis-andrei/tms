<?php

namespace App\Http\Controllers\ClusterBController\Dispatcher;

use App\Http\Controllers\Controller;
use App\Models\TmsHaulageAttendance;
use App\Models\TractorTrailerDriver;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class HaulageAttendance extends Controller
{
    public function create_attendance(Request $rq)
    {
        try{
            DB::beginTransaction();

            $haulage_id = Crypt::decrypt($rq->haulage_id);
            $cluster_id = Auth::user()->emp_cluster->cluster_id;
            $is_present = 1;
            $query = TractorTrailerDriver::where('cluster_id',$cluster_id)->get();

            if(!$query){
                return response()->json(['status' => 'error','message'=>'Something went wrong. Try again later']);
            }

            TmsHaulageAttendance::create([
                'tractor_trailer_id'=>$query->id,
                'haulage_id'=>$haulage_id,
                'is_present_pdriver' => $is_present,
                'is_present_sdriver' => $is_present,
                'tractor_id'=> $query->tractor_id,
                'trailer_id'=>$query->trailer_id,
                'pdriver' => '',
                'sdriver' => '',
                'created_by' => Auth::user()->emp_id
            ]);

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Attendance is created', 'payload'=>'' ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update_attendance(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->haulage_att_id);
            $haulage_id = Crypt::decrypt($rq->haulage_id);
            $is_present = $rq->attendance=='absent'? 0:1;
            $query = (new TractorTrailerDriver)->find($rq);

            if(!$query){
                return response()->json(['status' => 'error','message'=>'Something went wrong. Try again later']);
            }

            TmsHaulageAttendance::where([['id'=>$id],['haulage_id'=>$haulage_id]])
            ->update([
                'is_present'.$rq->column => $is_present,
                $rq->column => $query->{$rq->column},
                'created_by' => Auth::user()->emp_id
            ]);

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Attendance is updated', 'payload'=>'']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    public function finalize_attendance(Request $rq)
    {
        try{
            DB::beginTransaction();

            $haulage_id = Crypt::decrypt($rq->haulage_id);
            TmsHaulageAttendance::where('haulage_id',$haulage_id)->update([
                'is_final'.$rq->column => $rq->is_final,
                'updated_by'=>Auth::user()->emp_id
            ]);

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Attendance is Finalize', 'payload'=>'']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    public function update_tractor_trailer_att(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->haulage_att_id);
            $haulage_id = Crypt::decrypt($rq->haulage_id);
            $tractor_id = Crypt::decrypt($rq->tractor_id);
            $trailer_id = Crypt::decrypt($rq->trailer_id);

            TmsHaulageAttendance::where([['id'=>$id],['haulage_id'=>$haulage_id]])
            ->update([
                'tractor_id' => $tractor_id,
                'trailer_id' => $trailer_id,
                'remarks' => $rq->remarks,
                'tractor_trailer_status' => $rq->tractor_trailer_status
            ]);

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Attendance is updated', 'payload'=>'']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }
}
