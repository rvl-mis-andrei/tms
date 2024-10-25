<?php

namespace App\Services\Dispatcher;

use App\Models\TmsClusterDriver;
use App\Models\TmsHaulageAttendance;
use App\Models\Tractor;
use App\Models\TractorTrailerDriver;
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

class TractorTrailerList
{
    public function datatable(Request $rq)
    {
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $filter_status = $rq->filter_status!='all' ? $rq->filter_status : false;

        $data = TractorTrailerDriver::with([
            'trailer',
            'tractor',
            'sdriver_emp.employee:id,fname,lname',
            'pdriver_emp.employee:id,fname,lname',
        ])
        ->where([['cluster_id', $cluster_id],['is_deleted',null]])
        ->when($filter_status, function ($q) use ($filter_status) {
            $q->where('status', $filter_status);
        })
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) {

            $last_updated_by = null;
            if($item->created_by != null){
                $last_updated_by = $item->created_by_emp->fullname();
            }elseif($item->updated_by !=null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }

            $item->count = $key + 1;
            $item->tractor_name = optional($item->haulage_att)->tractor->name ?? optional($item->tractor)->name ?? '';
            $item->tractor_plate_no = optional($item->haulage_att)->tractor->plate_no ?? optional($item->tractor)->plate_no ?? '';
            $item->trailer_name = optional($item->haulage_att)->trailer->name ?? optional($item->trailer)->name ?? '';
            $item->trailer_type = optional($item->haulage_att)->trailer->trailer_type->name ?? optional($item->trailer->trailer_type)->name ?? '';
            $item->tractor_trailer_status = $item->haulage_att? $item->haulage_att->tractor_trailer_status : $item->status;
            $item->pdriver_name = optional($item->pdriver_emp->employee)->fullname() ?? '';
            $item->sdriver_name = optional($item->sdriver_emp->employee)->fullname() ?? '';
            $item->tractor_trailer_status = $item->status ?? '--';
            $item->last_updated_by = $last_updated_by;
            $item->remarks = $item->remarks ?? '--';

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

    // public function info(Request $rq)
    // {
    //     try{
    //         $id = Crypt::decrypt($rq->id);
    //         $query = TractorTrailerDriver::findorFail($id);
    //         $driver = [];
    //         if($query->pdriver)
    //             $driver[] = [
    //                 'emp_no'=>$query->pdriver_emp->employee->emp_no,
    //                 'name'=>$query->pdriver_emp->employee->fullname(),
    //                 'column'=>'pdriver',
    //                 'license_no'=>$query->pdriver_emp->employee->license_no,
    //                 'mobile_no'=>$query->pdriver_emp->employee->mobile_no,
    //                 'status'=>config('value.cluster_driver_status.'.$query->pdriver_emp->status),
    //                 'remarks'=>$query->pdriver_emp->remarks,
    //             ];
    //         if($query->sdriver)
    //             $driver[] = [
    //                 'emp_no'=>$query->sdriver_emp->employee->emp_no,
    //                 'column'=>'sdriver',
    //                 'name'=>$query->sdriver_emp->employee->fullname(),
    //                 'license_no'=>$query->sdriver_emp->employee->license_no,
    //                 'mobile_no'=>$query->sdriver_emp->employee->mobile_no,
    //                 'status'=>config('value.cluster_driver_status.'.$query->sdriver_emp->status),
    //                 'remarks'=>$query->sdriver_emp->remarks,
    //             ];
    //         $payload = base64_encode(json_encode([
    //             'trailer' =>$query->trailer->name ?? null,
    //             'trailer_plate_no' =>$query->trailer->plate_no ?? null,
    //             'trailer_type' =>$query->trailer->trailer_type->name ?? null,
    //             'trailer_status' =>config('value.tractor_status.'.$query->trailer->status),
    //             'trailer_remarks' =>$query->trailer->remarks ?? null,
    //             'tractor' =>$query->tractor->name ?? null,
    //             'tractor_body_no' =>$query->tractor->body_no ?? null,
    //             'tractor_plate_no' =>$query->tractor->plate_no ?? null,
    //             'tractor_status' =>config('value.tractor_status.'.$query->tractor->status),
    //             'tractor_remarks' =>$query->tractor->remarks ?? null,
    //             'drivers' =>$driver,
    //             'status' =>config('value.tractor_trailer_status.'.$query->status),
    //             'remarks' =>$query->remarks,
    //             'last_updated_at' =>Carbon::parse($query->updated_at??$query->created_at)->format('F j, Y'),
    //             'last_updated_by'=>isset($query->updated_by)?$query->updated_by_emp->fullname() : $query->created_by_emp->fullname(),
    //         ]));
    //         return ['status'=>'success','message' =>'success', 'payload' => $payload];
    //     }catch(Exception $e){
    //         return response()->json([
    //             'status' => 400,
    //             // 'message' =>  'Something went wrong. try again later'
    //             'message' =>  $e->getMessage()
    //         ]);
    //     }
    // }

    // public function upsert(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();
    //         $cluster_id = Auth::user()->emp_cluster->cluster_id;
    //         $attr =  ['id'=>isset($rq->id) ?Crypt::decrypt($rq->id):null];
    //         if(!isset($rq->is_deleted)){
    //             $values =[
    //                 'cluster_id' => $cluster_id,
    //                 'tractor_id' => isset($rq->tractor)?Crypt::decrypt($rq->tractor):null,
    //                 'trailer_id' => isset($rq->trailer)?Crypt::decrypt($rq->trailer):null,
    //                 'pdriver' => isset($rq->pdriver)?Crypt::decrypt($rq->pdriver):null,
    //                 'sdriver' => isset($rq->sdriver)?Crypt::decrypt($rq->sdriver):null,
    //                 'remarks' => $rq->remarks,
    //                 'status' => $rq->is_active,
    //                 'created_by' => Auth::user()->emp_id,
    //             ];
    //             $message = "Tractor Trailer added successfully";
    //         }else{
    //             $values=[
    //                 'status' => $rq->is_active,
    //                 'is_deleted' => $rq->is_deleted,
    //                 'deleted_by' => Auth::user()->emp_id,
    //                 'deleted_at' => Carbon::now(),
    //             ];
    //             $message = "Tractor Trailer is deleted";
    //         }

    //         $query = TractorTrailerDriver::updateOrCreate($attr,$values);
    //         if (!$query->wasRecentlyCreated && !isset($rq->is_deleted)) {
    //             $query->updated_by = Auth::user()->emp_id;
    //             $query->save();
    //             $message = "Tractor Trailer details is updated";
    //             // WILL NOT UPDATE IF THERE IS A DELETE
    //         }
    //         DB::commit();
    //         return ['status'=>'success','message' =>$message];
    //     }catch(Exception $e){
    //         DB::rollBack();
    //         return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ], 500);
    //     }
    // }

    // public function remove(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();

    //         $id = Crypt::decrypt($rq->id);
    //         $column = $rq->column;
    //         $query = TractorTrailerDriver::find($id);
    //         $query->$column = null;
    //         $query->updated_by = Auth::user()->emp_id;
    //         $query->save();
    //         $page = (new DispatcherPage)->tractor_trailer_info($rq);
    //         DB::commit();
    //         return ['status'=>'success','message' =>'Removal is success','page'=>$page];
    //     }catch(Exception $e){
    //         DB::rollBack();
    //         return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ], 500);
    //     }
    // }

    // public function update_column(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();

    //         $id = Crypt::decrypt($rq->id);
    //         $column = $rq->column;
    //         $query = TractorTrailerDriver::find($id);
    //         $query->$column = Crypt::decrypt($rq->column_id);
    //         $query->updated_by = Auth::user()->emp_id;
    //         $query->save();
    //         $page = (new DispatcherPage)->tractor_trailer_info($rq);
    //         DB::commit();
    //         return ['status'=>'success','message' =>'Update is success','page'=>$page];
    //     }catch(Exception $e){
    //         DB::rollBack();
    //         return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ], 500);
    //     }
    // }

    // public function tractor_trailer_info(Request $rq)
    // {
    //     try{
    //         $id = Crypt::decrypt($rq->id);
    //         $haulage_id = Crypt::decrypt($rq->haulage_id);

    //         $query = TmsHaulageAttendance::where([['id',$id],['haulage_id',$haulage_id]])->first();
    //         $tractor_id = $query->tractor_id;
    //         $trailer_id = $query->trailer_id;
    //         $pdriver = $query->pdriver;
    //         $sdriver = $query->sdriver;

    //         $query = TractorTrailerDriver::where([
    //             ['tractor_id',$tractor_id],
    //             ['trailer_id',$trailer_id],
    //             ['pdriver',$pdriver],
    //             ['sdriver',$sdriver],
    //             ['is_deleted',null]])->first();

    //         $tractor_request = $rq->merge(['id' => Crypt::encrypt($tractor_id), 'type'=>'options']); // Clone and add the id
    //         $tractor_option = (new TractorOption)->list($tractor_request);

    //         $trailer_request = $rq->merge(['id' => Crypt::encrypt($trailer_id), 'type'=>'options']);
    //         $trailer_option = (new TrailerOption)->list($trailer_request);

    //         $pdriver_request = $rq->merge(['id' => Crypt::encrypt($pdriver), 'type'=>'options']);
    //         $pdriver_option = (new ClusterDriverOption)->list($pdriver_request);

    //         $sdriver_request = $rq->merge(['id' => Crypt::encrypt($sdriver), 'type'=>'options']);
    //         $sdriver_option = (new ClusterDriverOption)->list($sdriver_request);

    //         $payload = [
    //             'tractor_option' => $tractor_option,
    //             'trailer_option' => $trailer_option,
    //             'pdriver_option' => $pdriver_option,
    //             'sdriver_option' => $sdriver_option,
    //             'remarks' => $query->remarks,
    //             'status'=> $query->status,
    //         ];

    //         return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
    //     }catch(Exception $e){
    //         DB::rollback();
    //         return response()->json(['status'=>400,'message' =>$e->getMessage()]);

    //     }
    // }



    public function update_status(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $tractor_trailer = TractorTrailerDriver::find($id);

            if(!$tractor_trailer){
                return response()->json(['status'=>'error','message' =>'Record not found']);
            }

            if( ($rq->status ==3 || $rq->status ==4) && ($tractor_trailer->pdriver != null || $tractor_trailer->sdriver != null)){
                return response()->json(['status'=>'error','message' =>'There is a drivers assigned to this tractor']);
            }


            if( ($rq->status ==5 || $rq->status ==6) ){
                if($tractor_trailer->tractor_id == null || $tractor_trailer->trailer_id === null){
                    return response()->json(['status'=>'error','message' =>'There is no tractor or trailer assigned.']);
                }

                $isPendingDelivery = TmsHaulageAttendance::with('block_delivery')
                ->where([['tractor_id',$tractor_trailer->tractor_id],['trailer_id',$tractor_trailer->trailer_id]])->first();

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

    public function update_remarks(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $query = TractorTrailerDriver::find($id);
            if(!$query){
                return response()->json(['status'=>'error','message' =>'Record not found']);
            }

            $query->remarks =$rq->remarks;
            $query->updated_by =Auth::user()->emp_id;
            $query->updated_at =Carbon::now();

            $query->save();

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Remarks is updated']);
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
            $tractor_id = $rq->tractor?Crypt::decrypt($rq->tractor):null;
            $trailer_id = $rq->trailer?Crypt::decrypt($rq->trailer):null;
            $pdriver = $rq->pdriver?Crypt::decrypt($rq->pdriver):null;
            $sdriver = $rq->sdriver?Crypt::decrypt($rq->sdriver):null;

            $tractor_trailer = TractorTrailerDriver::find($id);
            $tractor_trailer_att = TmsHaulageAttendance::where([
                ['tractor_id',$tractor_trailer->tractor_id],
                ['trailer_id',$tractor_trailer->trailer_id],
                ['pdriver',$tractor_trailer->pdriver],
                ['sdriver',$tractor_trailer->sdriver],
                ['is_final',0]
            ])->first();

            //update old record of tractor
            if($tractor_id !=null && $tractor_trailer->tractor_id != $tractor_id){
                $update_tractor = TractorTrailerDriver::where([['tractor_id',$tractor_id],['is_deleted',null],['id','!=',$id]])->first();
                if($update_tractor){
                    $update_att_tractor = TmsHaulageAttendance::where([['tractor_id',$tractor_id],['is_final',0]])->first();
                    if($update_att_tractor){
                        if($update_att_tractor->trailer_id == null || $update_tractor->trailer_id == null){
                            return response()->json([
                                'status' => 'error',
                                'message'=>'Assign a trailer for this tractor "'.$update_att_tractor->tractor->name.'" before decoupling'
                            ]);
                        }
                        $update_att_tractor->tractor_id = null;
                        $update_att_tractor->updated_by =Auth::user()->emp_id;
                        $update_att_tractor->updated_at =Carbon::now();
                        $update_att_tractor->save();
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message'=>'Tractor details not found'
                        ]);
                    }

                    $tractor = Tractor::find($tractor_id);
                    if($tractor){
                        $tractor->status = 2;
                        $tractor->updated_by =Auth::user()->emp_id;
                        $tractor->save();
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message'=>'Tractor details not found'
                        ]);
                    }

                    $update_tractor->tractor_id = null;
                    $update_tractor->updated_by =Auth::user()->emp_id;
                    $update_tractor->updated_at =Carbon::now();
                    $update_tractor->save();
                }
            }elseif($tractor_id == null && $tractor_trailer->tractor_id !=null){
                $tractor = Tractor::find($tractor_trailer->tractor_id);
                if($tractor){
                    $tractor->status = 1;
                    $tractor->updated_by =Auth::user()->emp_id;
                    $tractor->save();
                }
            }

