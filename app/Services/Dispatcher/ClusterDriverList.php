<?php

namespace App\Services\Dispatcher;

use App\Models\Employee;
use App\Models\TmsClusterClient;
use App\Models\TmsClusterDriver;
use App\Models\Tractor;
use App\Services\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ClusterDriverList
{
    public function datatable(Request $rq)
    {
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $filter_status = $rq->filter_status!='all' ? $rq->filter_status : false;

        $data = TmsClusterDriver::with(['employee','cluster'])->where([['cluster_id', $cluster_id],['is_deleted',null]])
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
            $driver = null;
            if($item->employee){
                $driver = $item->employee->fullname();
            }

            $item->count = $key + 1;
            $item->driver = $driver;
            $item->cluster = $item->cluster->name;
            $item->remarks = $item->remarks ?? '--';
            $item->driver_status = $item->status;
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
            $query = TmsClusterDriver::with(['employee'])->find($id);

            $payload = [
                'name' =>optional($query->employee)->fullname() ?? null,
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

            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;
            $cluster_id = Auth::user()->emp_cluster->cluster_id;
            $user_id = Auth::user()->emp_id;
            $emp_id = isset($rq->trailer_driver) ? Crypt::decrypt($rq->trailer_driver):null;

            $attribute = [ 'id' =>$id, 'cluster_id'=>$cluster_id ];
            $values = [
                'remarks' => $rq->remarks,
                'status' =>$rq->status,
            ];
            $query = TmsClusterDriver::updateOrCreate($attribute,$values);
            if ($query->wasRecentlyCreated) {
                $query->update([
                    'emp_id' =>$emp_id,
                    'created_by'=>$user_id,
                ]);
                $message = "Driver is added successfully";
            }else{
                $query->update([
                    'updated_by' => $user_id,
                ]);
                $message = "Details is updated successfully";
            }
            DB::commit();
            return response()->json(['status' => 'success','message'=>$message]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function validate_driver(Request $rq)
    {
        try{
            $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;
            $emp_id = Crypt::decrypt($rq->trailer_driver);
            $exists = TmsClusterDriver::where([['emp_id', $emp_id],['status',1]])
            ->when($excluded_id, function ($q) use ($excluded_id) {
                $q->where('id', '!=', $excluded_id);
            })
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

            $query = TmsClusterDriver::find($id);
            $query->status = 0;
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json(['status' => 'info','message'=>'Driver is removed']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
