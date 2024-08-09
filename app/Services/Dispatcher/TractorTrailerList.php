<?php

namespace App\Services\Dispatcher;

use App\Models\TractorTrailerDriver;
use App\Services\DTServerSide;
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
        $status = $rq->status;
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $data = TractorTrailerDriver::when($status!="all", function ($q) use ($status) {
            return $q->where('status',$status);
        })->where(function ($query) {
            $query->where('is_deleted','!=',1)->orWhereNull('is_deleted');
        })->where('cluster_id',$cluster_id)->get();
        $data->transform(function ($item,$key){
            $item->count = $key+1;
            $item->status = config('value.is_active.'.$item->status);

            $item->tractor_plate_no = $item->tractor->plate_no;
            $item->tractor_status = config('value.tractor_status.'.$item->tractor->status);

            $item->trailer_type = $item->trailer->trailer_type->name;
            $item->trailer_status = config('value.trailer_status.'.$item->trailer->status);

            $item->sdriver_emp = $item->sdriver ? $item->sdriver_emp->employee->fullname():null;
            $item->pdriver_emp = $item->pdriver ?$item->pdriver_emp->employee->fullname():null;
            $item->tractor = $item->tractor->name;
            $item->trailer = $item->trailer->name;
            $item->remarks = $item->remarks ?? '--';
            $item->encrypt_id = Crypt::encrypt($item->id);
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

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = TractorTrailerDriver::findorFail($id);
            $driver = [];
            if($query->pdriver)
                $driver[] = [
                    'emp_no'=>$query->pdriver_emp->employee->emp_no,
                    'name'=>$query->pdriver_emp->employee->fullname(),
                    'column'=>'pdriver',
                    'license_no'=>$query->pdriver_emp->employee->license_no,
                    'mobile_no'=>$query->pdriver_emp->employee->mobile_no,
                    'status'=>config('value.cluster_driver_status.'.$query->pdriver_emp->status),
                    'remarks'=>$query->pdriver_emp->remarks,
                ];
            if($query->sdriver)
                $driver[] = [
                    'emp_no'=>$query->sdriver_emp->employee->emp_no,
                    'column'=>'sdriver',
                    'name'=>$query->sdriver_emp->employee->fullname(),
                    'license_no'=>$query->sdriver_emp->employee->license_no,
                    'mobile_no'=>$query->sdriver_emp->employee->mobile_no,
                    'status'=>config('value.cluster_driver_status.'.$query->sdriver_emp->status),
                    'remarks'=>$query->sdriver_emp->remarks,
                ];
            $payload = base64_encode(json_encode([
                'trailer' =>$query->trailer->name ?? null,
                'trailer_plate_no' =>$query->trailer->plate_no ?? null,
                'trailer_type' =>$query->trailer->trailer_type->name ?? null,
                'trailer_status' =>config('value.tractor_status.'.$query->trailer->status),
                'trailer_remarks' =>$query->trailer->remarks ?? null,
                'tractor' =>$query->tractor->name ?? null,
                'tractor_body_no' =>$query->tractor->body_no ?? null,
                'tractor_plate_no' =>$query->tractor->plate_no ?? null,
                'tractor_status' =>config('value.tractor_status.'.$query->tractor->status),
                'tractor_remarks' =>$query->tractor->remarks ?? null,
                'drivers' =>$driver,
                'status' =>config('value.tractor_trailer_status.'.$query->status),
                'remarks' =>$query->remarks,
                'last_updated_at' =>Carbon::parse($query->updated_at??$query->created_at)->format('F j, Y'),
                'last_updated_by'=>isset($query->updated_by)?$query->updated_by_emp->fullname() : $query->created_by_emp->fullname(),
            ]));
            return ['status'=>'success','message' =>'success', 'payload' => $payload];
        }catch(Exception $e){
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function upsert(Request $rq)
    {
        try{
            DB::beginTransaction();
            $cluster_id = Auth::user()->emp_cluster->cluster_id;
            $attr =  ['id'=>isset($rq->id) ?Crypt::decrypt($rq->id):null];
            if(!isset($rq->is_deleted)){
                $values =[
                    'cluster_id' => $cluster_id,
                    'tractor_id' => isset($rq->tractor)?Crypt::decrypt($rq->tractor):null,
                    'trailer_id' => isset($rq->trailer)?Crypt::decrypt($rq->trailer):null,
                    'pdriver' => isset($rq->pdriver)?Crypt::decrypt($rq->pdriver):null,
                    'sdriver' => isset($rq->sdriver)?Crypt::decrypt($rq->sdriver):null,
                    'remarks' => $rq->remarks,
                    'status' => $rq->is_active,
                    'created_by' => Auth::user()->emp_id,
                ];
                $message = "Tractor Trailer added successfully";
            }else{
                $values=[
                    'status' => $rq->is_active,
                    'is_deleted' => $rq->is_deleted,
                    'deleted_by' => Auth::user()->emp_id,
                    'deleted_at' => Carbon::now(),
                ];
                $message = "Tractor Trailer is deleted";
            }

            $query = TractorTrailerDriver::updateOrCreate($attr,$values);
            if (!$query->wasRecentlyCreated && !isset($rq->is_deleted)) {
                $query->updated_by = Auth::user()->emp_id;
                $query->save();
                $message = "Tractor Trailer details is updated";
                // WILL NOT UPDATE IF THERE IS A DELETE
            }
            DB::commit();
            return ['status'=>'success','message' =>$message];
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ], 500);
        }
    }

    public function remove(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $column = $rq->column;
            $query = TractorTrailerDriver::find($id);
            $query->$column = null;
            $query->updated_by = Auth::user()->emp_id;
            $query->save();
            $page = (new DispatcherPage)->tractor_trailer_info($rq);
            DB::commit();
            return ['status'=>'success','message' =>'Removal is success','page'=>$page];
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ], 500);
        }
    }

    public function update_column(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $column = $rq->column;
            $query = TractorTrailerDriver::find($id);
            $query->$column = Crypt::decrypt($rq->column_id);
            $query->updated_by = Auth::user()->emp_id;
            $query->save();
            $page = (new DispatcherPage)->tractor_trailer_info($rq);
            DB::commit();
            return ['status'=>'success','message' =>'Update is success','page'=>$page];
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ], 500);
        }
    }

}
