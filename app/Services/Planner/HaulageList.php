<?php

namespace App\Services\Planner;

use App\Models\TmsClusterClient;
use App\Models\TmsHaulage;
use App\Services\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HaulageList
{
    public function datatable(Request $rq)
    {
        $status = $rq->filter;
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $data = TmsHaulage::when($status!="all", function ($q) use ($status) {
            return $q->where('status',$status);
        })->where(function ($query) {
            $query->where('is_deleted','!=',1)->orWhereNull('is_deleted');
        })->where('cluster_id',$cluster_id)->get();
        $data->transform(function ($item,$key){
            $item->count = $key+1;
            $item->status = config('value.haulage_status.'.$item->status);
            $item->name = $item->name;
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

    public function validate(Request $rq)
    {
        $id = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $query = TmsHaulage::where('name',$rq->name)
        ->when($id, function ($q) use ($id) {
            return $q->where('id','!=',$id);
        })->where(function ($q) {
            $q->where('is_deleted','!=',1)->orWhereNull('is_deleted');
        })->first();
        return json_encode(array( 'valid' => $query === null ?? false ));
    }

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = TmsHaulage::findorFail($id);
            $query->filenames = json_decode($query->filenames,true) ?? [];
            $payload = base64_encode(json_encode([
                'name' =>$query->name,
                'remarks' =>$query->remarks,
                'status' =>$query->status,
                'plan_type'=>$query->plan_type,
                'filenames'=>$query->filenames,
                'planning_date' =>$query->planning_date,
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
            $create = TmsHaulage::create([
                'name' => $rq->name,
                'cluster_id' => $cluster_id,
                'remarks' => $rq->remarks,
                'plan_type' => $rq->plan_type,
                'status' => $rq->status,
                'planning_date' => Carbon::createFromFormat('m-d-Y',$rq->planning_date)->format('Y-m-d'),
                'created_by' => Auth::user()->emp_id,
            ]);
            $collaborator = (new HaulageCollaborators)->add_collaborators($create->id,$cluster_id);
            if($collaborator != true){
                DB::rollback();
                return response()->json([ 'status' => 400,  'message' =>  'No collaborator available' ]);
            }

            DB::commit();
            return ['status'=>'success','message' =>'Hauling plan added successfully'];
        }catch(Exception $e){
            DB::rollback();
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
        }
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);
            $query = TmsHaulage::find($id);
            $query->name = $rq->name;
            $query->remarks = $rq->remarks;
            $query->status = $rq->status;
            $query->planning_date = Carbon::createFromFormat('m-d-Y',$rq->planning_date)->format('Y-m-d');
            $query->updated_by = Auth::user()->emp_id;
            $query->save();

            DB::commit();
            return ['status'=>'success','message' =>'Hauling plan details is updated'];
        }catch(Exception $e){
            DB::rollback();
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
        }
    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->id);
            TmsHaulage::where('id', $id)->update([
                'status' => $rq->is_active,
                'is_deleted' => $rq->is_deleted,
                'deleted_by' => Auth::user()->emp_id,
            ]);
            DB::commit();
            return ['status'=>'success','message' =>'Hauling plan is removed'];
        }catch(Exception $e){
            DB::rollback();
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
        }
    }

    public function move_file($rq,$folder='hauling_plan')
    {
        try{
            DB::beginTransaction();
            $filename = Str::random(10) . '.' . $rq->file($folder)->getClientOriginalExtension();
            $filePath = $rq->file($folder)->storeAs($folder, $filename, 'public');

            if (Storage::disk('public')->exists($filePath)) {
                $id    = Crypt::decrypt($rq->id);
                $query = TmsHaulage::find($id);
                $files = json_decode($query->filenames,true) ??[];
                if($folder=='masterlist' && count($files) >= 1){
                    return ['status'=>'error','message' =>'You already uploaded a masterlist'];
                }
                if(count($files) >= 2 && $folder == 'hauling_plan') {
                    return ['status'=>'error','message' =>'You already uploaded 2 hauling plan'];
                }
                $files[] = $filename;
                $query->filenames = json_encode($files);
                $query->save();
                DB::commit();
                return ['status'=>'success','message' =>'Hauling plan is removed'];
            }
        }catch(Exception $e){
            DB::rollback();
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
        }
    }

    public function reupload_masterlist(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id    = Crypt::decrypt($rq->id);
            $query = TmsHaulage::find($id);
            $query->filenames = null;
            $query->save();

            DB::commit();
            return ['status'=>'success','message' =>'Masterlist is removed'];
        }catch(Exception $e){
            DB::rollback();
            return ['status'=>400,'message' =>$e->getMessage()];
        }
    }

    public function reupload_hauling_plan(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id    = Crypt::decrypt($rq->id);
            $query = TmsHaulage::find($id);
            $files = json_encode($query->filenames,true);
            $index = $rq->batch-1;
            unset($files[$index]);
            $query->filenames = json_encode($files);
            $query->save();

            DB::commit();
            return ['status'=>'success','message' =>'Hauling plan is removed'];
        }catch(Exception $e){
            DB::rollback();
            return ['status'=>400,'message' =>$e->getMessage()];
        }
    }

}
