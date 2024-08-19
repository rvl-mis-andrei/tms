<?php

namespace App\Services\Planner;

use App\Models\TmsHaulageCollaborator;
use App\Services\ClusterPersonnelList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HaulageCollaborators
{
    public function datatable(Request $rq)
    {

    }

    public function create(Request $rq)
    {

    }


    public function add_collaborators($haulage_id,$cluster_id)
    {
        try{
            DB::beginTransaction();
            $id = (new ClusterPersonnelList)->get_personnel($cluster_id);
            if(!$id) {  return false; }
            $array = [];
            foreach($id as $personnel_id)
            {
                $array[]=[
                    'haulage_id'=>$haulage_id,
                    'cluster_personnel_id'=>$personnel_id,
                    'status'=>1,
                    'created_by'=>Auth::user()->emp_id,
                ];
            }

            TmsHaulageCollaborator::create($array);
            DB::commit();
            return true;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function update_conollaborators()
    {

    }
}