            //update old record of trailer
            if($trailer_id !=null && $tractor_trailer->trailer_id != $trailer_id){
                $update_trailer = TractorTrailerDriver::where([['trailer_id',$trailer_id],['is_deleted',null],['id','!=',$id]])->first();
                if($update_trailer){

                    $update_att_trailer = TmsHaulageAttendance::where([['trailer_id',$trailer_id],['is_final',0]])->first();
                    if($update_att_trailer){
                        if($update_att_trailer->tractor_id == null || $update_trailer->tractor_id == null){
                            return response()->json([
                                'status' => 'error',
                                'message'=>'Assign a tractor for this trailer "'.$update_att_trailer->trailer->name.'" before decoupling'
                            ]);
                        }
                        $update_att_trailer->trailer_id = null;
                        $update_att_trailer->updated_by =Auth::user()->emp_id;
                        $update_att_trailer->updated_at =Carbon::now();
                        $update_att_trailer->save();
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message'=>'Trailer details not found'
                        ]);
                    }

                    $trailer = Tractor::find($trailer_id);
                    if($trailer){
                        $trailer->status = 2;
                        $trailer->updated_by =Auth::user()->emp_id;
                        $trailer->save();
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message'=>'Trailer details not found'
                        ]);
                    }

                    $update_trailer->tractor_id = null;
                    $update_trailer->updated_by =Auth::user()->emp_id;
                    $update_trailer->updated_at =Carbon::now();
                    $update_trailer->save();
                }
            }elseif($trailer_id == null && $tractor_trailer->trailer_id !=null){
                $trailer = Tractor::find($tractor_trailer->trailer_id);
                if($trailer){
                    $trailer->status = 1;
                    $trailer->updated_by =Auth::user()->emp_id;
                    $trailer->save();
                }
            }

            //update old record of pdriver and sdriver
            if($pdriver!=null && $tractor_trailer->pdriver != $pdriver){
                $update_pdriver = TractorTrailerDriver::where([['pdriver',$pdriver],['is_deleted',null],['id','!=',$id]])->first();
                if($update_pdriver){

                    $update_att_pdriver = TmsHaulageAttendance::where([['pdriver',$pdriver],['is_final',0]])->first();
                    if($update_att_pdriver){
                        if($update_att_pdriver->sdriver == null || $update_pdriver->sdriver == null){
                            return response()->json([
                                'status' => 'error',
                                'message'=>'Assign Driver 2 for this tractor "'.$update_att_pdriver->tractor->name.'" before assigning a new driver'
                            ]);
                        }
                        $update_att_pdriver->pdriver = null;
                        $update_att_pdriver->updated_by =Auth::user()->emp_id;
                        $update_att_pdriver->updated_at =Carbon::now();
                        $update_att_pdriver->save();
                    }

                    $new_pdriver = TmsClusterDriver::find($pdriver);
                    if($new_pdriver){
                        $new_pdriver->status = 2;
                        $new_pdriver->updated_by =Auth::user()->emp_id;
                        $new_pdriver->updated_at =Carbon::now();
                        $new_pdriver->save();
                    }

                    $old_pdriver = TmsClusterDriver::find($tractor_trailer->pdriver);
                    if($old_pdriver){
                        $old_pdriver->status = 1;
                        $old_pdriver->updated_by =Auth::user()->emp_id;
                        $old_pdriver->updated_at =Carbon::now();
                        $old_pdriver->save();
                    }

                    $update_pdriver->pdriver = null;
                    $update_pdriver->updated_by =Auth::user()->emp_id;
                    $update_pdriver->updated_at =Carbon::now();
                    $update_pdriver->save();
                }
            }elseif($pdriver == null && $tractor_trailer->pdriver != null){
                $update_pdriver = TmsClusterDriver::find($tractor_trailer->pdriver);
                if($update_pdriver){
                    $update_pdriver->status = 1;
                    $update_pdriver->updated_by =Auth::user()->emp_id;
                    $update_pdriver->updated_at =Carbon::now();
                    $update_pdriver->save();
                }
            }

            if($sdriver!=null && $tractor_trailer->sdriver != $sdriver){
                $update_sdriver = TractorTrailerDriver::where([['sdriver',$sdriver],['is_deleted',null],['id','!=',$id]])->first();
                if($update_sdriver){

                    $update_att_sdriver = TmsHaulageAttendance::where([['sdriver',$sdriver],['is_final',0]])->first();
                    if($update_att_sdriver){
                        if($update_att_sdriver->sdriver == null || $update_sdriver->sdriver == null){
                            return response()->json([
                                'status' => 'error',
                                'message'=>'Assign a Driver 1 for this tractor "'.$update_att_sdriver->tractor->name.'" before assigning a new driver'
                            ]);
                        }
                        $update_att_sdriver->sdriver = null;
                        $update_att_sdriver->updated_by =Auth::user()->emp_id;
                        $update_att_sdriver->updated_at =Carbon::now();
                        $update_att_sdriver->save();
                    }

                    $new_sdriver = TmsClusterDriver::find($sdriver);
                    if($new_sdriver){
                        $new_sdriver->status = 2;
                        $new_sdriver->updated_by =Auth::user()->emp_id;
                        $new_sdriver->updated_at =Carbon::now();
                        $new_sdriver->save();
                    }

                    $old_sdriver = TmsClusterDriver::find($tractor_trailer->sdriver);
                    if($old_sdriver){
                        $old_sdriver->status = 1;
                        $old_sdriver->updated_by =Auth::user()->emp_id;
                        $old_sdriver->updated_at =Carbon::now();
                        $old_sdriver->save();
                    }

                    $update_sdriver->sdriver = null;
                    $update_sdriver->updated_by =Auth::user()->emp_id;
                    $update_sdriver->updated_at =Carbon::now();
                    $update_sdriver->save();
                }
            }elseif($sdriver == null && $tractor_trailer->sdriver != null){
                $update_sdriver = TmsClusterDriver::find($tractor_trailer->sdriver);
                if($update_sdriver){
                    $update_sdriver->status = 1;
                    $update_sdriver->updated_by =Auth::user()->emp_id;
                    $update_sdriver->updated_at =Carbon::now();
                    $update_sdriver->save();
                }
            }


            //assign new tractor trailer
            $tractor_trailer->tractor_id = $tractor_id;
            $tractor_trailer->trailer_id =$trailer_id;
            $tractor_trailer->pdriver = $pdriver;
            $tractor_trailer->sdriver = $sdriver;
            $tractor_trailer->updated_by =Auth::user()->emp_id;
            $tractor_trailer->updated_at =Carbon::now();
            $tractor_trailer->save();

            if($tractor_trailer_att){
                $tractor_trailer_att->tractor_id = $tractor_id;
                $tractor_trailer_att->trailer_id =$trailer_id;
                $tractor_trailer_att->pdriver = $pdriver;
                $tractor_trailer_att->sdriver = $sdriver;
                $tractor_trailer_att->updated_by =Auth::user()->emp_id;
                $tractor_trailer_att->updated_at =Carbon::now();
                $tractor_trailer_att->save();
            }

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Tractor Trailer is updated', 'payload'=>'']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    public function info (Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = TractorTrailerDriver::find($id);

            $tractor_id = $query->tractor_id;
            $trailer_id = $query->trailer_id;
            $pdriver = $query->pdriver;
            $sdriver = $query->sdriver;

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
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }
    }

    // public function delete_tractor_trailer(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();

    //         $attendance_id = Crypt::decrypt($rq->attendance_id);
    //         $haulage_id = Crypt::decrypt($rq->haulage_id);

    //         $user_id = Auth::user()->emp_id;
    //         $deleted_at = Carbon::now();

    //         $query = TmsHaulageAttendance::where([['id',$attendance_id],['haulage_id',$haulage_id],['is_deleted',null]])->first();
    //         $query->is_deleted = 1;
    //         $query->deleted_by = $user_id;
    //         $query->deleted_at = $deleted_at;
    //         $query->save();

    //         TractorTrailerDriver::where([
    //             ['tractor_id',$query->tractor_id],
    //             ['trailer_id',$query->trailer_id],
    //             ['pdriver',$query->pdriver],
    //             ['sdriver',$query->sdriver],
    //             ['is_deleted',null]])
    //         ->update([
    //             'is_deleted'=>1,
    //             'deleted_by'=>$user_id,
    //             'deleted_at'=>$deleted_at,
    //         ]);

    //         DB::commit();
    //         return response()->json(['status' => 'success','message'=>'Tractor Trailer is Deleted', 'payload'=>'']);
    //     }catch(Exception $e){
    //         DB::rollback();
    //         return response()->json(['status'=>400,'message' =>$e->getMessage()]);

    //     }
    // }

    // public function check_tractor_status(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();

    //         $haulage_id = Crypt::decrypt($rq->haulage_id);
    //         $tractor_id = Crypt::decrypt($rq->tractor);

    //         //Check Tractor Status if not Available
    //         $tractor_trailer = TractorTrailerDriver::where([['tractor_id',$tractor_id],['is_deleted',null]])->first();
    //         if($tractor_trailer && in_array($tractor_trailer->status,[1,3,7])){
    //             $tractor_status = config("value.tractor_status.$tractor_trailer->status");
    //             return response()->json([
    //                 'valid' => false,
    //                 'message' => 'This tractor is currently "'.$tractor_status[0].'"'
    //             ]);
    //         }

    //         //Check Tractor if use in another haulage
    //         $haulage_attendance = TmsHaulageAttendance::where([['tractor_id',$tractor_id],['haulage_id','!=',$haulage_id],['is_final',0]])->latest()->first();
    //         if($haulage_attendance){
    //             if(in_array($haulage_attendance->tractor_trailer_status,[1,3,7])){
    //                 $tractor_status = config("value.tractor_status.$haulage_attendance->tractor_trailer_status");
    //                 return response()->json([
    //                     'valid' => false,
    //                     'message' => 'This tractor is currently "'.$tractor_status[0].'"'
    //                 ]);
    //             }
    //         }

    //         //Check tractor delivery status in another haulage
    //         $haulage_delivery = TmsHaulageBlockDelivery::where([['haulage_id','!=',$haulage_id]])
    //         ->whereHas('attendance',function($q) use($tractor_id){
    //             $q->where('tractor_id',$tractor_id);
    //         })
    //         ->latest()->first();
    //         if($haulage_delivery){
    //             if($haulage_delivery->status == 0){
    //                 $tractor_status = config("value.delivery_status.$haulage_delivery->status");
    //                 return response()->json([
    //                     'valid' => false,
    //                     'message' => 'This tractor is currently "'.$tractor_status[0].'"'
    //                 ]);
    //             }
    //         }

    //         return response()->json([
    //             'valid' => true,
    //             'message' => 'This tractor is available.'
    //         ]);
    //     }catch(Exception $e){
    //         DB::rollback();
    //         return response()->json(['status'=>400,'message' =>$e->getMessage()]);

    //     }
    // }

    // public function check_trailer_status(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();

    //         $haulage_id = Crypt::decrypt($rq->haulage_id);
    //         $trailer_id = Crypt::decrypt($rq->trailer);

    //         //Check Trailer Status if not Available
    //         $tractor_trailer = TractorTrailerDriver::where([['trailer_id',$trailer_id],['is_deleted',null]])->first();
    //         if($tractor_trailer && in_array($tractor_trailer->status,[1,3,6])){
    //             $tractor_status = config("value.tractor_status.$tractor_trailer->status");
    //             return response()->json([
    //                 'valid' => false,
    //                 'message' => 'This trailer status is currently "'.$tractor_status[0].'"'
    //             ]);
    //         }

    //         //Check Trailer if use in another haulage
    //         $haulage_attendance = TmsHaulageAttendance::where([['trailer_id',$trailer_id],['haulage_id','!=',$haulage_id],['is_final',0]])->latest()->first();
    //         if($haulage_attendance){
    //             if(in_array($haulage_attendance->tractor_trailer_status,[1,3,6])){
    //                 $tractor_status = config("value.tractor_status.$haulage_attendance->tractor_trailer_status");
    //                 return response()->json([
    //                     'valid' => false,
    //                     'message' => 'This trailer status is currently "'.$tractor_status[0].'"'
    //                 ]);
    //             }
    //         }

    //         //Check trailer delivery status in another haulage
    //         $haulage_delivery = TmsHaulageBlockDelivery::where([['haulage_id','!=',$haulage_id]])
    //         ->whereHas('attendance',function($q) use($trailer_id){
    //             $q->where('trailer_id',$trailer_id);
    //         })
    //         ->latest()->first();
    //         if($haulage_delivery){
    //             if($haulage_delivery->status == 0){
    //                 $tractor_status = config("value.delivery_status.$haulage_delivery->status");
    //                 return response()->json([
    //                     'valid' => false,
    //                     'message' => 'This trailer is currently "'.$tractor_status[0].'"'
    //                 ]);
    //             }
    //         }

    //         return response()->json([
    //             'valid' => true,
    //             'message' => 'This trailer is available.'
    //         ]);
    //     }catch(Exception $e){
    //         DB::rollback();
    //         return response()->json(['status'=>400,'message' =>$e->getMessage()]);

    //     }
    // }

}
