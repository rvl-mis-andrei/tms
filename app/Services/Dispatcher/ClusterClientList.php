<?php

namespace App\Services\Dispatcher;

use App\Models\TmsClusterClient;
use App\Services\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ClusterClientList
{
    public function datatable(Request $rq)
    {
        $is_active = $rq->status;
        $cluster_id = Auth::user()->emp_cluster->cluster_id;

        $data = TmsClusterClient::when($is_active!="all", function ($q) use ($is_active) {
            return $q->where('is_active',$is_active);
        })->where(function ($query) {
            $query->where('is_deleted','!=',1)->orWhereNull('is_deleted');
        })->where('cluster_id',$cluster_id)->get();

        $data->transform(function ($item,$key){

            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }

            $item->count = $key+1;
            $item->is_active = config('value.is_active.'.$item->is_active);
            $item->description = $item->description ?? '--';
            $item->last_updated_by = $last_updated_by;

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

    public function validate(Request $rq)
    {
        $excluded_id = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $query = TmsClusterClient::where('name',$rq->name)
        ->when($excluded_id, function ($q) use ($excluded_id) {
            return $q->where('id','!=',$excluded_id);
        })->first();
        return json_encode(array( 'valid' => $query === null ?? false ));
    }

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = TmsClusterClient::with('client_dealership')->findorFail($id);
            $dealership=[];
            $active_dealership = 0;
            $inactive_dealership = 0;
            foreach($query->client_dealership as $data)
            {
                $receiving_personnel = json_decode($data->receiving_personnel,true);
                $data->is_active ? $active_dealership++ :$inactive_dealership++;
                $dealership[]=[
                    'encrypted_id' => Crypt::encrypt($data->id),
                    'name' =>$data->name,
                    'code' =>$data->code,
                    'location' =>$data->location->name,
                    'is_active'=>config('value.is_active.'.$data->is_active),
                    'pv_lead_time'=>$data->pv_lead_time,
                    'receiving_personnel'=>$receiving_personnel,
                ];
            }
            $payload = base64_encode(json_encode([
                'name' =>$query->name,
                'dealership' =>$dealership,
                'active_dealership' =>$active_dealership,
                'inactive_dealership' =>$inactive_dealership,
                'description' =>$query->description ?? 'No Description',
                'is_active' =>$query->is_active,
                'created_by'=>$query->employee->fullname ?? 'No record found',
                'created_at'=>Carbon::parse($query->created_at)->format('F j, Y'),
            ]));
            return ['status'=>'success','message' =>'success', 'payload' => $payload];
        }catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function create(Request $rq)
    {
        try{
            DB::beginTransaction();
            $cluster_id = Auth::user()->emp_cluster->cluster_id;
            TmsClusterClient::create([
                'name' => $rq->name,
                'cluster_id' => $cluster_id,
                'description' => $rq->description,
                'is_active' => $rq->is_active,
                'created_by' => Auth::user()->emp_id,
            ]);
            //CREATE A TRIGGER
            DB::commit();
            return ['status'=>'success','message' =>'Client added successfully'];
        }catch(Exception $e){
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
        }
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);
            $query = TmsClusterClient::find($id);
            $query->name = $rq->name;
            $query->description = $rq->description;
            $query->is_active = $rq->is_active;
            $query->updated_by   = Auth::user()->emp_id;
            $query->save();
            DB::commit();
            return ['status'=>'success','message' =>'Client details is updated'];
        }catch(Exception $e){
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
        }
    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);
            TmsClusterClient::where('id', $id)->update([
                'is_active' => $rq->is_active,
                'is_deleted' => $rq->is_deleted,
                'deleted_by' => Auth::user()->emp_id,
            ]);
            DB::commit();
            return ['status'=>'success','message' =>'Client is removed'];
        }catch(Exception $e){
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
        }
    }

}
