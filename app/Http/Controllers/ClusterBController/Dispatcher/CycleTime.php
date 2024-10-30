<?php

namespace App\Http\Controllers\ClusterBController\Dispatcher;

use App\Http\Controllers\Controller;
use App\Models\TmsClusterBCycleTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CycleTime extends Controller
{
    public function table(Request $rq)
    {
        try {
            $query = TmsClusterBCycleTime::with([
                'dealership'=>function($q){
                    $q->where('is_active',1);
                },
                'garage'
            ])->get();

            $groupedData = $query->groupBy(function($item) {
                return $item->dealership->location->name; // Group by location name
            });

            $array = [];
            foreach ($groupedData as $dealer_location => $items) {
                foreach ($items as $data) {
                    $dealer_code = $data->dealership->code; // Accessing dealership code
                    $cycletime = [
                        'departure_garage' => $data->garage->name,
                        'svc_garage_to_pickup' => $data->svc_garage_to_pickup,
                        'bvc_garage_to_pickup' => $data->bvc_garage_to_pickup,
                        'time_loading' => $data->time_loading,
                        'departure_to_pickup' => $data->departure_to_pickup,
                        'dealer_to_garage' => $data->dealer_to_garage,
                        'svc_total_cycle_time' => $data->svc_total_cycle_time,
                        'bvc_total_cycle_time' => $data->bvc_total_cycle_time,
                        'additional_day' => $data->additional_day,
                        'encrypted_id' => Crypt::encrypt($data->id),
                    ];

                    // Populate the array, grouping by dealer location and dealer code
                    $array[$dealer_location][$dealer_code][] = $cycletime;
                }
            }

            $payload = base64_encode(json_encode($array));
            return response()->json([
                'status'=>'success',
                'message' => 'Updated Successfully',
                'payload'=>$payload,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' =>  $e->getMessage(),
            ]);
        }
    }

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = TmsClusterBCycleTime::find($id);

            $payload = [

            ];

            return response()->json([
                'status' => 'success',
                'message'=>'success',
                'payload'=>base64_encode(json_encode($payload)),
            ]);
        }catch(Exception $e){
            return response()->json([
                'status'=>400,
                'message' =>$e->getMessage(),
            ]);
        }
    }
}
