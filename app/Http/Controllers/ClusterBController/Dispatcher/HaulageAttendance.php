<?php

namespace App\Http\Controllers\ClusterBController\Dispatcher;

use App\Http\Controllers\Controller;
use App\Models\TmsHaulage;
use App\Models\TmsHaulageAttendance;
use App\Models\TmsHaulageBlockDelivery;
use App\Models\Tractor;
use App\Models\TractorTrailerDriver;
use App\Models\Trailer;
use App\Services\ClusterDriverOption;
use App\Services\DTServerSide;
use App\Services\TractorOption;
use App\Services\TrailerOption;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class HaulageAttendance extends Controller
{
    public function dt(Request $rq)
    {
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $haulage_id = Crypt::decrypt($rq->id);

        $filter_status = $rq->filter_status!='all' ? $rq->filter_status : false;
        $filter_attendance = $rq->filter_attendance!='all' ? $rq->filter_attendance : false;

        $data = TmsHaulageAttendance::with([
            'trailer',
            'tractor',
            'sdriver_emp.employee:id,fname,lname',
            'pdriver_emp.employee:id,fname,lname',
        ])
        ->when($filter_status, function ($q) use ($filter_status) {
            $q->where('tractor_trailer_status', $filter_status);
        })
        ->when($filter_attendance, function ($q) use ($filter_attendance) {
            $q->where(function($query) use ($filter_attendance) {
                $filter_attendance = $filter_attendance =='present'?1:0;
                $query->where('is_present_pdriver', $filter_attendance)
                      ->orWhere('is_present_sdriver', $filter_attendance);
            });
        })
        ->where([['cluster_id', $cluster_id],['is_deleted',null],['haulage_id', $haulage_id]])
        ->orderBy('id', 'ASC')
        ->get();

        $pendingAttendance = TmsHaulageAttendance::where([
            ['cluster_id',$cluster_id],
            ['haulage_id','!=',$haulage_id],
            ['is_final',0]
        ])->exists();

        $data->transform(function ($item, $key) use($pendingAttendance) {
            $item->count = $key + 1;

            $item->tractor_name = $item->tractor_id ? $item->tractor->name : '';
            $item->tractor_plate_no = $item->tractor_id ? $item->tractor->plate_no : '';
            $item->trailer_name = $item->trailer_id ? $item->trailer->name : '';
            $item->trailer_type = $item->trailer_id ? $item->trailer->trailer_type->name :'';

            $item->pdriver_name = $item->pdriver ?  $item->pdriver_emp->employee->fullname(): '';
            $item->sdriver_name = $item->sdriver ?  $item->sdriver_emp->employee->fullname(): '';
            $item->pdriver_att = $item->is_present_pdriver ?? '';
            $item->sdriver_att = $item->is_present_sdriver ?? '';
            $item->remarks = $item->remarks ?? '';

            $item->url = 'update_attendance';
            $item->is_started = !$pendingAttendance ?? false;
            $item->is_final = $item->is_final?? false;

            $item->encrypted_id = Crypt::encrypt($item->id);

            return $item;
        });

        $table = new DTServerSide($rq, $data);
        $table->renderTable();

        return response()->json([
            'draw' => $table->getDraw(),
            'recordsTotal' => $table->getRecordsTotal(),
            'recordsFiltered' =>  $table->getRecordsFiltered(),
            'data' => $table->getRows()
        ]);

    }

    public function start_attendance(Request $rq)
    {
        try{
            DB::beginTransaction();

            $is_present = 1;
            $haulage_id = Crypt::decrypt($rq->id);
            $cluster_id = Auth::user()->emp_cluster->cluster_id;

            $isPendingAttendance = TmsHaulageAttendance::where([['cluster_id', $cluster_id],['is_final',0]])->exists();
            if ($isPendingAttendance) {
                return response()->json(['status' => 'error', 'message' => 'There is an ongoing attendance on another Dispatch.']);
            }

            $query = TractorTrailerDriver::where([['cluster_id', $cluster_id],['is_deleted',null]])->get();
            if ($query->isEmpty()) {
                return response()->json(['status' => 'error', 'message' => 'No driver found on this cluster.']);
            }

            $array = [];
            foreach($query as $data){
                $array[]=[
                    'cluster_id'=> $cluster_id,
                    'tractor_trailer_id'=>$data->id,
                    'haulage_id'=>$haulage_id,
                    'is_present_pdriver' => $is_present,
                    'is_present_sdriver' => $is_present,
                    'tractor_id'=> $data->tractor_id,
                    'trailer_id'=>$data->trailer_id,
                    'pdriver' => $data->pdriver,
                    'sdriver' => $data->sdriver,
                    'created_by' => Auth::user()->emp_id
                ];
            }


            TmsHaulageAttendance::insert($array);
            DB::commit();
            return response()->json(['status' => 'success','message'=>'Attendance is created']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function finish_attendance(Request $rq)
    {
        try{
            DB::beginTransaction();

            $haulage_id = Crypt::decrypt($rq->id);
            TmsHaulageAttendance::where([['haulage_id',$haulage_id],['is_final',0]])->update([
                'is_final'.$rq->column => $rq->is_final,
                'updated_by'=>Auth::user()->emp_id
            ]);

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Attendance is Finalize']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    public function update_driver_attendance(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $haulage_id = Crypt::decrypt($rq->haulage_id);
            $is_present = $rq->attendance=='absent'? 0:1;
            $query = TmsHaulageAttendance::where([['id',$id],['haulage_id',$haulage_id]])->first();

            if($query->{$rq->column} ==null && $is_present !== 0){
                return response()->json(['status'=>'error','message' =>'There is no driver assigned to this record']);
            }

            $query->{'is_present_'.$rq->column} =$is_present;
            $query->updated_by =Auth::user()->emp_id;
            $query->save();

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Attendance is updated', 'payload'=>'']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    public function tractor_trailer_info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $haulage_id = Crypt::decrypt($rq->haulage_id);

            $query = TmsHaulageAttendance::where([['id',$id],['haulage_id',$haulage_id]])->first();
            $tractor_id = $query->tractor_id;
            $trailer_id = $query->trailer_id;
            $pdriver = $query->pdriver;
            $sdriver = $query->sdriver;

            $query = TractorTrailerDriver::where([
                ['tractor_id',$tractor_id],
                ['trailer_id',$trailer_id],
                ['pdriver',$pdriver],
                ['sdriver',$sdriver],
                ['is_deleted',null]])->first();

            $tractor_request = $rq->merge(['id' => Crypt::encrypt($tractor_id), 'type'=>'options']); // Clone and add the id
            $tractor_option = (new TractorOption)->list($tractor_request);

            $trailer_request = $rq->merge(['id' => Crypt::encrypt($trailer_id), 'type'=>'options']);
            $trailer_option = (new TrailerOption)->list($trailer_request);

            $pdriver_request = $rq->merge(['id' => Crypt::encrypt($pdriver), 'type'=>'options']);
            $pdriver_option = (new ClusterDriverOption)->list($pdriver_request);

            $sdriver_request = $rq->merge(['id' => Crypt::encrypt($sdriver), 'type'=>'options']);
            $sdriver_option = (new ClusterDriverOption)->list($sdriver_request);

            $payload = [
                'tractor_option' => $tractor_option,
                'trailer_option' => $trailer_option,
                'pdriver_option' => $pdriver_option,
                'sdriver_option' => $sdriver_option,
                'remarks' => $query->remarks,
                'status'=> $query->status,
            ];

            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    public function update_tractor_trailer(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->attendance_id);
            $haulage_id = Crypt::decrypt($rq->id);
            $tractor_id = $rq->tractor?Crypt::decrypt($rq->tractor):null;
            $trailer_id = $rq->trailer?Crypt::decrypt($rq->trailer):null;
            $pdriver = $rq->pdriver?Crypt::decrypt($rq->pdriver):null;
            $sdriver = $rq->sdriver?Crypt::decrypt($rq->sdriver):null;

            $query = TmsHaulageAttendance::where([['id',$id],['haulage_id',$haulage_id]])->first();
            $tractor_trailer = TractorTrailerDriver::where([
                ['tractor_id',$query->tractor_id],
                ['trailer_id',$query->trailer_id],
                ['pdriver',$query->pdriver],
                ['sdriver',$query->sdriver],
                ['is_deleted',null]])->first();

            //update old record of trailer trailer
            if($tractor_id !=null && $query->tractor_id != $tractor_id){
                $update_tractor = TractorTrailerDriver::where([['tractor_id',$tractor_id],['is_deleted',null]])->first();
                if($update_tractor){
                    $update_att_tractor = TmsHaulageAttendance::where([['tractor_id',$tractor_id],['haulage_id',$haulage_id]])->first();
                    if($update_att_tractor->trailer_id == null || $update_tractor->trailer_id == null){
                        return response()->json([
                            'status' => 'error',
                            'message'=>'Assign a trailer for this tractor "'.$update_att_tractor->tractor->name.'" before decoupling'
                        ]);
                    }
                    $update_att_tractor->tractor_id = null;
                    $update_att_tractor->save();

                    $update_tractor->tractor_id = null;
                    $update_tractor->save();
                }
            }
            if($trailer_id !=null && $query->trailer_id != $trailer_id){
                $update_trailer = TractorTrailerDriver::where([['trailer_id',$trailer_id],['is_deleted',null]])->first();
                if($update_trailer){
                    $update_att_trailer = TmsHaulageAttendance::where([['trailer_id',$trailer_id],['haulage_id',$haulage_id]])->first();
                    if($update_att_trailer->tractor_id == null || $update_trailer->tractor_id == null){
                        return response()->json([
                            'status' => 'error',
                            'message'=>'Assign a tractor for this trailer "'.$update_att_trailer->trailer->name.'" before decoupling'
                        ]);
                    }
                    $update_att_trailer->trailer_id = null;
                    $update_att_trailer->save();

                    $update_trailer->tractor_id = null;
                    $update_trailer->save();
                }
            }

            //update old record of pdriver and sdriver
            if($pdriver!=null && $query->pdriver != $pdriver){
                $update_pdriver = TractorTrailerDriver::where([['pdriver',$pdriver],['is_deleted',null]])->first();
                if($update_pdriver){
                    $update_att_pdriver = TmsHaulageAttendance::where([['pdriver',$pdriver],['haulage_id',$haulage_id]])->first();
                    if($update_att_pdriver->sdriver == null || $update_pdriver->sdriver == null){
                        return response()->json([
                            'status' => 'error',
                            'message'=>'Assign Driver 2 for this tractor "'.$update_att_pdriver->tractor->name.'" before assigning a new driver'
                        ]);
                    }
                    $update_att_pdriver->pdriver = null;
                    $update_att_pdriver->save();

                    $update_pdriver->pdriver = null;
                    $update_pdriver->save();
                }
            }
            if($sdriver!=null && $query->sdriver != $sdriver){
                $update_sdriver = TractorTrailerDriver::where([['sdriver',$sdriver],['is_deleted',null]])->first();
                if($update_sdriver){
                    $update_att_sdriver = TmsHaulageAttendance::where([['sdriver',$sdriver],['haulage_id',$haulage_id]])->first();
                    if($update_att_sdriver->sdriver == null || $update_sdriver->sdriver == null){
                        return response()->json([
                            'status' => 'error',
                            'message'=>'Assign a Driver 1 for this tractor "'.$update_att_sdriver->tractor->name.'" before assigning a new driver'
                        ]);
                    }
                    $update_att_sdriver->sdriver = null;
                    $update_att_sdriver->save();

                    $update_sdriver->sdriver = null;
                    $update_sdriver->save();
                }
            }

            //assign new tractor trailer
            $query->tractor_id = $tractor_id;
            $query->trailer_id =$trailer_id;
            $query->pdriver = $pdriver;
            $query->sdriver = $sdriver;
            $query->tractor_trailer_status = $rq->is_active;
            $query->updated_by =Auth::user()->emp_id;
            $query->updated_at =Carbon::now();
            $query->save();

            $tractor_trailer->tractor_id = $tractor_id;
            $tractor_trailer->trailer_id =$trailer_id;
            $tractor_trailer->pdriver = $pdriver;
            $tractor_trailer->sdriver = $sdriver;
            $tractor_trailer->updated_by =Auth::user()->emp_id;
            $tractor_trailer->updated_at =Carbon::now();

            $tractor_trailer->save();

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Tractor Trailer is updated', 'payload'=>'']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    public function update_tractor_trailer_remarks(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $haulage_id = Crypt::decrypt($rq->haulage_id);

            $query = TmsHaulageAttendance::where([['id',$id],['haulage_id',$haulage_id]])->first();
            if(!$query){
                return response()->json(['status'=>'error','message' =>'Record not found']);
            }

            $query->remarks =$rq->remarks;
            $query->updated_by =Auth::user()->emp_id;
            $query->save();

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Remarks is updated']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    public function update_tractor_trailer_status(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $haulage_id = Crypt::decrypt($rq->haulage_id);

            $query = TmsHaulageAttendance::where([['id',$id],['haulage_id',$haulage_id]])->first();
            $tractor_trailer = TractorTrailerDriver::where([
                ['tractor_id',$query->tractor_id],
                ['trailer_id',$query->trailer_id],
                ['pdriver',$query->pdriver],
                ['sdriver',$query->sdriver],
                ['is_deleted',null]])->first();

            if(!$query || !$tractor_trailer){
                return response()->json(['status'=>'error','message' =>'Record not found']);
            }

            if( ($rq->status ==3 || $rq->status ==4) && ($query->pdriver != null || $query->sdriver != null)){
                return response()->json(['status'=>'error','message' =>'There is a drivers assigned to this tractor']);
            }


            if( ($rq->status ==5 || $rq->status ==6) ){
                if($tractor_trailer->tractor_id == null || $tractor_trailer->trailer_id === null){
                    return response()->json(['status'=>'error','message' =>'There is no tractor or trailer assigned.']);
                }

                $isPendingDelivery = TmsHaulageAttendance::with('block_delivery')
                ->where([['tractor_id',$query->tractor_id],['trailer_id',$query->trailer_id],['id','!=',$id]])->first();

                if($isPendingDelivery){
                    if($isPendingDelivery->block_delivery->status === 0){
                        return response()->json(['status'=>'error','message' =>'There is a on-going delivery for this tractor trailer.']);
                    }
                }

            }

            if( $rq->status ==7  && $tractor_trailer->trailer_id == null){
                return response()->json(['status'=>'error','message' =>'There is no tractor assigned.']);
            }

            if( $rq->status ==8 && $tractor_trailer->tractor_id == null){
                return response()->json(['status'=>'error','message' =>'There is no trailer assigned.']);
            }

            $query->tractor_trailer_status =$rq->status;
            $query->updated_by =Auth::user()->emp_id;
            $query->updated_at =Carbon::now();
            $query->save();

            $tractor_trailer->status =$rq->status;
            $tractor_trailer->updated_by =Auth::user()->emp_id;
            $tractor_trailer->updated_at =Carbon::now();
            $tractor_trailer->save();

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Status is updated']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    public function delete_tractor_trailer(Request $rq)
    {
        try{
            DB::beginTransaction();

            $attendance_id = Crypt::decrypt($rq->attendance_id);
            $haulage_id = Crypt::decrypt($rq->haulage_id);

            $user_id = Auth::user()->emp_id;
            $deleted_at = Carbon::now();

            $query = TmsHaulageAttendance::where([['id',$attendance_id],['haulage_id',$haulage_id],['is_deleted',null]])->first();
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = $deleted_at;
            $query->save();

            TractorTrailerDriver::where([
                ['tractor_id',$query->tractor_id],
                ['trailer_id',$query->trailer_id],
                ['pdriver',$query->pdriver],
                ['sdriver',$query->sdriver],
                ['is_deleted',null]])
            ->update([
                'is_deleted'=>1,
                'deleted_by'=>$user_id,
                'deleted_at'=>$deleted_at,
            ]);

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Tractor Trailer is Deleted', 'payload'=>'']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    public function check_tractor_status(Request $rq)
    {
        try{
            DB::beginTransaction();

            $haulage_id = Crypt::decrypt($rq->haulage_id);
            $tractor_id = Crypt::decrypt($rq->tractor);

            //Check Tractor Status if not Available
            $tractor_trailer = TractorTrailerDriver::where([['tractor_id',$tractor_id],['is_deleted',null]])->first();
            if($tractor_trailer && in_array($tractor_trailer->status,[1,3,7])){
                $tractor_status = config("value.tractor_status.$tractor_trailer->status");
                return response()->json([
                    'valid' => false,
                    'message' => 'This tractor is currently "'.$tractor_status[0].'"'
                ]);
            }

            //Check Tractor if use in another haulage
            $haulage_attendance = TmsHaulageAttendance::where([['tractor_id',$tractor_id],['haulage_id','!=',$haulage_id],['is_final',0]])->latest()->first();
            if($haulage_attendance){
                if(in_array($haulage_attendance->tractor_trailer_status,[1,3,7])){
                    $tractor_status = config("value.tractor_status.$haulage_attendance->tractor_trailer_status");
                    return response()->json([
                        'valid' => false,
                        'message' => 'This tractor is currently "'.$tractor_status[0].'"'
                    ]);
                }
            }

            //Check tractor delivery status in another haulage
            $haulage_delivery = TmsHaulageBlockDelivery::where([['haulage_id','!=',$haulage_id]])
            ->whereHas('attendance',function($q) use($tractor_id){
                $q->where('tractor_id',$tractor_id);
            })
            ->latest()->first();
            if($haulage_delivery){
                if($haulage_delivery->status == 0){
                    $tractor_status = config("value.delivery_status.$haulage_delivery->status");
                    return response()->json([
                        'valid' => false,
                        'message' => 'This tractor is currently "'.$tractor_status[0].'"'
                    ]);
                }
            }

            return response()->json([
                'valid' => true,
                'message' => 'This tractor is available.'
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    public function check_trailer_status(Request $rq)
    {
        try{
            DB::beginTransaction();

            $haulage_id = Crypt::decrypt($rq->haulage_id);
            $trailer_id = Crypt::decrypt($rq->trailer);

            //Check Trailer Status if not Available
            $tractor_trailer = TractorTrailerDriver::where([['trailer_id',$trailer_id],['is_deleted',null]])->first();
            if($tractor_trailer && in_array($tractor_trailer->status,[1,3,6])){
                $tractor_status = config("value.tractor_status.$tractor_trailer->status");
                return response()->json([
                    'valid' => false,
                    'message' => 'This trailer status is currently "'.$tractor_status[0].'"'
                ]);
            }

            //Check Trailer if use in another haulage
            $haulage_attendance = TmsHaulageAttendance::where([['trailer_id',$trailer_id],['haulage_id','!=',$haulage_id],['is_final',0]])->latest()->first();
            if($haulage_attendance){
                if(in_array($haulage_attendance->tractor_trailer_status,[1,3,6])){
                    $tractor_status = config("value.tractor_status.$haulage_attendance->tractor_trailer_status");
                    return response()->json([
                        'valid' => false,
                        'message' => 'This trailer status is currently "'.$tractor_status[0].'"'
                    ]);
                }
            }

            //Check trailer delivery status in another haulage
            $haulage_delivery = TmsHaulageBlockDelivery::where([['haulage_id','!=',$haulage_id]])
            ->whereHas('attendance',function($q) use($trailer_id){
                $q->where('trailer_id',$trailer_id);
            })
            ->latest()->first();
            if($haulage_delivery){
                if($haulage_delivery->status == 0){
                    $tractor_status = config("value.delivery_status.$haulage_delivery->status");
                    return response()->json([
                        'valid' => false,
                        'message' => 'This trailer is currently "'.$tractor_status[0].'"'
                    ]);
                }
            }

            return response()->json([
                'valid' => true,
                'message' => 'This trailer is available.'
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

}
