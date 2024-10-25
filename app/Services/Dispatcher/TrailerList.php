<?php

namespace App\Services\Dispatcher;

use App\Models\TmsClusterClient;
use App\Models\Trailer;
use App\Models\TrailerType;
use App\Services\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TrailerList
{

    public function datatable(Request $rq)
    {
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $filter_status = $rq->filter_status!='all' ? $rq->filter_status : false;

        $data = Trailer::where([['cluster_id', $cluster_id],['is_deleted',null]])
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
            $item->trailer_name = $item->name ;
            $item->trailer_plate_no = $item->plate_no;
            $item->trailer_types = $item->trailer_type->name;
            $item->remarks = $item->remarks ?? '--';
            $item->trailer_status = $item->status;
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
            $query = Trailer::find($id);

            $payload = [
                'plate_no' =>$query->plate_no,
                'status' =>$query->status,
                'remarks' =>$query->remarks,
                'trailer_type' =>$query->trailer_type->name,
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
            $trailer_type_id = null;
            $plate_no = strtoupper($rq->plate_no);
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;

            try {
                $trailer_type_id = Crypt::decrypt($rq->trailer_type);
            } catch (Exception $e) {
                $newTrailerType = TrailerType::firstOrCreate(
                    [
                        'name' => strtoupper($rq->trailer_type)
                    ],
                    [
                        'status' =>1,
                        'created_by' =>$user_id
                    ]
                );
                $trailer_type_id = $newTrailerType->id;
            }

            $attribute = ['id' =>$id ];
            $values = [
                'plate_no' =>$plate_no,
                'cluster_id' =>$cluster_id,
                'name' =>$plate_no,
                'trailer_type_id' =>$trailer_type_id,
                'remarks' => $rq->remarks,
                'status' =>$rq->status,
            ];

            $query = Trailer::updateOrCreate($attribute,$values);

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
            return response()->json(['status' => 'success','message'=>'Trailer is added successfully']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = Trailer::find($id);
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

    public function validate_plate_number(Request $rq)
    {
        try{
            $excluded_id = $rq->id && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;
            $exists = Trailer::where('plate_no',strtoupper($rq->plate_no))
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
}
