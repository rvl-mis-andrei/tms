<?php

namespace App\Services\Dispatcher;

use App\Models\TmsClusterCarModel;
use App\Models\TmsClusterClient;
use App\Models\TmsGarage;
use App\Models\TmsLocation;
use App\Models\TrailerType as CatTrailerType;
use App\Services\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TrailerType
{
    public function datatable(Request $rq)
    {
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $filter_status = $rq->filter_status!='all' ? $rq->filter_status : false;

        $data = CatTrailerType::where('is_deleted',null)
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
            $item->trailer_type_name = $item->name;
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
            $query = CatTrailerType::find($id);

            $payload = [
                'trailer_type_name' =>$query->name,
                'description' =>$query->description,
                'status' =>$query->status,
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
                'name' =>strtoupper($rq->trailer_type_name),
                'description' =>$rq->description,
                'status' =>$rq->status,
            ];
            $query = CatTrailerType::updateOrCreate($attribute,$values);
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

    public function validate_trailer_type(Request $rq)
    {
        try{
            $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;

            $exists = TmsLocation::where('name', strtoupper($rq->trailer_type_name))
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

            $query = CatTrailerType::find($id);
            $query->status = 0;
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
