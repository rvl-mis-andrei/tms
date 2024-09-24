<?php

namespace App\Http\Controllers\ClusterBController\Dispatcher;

use App\Http\Controllers\Controller;
use App\Models\TmsHaulageAttendance;
use App\Models\TractorTrailerDriver as TractorTrailerDriverCat;
use App\Services\DTServerSide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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
            $item->url = '';

            // Set attendance properties if haulage_att exists
            if ($item->haulage_att) {
                $item->pdriver_att = $item->haulage_att->is_present_pdriver ?? '';
                $item->sdriver_att = $item->haulage_att->is_present_sdriver ?? '';
                $item->remarks = $item->haulage_att->remarks ?? '--';
            } else {
                $item->pdriver_att = null; // or some default value
                $item->sdriver_att = null; // or some default value
                $item->remarks = $item->remarks ?? '--';
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
}
