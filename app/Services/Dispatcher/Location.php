<?php

namespace App\Services\Dispatcher;

use App\Models\TmsClusterCarModel;
use App\Models\TmsClusterClient;
use App\Models\TmsGarage;
use App\Models\TmsLocation;
use App\Services\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Location
{
    public function datatable(Request $rq)
    {
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $filter_status = $rq->filter_status!='all' ? $rq->filter_status : false;

        $data = TmsLocation::where(function ($query) use ($cluster_id) {
            $query->where('cluster_id', $cluster_id)
                  ->orWhere('cluster_id', 0);
        })
        ->when($filter_status, function ($q) use ($filter_status) {
            $q->where('is_active', $filter_status);
        })
        ->where('is_deleted',null)
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
            $item->location_name = $item->name;
            $item->status = $item->is_active;
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
            $query = TmsLocation::find($id);

            $payload = [
                'location_name' =>$query->name,
                'status' =>$query->is_active,
                'remarks' =>$query->remarks,
            ];

            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $cluster_id = Auth::user()->emp_cluster->cluster_id;
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;
            $attribute = ['id'=>$id];
            $values = [
                'cluster_id'=>$cluster_id,
                'name' =>strtoupper($rq->location_name),
                'remarks' =>$rq->remarks,
                'is_active' =>$rq->status,
            ];
            $query = TmsLocation::updateOrCreate($attribute,$values);
            if ($query->wasRecentlyCreated) {
                $query->update([
                    'created_by'=>$user_id,
                ]);
                $message = 'Added successfully';
            }else{
                $query->update([
                    'updated_by' => $user_id,
                ]);
                $message = 'Details is updated';
            }
            DB::commit();
            return response()->json(['status' => 'success','message'=>$message]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function validate_location(Request $rq)
    {
        try{
            $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;

            $exists = TmsLocation::where('name', strtoupper($rq->location_name))
            ->when($excluded_id, function ($q) use ($excluded_id) {
                $q->where('id', '!=', $excluded_id);
            })
            ->where('is_active',1)
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

            $query = TmsLocation::find($id);
            $query->is_active = 0;
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json(['status' => 'info','message'=>'Record is removed']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
