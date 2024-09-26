<?php

namespace App\Http\Controllers\ClusterBController\Dispatcher;

use App\Http\Controllers\Controller;
use App\Models\TmsHaulageAttendance;
use App\Models\TractorTrailerDriver as TractorTrailerDriverCat;
use App\Services\DTServerSide;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TractorTrailerDriver extends Controller
{
    public function dt(Request $rq)
    {
        $cluster_id = Auth::user()->emp_cluster->cluster_id;
        $haulage_id = Crypt::decrypt($rq->id);

        $data = TractorTrailerDriverCat::with([
            'trailer',
            'tractor',
            'sdriver_emp.employee:id,fname,lname',
            'pdriver_emp.employee:id,fname,lname',
            'haulage_att' => function ($q) use ($haulage_id) {
                $q->where('haulage_id', $haulage_id);
            }
        ])
        ->where('cluster_id', $cluster_id)
        ->whereNull('is_deleted')
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) {
            $item->count = $key + 1;

            // Set common properties
            $item->tractor_name = optional($item->haulage_att)->tractor->name ?? optional($item->tractor)->name ?? '';
            $item->tractor_plate_no = optional($item->haulage_att)->tractor->plate_no ?? optional($item->tractor)->plate_no ?? '';
            $item->trailer_name = optional($item->haulage_att)->trailer->name ?? optional($item->trailer)->name ?? '';
            $item->trailer_type = optional($item->haulage_att)->trailer->trailer_type->name ?? optional($item->trailer->trailer_type)->name ?? '';
            $item->tractor_trailer_status = optional($item->haulage_att)->tractor_trailer_status ?? $item->status ?? '';

            // Safely access pdriver and sdriver names
            $item->pdriver_name = optional($item->pdriver_emp->employee)->fullname() ?? '';
            $item->sdriver_name = optional($item->sdriver_emp->employee)->fullname() ?? '';

            $item->encrypted_id = Crypt::encrypt($item->id);

            // Set attendance properties if haulage_att exists
            if ($item->haulage_att) {
                $item->pdriver_att = $item->haulage_att->is_present_pdriver ?? '';
                $item->sdriver_att = $item->haulage_att->is_present_sdriver ?? '';
                $item->remarks = $item->haulage_att->remarks ?? '--';
                $item->encrypted_id = Crypt::encrypt($item->haulage_att->id);
                $item->url = 'update_attendance';
                $item->is_started = true;
            } else {
                $item->pdriver_att = null; // or some default value
                $item->sdriver_att = null; // or some default value
                $item->remarks = $item->remarks ?? '--';
                $item->url = '';
                $item->is_started = false;
            }

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

    public function find(Request $rq)
    {
        $tractor_trailer_id = Crypt::decrypt($rq->id);
        $query = TractorTrailerDriverCat::find($tractor_trailer_id);

        return $query;
    }

    public function update_tractor_trailer(Request $rq)
    {
        try{
            DB::beginTransaction();

            $haulage_id = Crypt::decrypt($rq->haulage_id);
            $tractor_id = Crypt::decrypt($rq->tractor_id);
            $trailer_id = Crypt::decrypt($rq->trailer_id);
            $query = self::find($rq);

            if(!$query){
                return response()->json(['status' => 'error','message'=>'Something went wrong. Try again later']);
            }

            $query->tractor_id = $tractor_id;
            $query->trailer_id = $trailer_id;
            $query->status = $rq->tractor_trailer_status;
            $query->updated_by = Auth::user()->emp_id;
            $query->save();

            (new HaulageAttendance)->update_tractor_trailer_att($rq);

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Attendance is updated', 'payload'=>'']);

        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);

        }

    }
}
