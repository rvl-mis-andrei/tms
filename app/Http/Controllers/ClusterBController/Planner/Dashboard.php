<?php

namespace App\Http\Controllers\ClusterBController\Planner;

use App\Http\Controllers\Controller;
use App\Models\TmsHaulage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Dashboard extends Controller
{
    public function index(Request $rq)
    {
        $data = self::getTruckTripsForMonth($rq);
        
        $page = view('cluster_b.planner.dashboard.trip_monitoring',compact('data'))->render();

        return [ 'status'=>'success', 'message' => 'success', 'payload'=>base64_encode(json_encode($page))];
    }

    private function getTripInfo($block)
    {
        $trips = [];
        foreach ($block->block_unit as $unit) {
            $hub = $unit->hub ? $unit->hub : '';
            $dealerCode = $unit->dealer ? $unit->dealer->code : '';
            $trip = $hub . '-' . $dealerCode;
            if (!in_array($trip, $trips)) {
                $trips[] = $trip;
            }
        }
        return $trips;
    }

    public function getTruckTripsForMonth($rq)
    {
        $month = Carbon::now()->month;
        $currStartMonth = Carbon::createFromDate(Carbon::now()->year, $month, 1)->startOfMonth();
        $currEndMonth = Carbon::createFromDate(Carbon::now()->year, $month, 1)->endOfMonth();

        $startDate = $rq->start_date ?? $currStartMonth;
        $endDate = $rq->end_date ?? $currEndMonth;
        $status = $rq->status_filter;
        $type = $rq->type_filter;
        $search = $rq->search;

        $haulages = TmsHaulage::with([
            'blocks.tractor',
            'blocks.trailer',
            'blocks.block_unit.dealer'
        ])
        ->whereBetween('planning_date', [$startDate, $endDate])
        ->whereHas('blocks', function ($query) {
            $query->where('status',2);
        })
        ->get();

        $truckTrips = [];
        $maxTrips = 0;
        foreach ($haulages as $haulage) {
            foreach ($haulage->blocks as $block) {
                if($block->tractor && $block->trailer){
                    $tractorInfo = $block->tractor->plate_no . ' ' . $block->tractor->body_no;
                    $trailerInfo = $block->trailer->trailer_type->name . ' ' . $block->trailer->plate_no;
                    $trips = self::getTripInfo($block);
                    if (!isset($truckTrips[$tractorInfo])) {
                        $truckTrips[$tractorInfo] = [
                            'trips'=>[$trips],
                            'trailer_plate_no'=>$block->trailer->plate_no,
                            'trailer_type'=>$block->trailer->trailer_type->name,
                            'tractor_plate_no'=>$block->tractor->plate_no,
                            'tractor_body_no'=>$block->tractor->body_no,
                        ];
                    }else{
                        $truckTrips[$tractorInfo]['trips'][]=$trips;
                    }

                    $maxTrips = max($maxTrips, count($truckTrips[$tractorInfo]['trips']));
                }
            }

        }
        return ['truckTrips' => $truckTrips, 'maxTrips' => $maxTrips];
    }

}
