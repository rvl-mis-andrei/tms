<?php

namespace App\Services\Dispatcher;

use App\Models\TmsClientDealership;
use App\Services\DealershipLocation;
use App\Services\DTServerSide;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ClientDealershipList
{
    public function datatable(Request $rq)
    {
        $is_active = $rq->status;
        $client_id  = isset($rq->client_id) ? Crypt::decrypt($rq->client_id) :false;
        $data = TmsClientDealership::when($is_active!="all", function ($q) use ($is_active) {
            return $q->where('is_active',$is_active);
        })->where(function ($query) {
            $query->where('is_deleted','!=',1)->orWhereNull('is_deleted');
        })->where('client_id',$client_id)->get();
        $data->transform(function ($item,$key){
            $item->count = $key+1;
            $item->is_active = config('value.is_active.'.$item->is_active);
            $item->name = $item->name;
            $item->code = $item->code;
            $item->location = $item->location->name;
            $item->pv_lead_time = $item->pv_lead_time ?? '--';
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
        $id = isset($rq->id)?Crypt::decrypt($rq->id):false;
        $query = TmsClientDealership::where('code',$rq->code)
        ->when($id, function ($q) use ($id) {
            return $q->where('id','!=',$id);
        })->first();
        return json_encode(array( 'valid' => $query === null ?? false ));
    }

    public function info(Request $rq){
        try{
            $id = Crypt::decrypt($rq->id);
            $query = TmsClientDealership::findorFail($id);
            $location= (new DealershipLocation)->list(
                $rq->merge(['id' => Crypt::encrypt($query->location_id)])
            );

            $payload = base64_encode(json_encode([
                'name' =>$query->name,
                'code' =>$query->code,
                'location' =>$location,
                'pv_lead_time' =>$query->pv_lead_time,
                'is_active' =>$query->is_active,
            ]));
            // foreach($query->client_dealership as $data)
            // {
            //     $receiving_personnel = json_decode($data->receiving_personnel,true);
            //     $data->is_active ? $active_dealership++ :$inactive_dealership++;
            //     $dealership[]=[
            //         'encrypted_id' => Crypt::encrypt($data->id),
            //         'name' =>$data->name,
            //         'code' =>$data->code,
            //         'location' =>$data->location->name,
            //         'is_active'=>config('value.is_active.'.$data->is_active),
            //         'pv_lead_time'=>$data->pv_lead_time,
            //         'receiving_personnel'=>$receiving_personnel,
            //     ];
            // }
            // $payload = base64_encode(json_encode([
            //     'name' =>$query->name,
            //     'dealership' =>$dealership,
            //     'active_dealership' =>$active_dealership,
            //     'inactive_dealership' =>$inactive_dealership,
            //     'description' =>$query->description ?? 'No Description',
            //     'is_active' =>$query->is_active,
            //     'created_by'=>$query->employee->fullname ?? 'No record found',
            //     'created_at'=>Carbon::parse($query->created_at)->format('F j, Y'),
            // ]));
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
            $receiving_personnel = [];
            foreach(json_decode($rq['repeater']) as $data){
                foreach($data as $row)
                {
                    $receiving_personnel[]=[
                        'name'=>$row->name,
                        'contact_number'=>$row->contact_number,
                    ];
                }
            }
            TmsClientDealership::create([
                'name' => $rq->name,
                'code' => $rq->code,
                'client_id' => Crypt::decrypt($rq->client_id),
                'location_id' => Crypt::decrypt($rq->location),
                'pv_lead_time' => $rq->pv_lead_time,
                'receiving_personnel' =>json_encode($receiving_personnel),
                'is_active' => $rq->is_active,
                'created_by' => Auth::user()->emp_id,
            ]);
            DB::commit();
            return ['status'=>'success','message' =>'Dealership added successfully'];
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update(Request $rq)
    {

    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);
            TmsClientDealership::where('id', $id)->update([
                'is_active' => $rq->is_active,
                'is_deleted' => $rq->is_deleted,
                'deleted_by' => Auth::user()->emp_id,
            ]);
            DB::commit();
            return ['status'=>'success','message' =>'Dealership is removed'];
        }catch(Exception $e){
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
        }
    }
}
