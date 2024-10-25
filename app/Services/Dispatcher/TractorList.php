<?php

namespace App\Services\Dispatcher;

use App\Models\Tractor;
use App\Services\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TractorList
{
    public function datatable(Request $rq)
    {
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $filter_status = $rq->filter_status!='all' ? $rq->filter_status : false;

        $data = Tractor::where([['cluster_id', $cluster_id],['is_deleted',null]])
        ->when($filter_status, function ($q) use ($filter_status) {
            $q->where('status', $filter_status);
        })
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) {

            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }

            $item->count = $key + 1;
            $item->tractor_name = $item->name;
            $item->tractor_plate_no = $item->plate_no;
            $item->remarks = $item->remarks ?? '--';
            $item->tractor_status = $item->status;
            $item->last_updated_by = $last_updated_by;
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

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = Tractor::find($id);

            $payload = [
                'plate_no' =>$query->plate_no,
                'body_no' =>$query->body_no,
                'status' =>$query->status,
                'remarks' =>$query->remarks,
            ];

            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }

    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();
            $cluster_id = Auth::user()->emp_cluster->cluster_id;
            $user_id = Auth::user()->emp_id;
            $plate_no = strtoupper($rq->plate_no);
            $body_no = strtoupper($rq->body_no);
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;


            $attribute = ['id' =>$id ];
            $values = [
                'plate_no' =>$plate_no,
                'body_no' =>$body_no,
                'cluster_id' =>$cluster_id,
                'name' =>$body_no,
                'remarks' => $rq->remarks,
                'status' =>$rq->status,
            ];

            $query = Tractor::updateOrCreate($attribute,$values);

            if ($query->wasRecentlyCreated) {
                $query->update([
                    'created_by'=>$user_id,
                ]);
            }else{
                $query->update([
                    'updated_by' => $user_id,
                ]);

            }
            DB::commit();
            return response()->json(['status' => 'success','message'=>'Tractor is added successfully']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function validate_plate_number(Request $rq)
    {
        try{
            $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;

            $exists = Tractor::where('plate_no',strtoupper($rq->plate_no))
            ->when($excluded_id,function($q) use($excluded_id){
                $q->where('id','!=',$excluded_id);
            })
            ->where('is_deleted',null)
            ->exists();

            return response()->json(['valid' => !$exists]);
        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function validate_body_number(Request $rq)
    {
        try{
            $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;

            $exists = Tractor::where('body_no', strtoupper($rq->body_no))
            ->when($excluded_id, function ($q) use ($excluded_id) {
                $q->where('id', '!=', $excluded_id);
            })
            ->where('is_deleted',null)
            ->exists();

            return response()->json(['valid' => !$exists]);
        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = Tractor::find($id);
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json(['status' => 'info','message'=>'Trailer is deleted']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

}
