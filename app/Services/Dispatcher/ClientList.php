<?php

namespace App\Services\Dispatcher;

use App\Models\TmsClient;
use App\Services\DTServerSide;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ClientList
{
    public function datatable(Request $rq)
    {
        $status = $rq->status;
        $data = TmsClient::when($status!="all", function ($q) use ($status) {
            return $q->where('is_active',$status);
        })->where(function ($query) {
            $query->where('is_deleted','!=',1)->orWhereNull('is_deleted');
        })->get();
        $data->transform(function ($item,$key){
            $item->count = $key+1;
            $item->is_active = config('value.is_active.'.$item->is_active);
            $item->name = $item->name;
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
        $id = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $query = TmsClient::where('name',$rq->name)
        ->when($id, function ($q) use ($id) {
            return $q->where('id','!=',$id);
        })->first();
        return json_encode(array( 'valid' => $query === null ?? false ));
    }

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = TmsClient::findorFail($id);
            $payload = base64_encode(json_encode([
                'name' =>$query->name,
                'is_active' =>$query->is_active,
            ]));
            return [
                'status'=>'success',
                'message' =>'success',
                'payload' => $payload
            ];
        }catch(Exception $e) {
            return response()->json([
                'status' => 400,
                'message' =>  'Something went wrong. try again later',
            ]);
        }
    }

    public function create(Request $rq)
    {
        try{
            DB::beginTransaction();
            TmsClient::create([
                'name' => $rq->name,
                'is_active' => $rq->is_active,
                'created_by' => Auth::user()->emp_id,
            ]);
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
            TmsClient::where('id', $id)->update([
                'name' => $rq->name,
                'is_active' => $rq->is_active,
                'updated_by' => Auth::user()->emp_id,
            ]);
            DB::commit();
            return ['status'=>'success','message' =>'Details is updated'];
        }catch(Exception $e){
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
        }
    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);
            TmsClient::where('id', $id)->update([
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
