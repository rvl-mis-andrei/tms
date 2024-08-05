<?php

namespace App\Services\Dispatcher;

use App\Models\TractorTrailerDriver;
use App\Services\DTServerSide;
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
            $item->status = config('value.status.'.$item->status);
            $item->tractor_plate_no = $item->tractor->plate_no;
            $item->tractor_status = config('value.tractor_status.'.$item->tractor->status);
            $item->trailer_status = config('value.trailer_status.'.$item->trailer->status);
            $item->trailer_type = $item->trailer->trailer_type->name;
            $item->sdriver_emp = $item->sdriver_emp->fullname();
            $item->pdriver_emp = $item->pdriver_emp->fullname();
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

    // public function validate(Request $rq)
    // {
    //     $id = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
    //     $query = TmsClusterClient::where('name',$rq->name)
    //     ->when($id, function ($q) use ($id) {
    //         return $q->where('id','!=',$id);
    //     })->first();
    //     return json_encode(array( 'valid' => $query === null ?? false ));
    // }

    // public function info(Request $rq)
    // {
    //     try{
    //         $id = Crypt::decrypt($rq->id);
    //         $query = TmsClusterClient::with('client_dealership')->findorFail($id);
    //         $dealership=[];
    //         $active_dealership = 0;
    //         $inactive_dealership = 0;
    //         foreach($query->client_dealership as $data)
    //         {
    //             $receiving_personnel = json_decode($data->receiving_personnel,true);
    //             $data->is_active ? $active_dealership++ :$inactive_dealership++;
    //             $dealership[]=[
    //                 'encrypted_id' => Crypt::encrypt($data->id),
    //                 'name' =>$data->name,
    //                 'code' =>$data->code,
    //                 'location' =>$data->location->name,
    //                 'is_active'=>config('value.is_active.'.$data->is_active),
    //                 'pv_lead_time'=>$data->pv_lead_time,
    //                 'receiving_personnel'=>$receiving_personnel,
    //             ];
    //         }
    //         $payload = base64_encode(json_encode([
    //             'name' =>$query->name,
    //             'dealership' =>$dealership,
    //             'active_dealership' =>$active_dealership,
    //             'inactive_dealership' =>$inactive_dealership,
    //             'description' =>$query->description ?? 'No Description',
    //             'is_active' =>$query->is_active,
    //             'created_by'=>$query->employee->fullname ?? 'No record found',
    //             'created_at'=>Carbon::parse($query->created_at)->format('F j, Y'),
    //         ]));
    //         return ['status'=>'success','message' =>'success', 'payload' => $payload];
    //     }catch(Exception $e) {
    //         return response()->json([
    //             'status' => 400,
    //             // 'message' =>  'Something went wrong. try again later'
    //             'message' =>  $e->getMessage()
    //         ]);
    //     }
    // }

    public function create(Request $rq)
    {
        try{
            DB::beginTransaction();
            $cluster_id = Auth::user()->emp_cluster->cluster_id;
            TractorTrailerDriver::create([
                'cluster_id' => $cluster_id,
                'tractor_id' => $rq->tractor,
                'trailer_id' => $rq->trailer,
                'remarks' => $rq->remarks,
                'is_active' => $rq->is_active,
                'created_by' => Auth::user()->emp_id,
            ]);

            DB::commit();
            return ['status'=>'success','message' =>'Client added successfully'];
        }catch(Exception $e){
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
        }
    }

    // public function update(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();
    //         $id = Crypt::decrypt($rq->id);
    //         $query = TmsClusterClient::find($id);
    //         $query->name = $rq->name;
    //         $query->description = $rq->description;
    //         $query->is_active = $rq->is_active;
    //         $query->updated_by   = Auth::user()->emp_id;
    //         $query->save();
    //         DB::commit();
    //         return ['status'=>'success','message' =>'Client details is updated'];
    //     }catch(Exception $e){
    //         return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
    //     }
    // }

    // public function delete(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();
    //         $id = Crypt::decrypt($rq->id);
    //         TmsClusterClient::where('id', $id)->update([
    //             'is_active' => $rq->is_active,
    //             'is_deleted' => $rq->is_deleted,
    //             'deleted_by' => Auth::user()->emp_id,
    //         ]);
    //         DB::commit();
    //         return ['status'=>'success','message' =>'Client is removed'];
    //     }catch(Exception $e){
    //         return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
    //     }
    // }

}
