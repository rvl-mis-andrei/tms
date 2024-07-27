<?php

namespace App\Http\Controllers\ClusterBController\Dispatcher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class DriverListing extends Controller
{
    public function datatable(Request $rq)
    {
        // $data = ScheduleGroup::with([
        //     'company:company_id,name,description',
        // ])
        // ->where('remove',null)->orWhere('remove',0)
        // ->orderBy('sched_grp_id','ASC')
        // ->get();

        // $data->transform(function ($item,$key){

        //     $item->count = $key+1;
        //     $item->is_active = config('value.is_active.'.$item->is_active);

        //     $item->company_name = $item->company->name;
        //     $item->company_desc = $item->company->description;
        //     $item->is_company_active = config('value.is_active.'.$item->company->is_active);

        //     // $item->last_update_by = $item->lastUpdateBy();

        //     $item->id = Crypt::encrypt($item->sched_grp_id);

        //     return $item;
        // });

        // $table = new DTServerSide($request, $data);
        // $table->renderTable();

        // return response()->json([
        //     'draw' => $table->getDraw(),
        //     'recordsTotal' => $table->getRecordsTotal(),
        //     'recordsFiltered' =>  $table->getRecordsFiltered(),
        //     'data' => $table->getRows()
        // ]);
    }


    public function show(Request $rq)
    {
        try {

            $id = Crypt::decrypt($rq->id);

            return ['status'=>'success','message' => 'Updated Successfully'];

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 400,
                'message' =>  $e->getMessage(),
            ]);
        }
    }

    public function upsert(Request $rq)
    {
        try {
            DB::beginTransaction();

            //code

            DB::commit();
            return ['status'=>'success','message' => 'Created Successfully'];
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 400,
                'message' =>  $e->getMessage(),
            ]);
        }
    }
}
