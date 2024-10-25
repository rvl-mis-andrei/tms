<?php

namespace App\Http\Controllers\ClusterBController\Dispatcher;

use App\Http\Controllers\Controller;
use App\Models\TmsHaulage;
use App\Models\TmsHaulageBlock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class HaulageInfo extends Controller
{
    public function tripblock(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $blocks = TmsHaulageBlock::with(['block_unit' => function($query) {
                $query->orderBy('unit_order', 'asc');
            }])
            ->where('batch', $rq->batch)
            ->where('haulage_id', $id)
            ->whereNull('is_deleted')
            ->get();

            $array = $blocks->map(function($block) {

                $dealer_arr = collect();
                $dealer_code_arr = collect();

                // Mapping block_unit data
                $block_unit = $block->block_unit->map(function($unit) use ($dealer_arr, $dealer_code_arr) {
                    $dealer = $unit->dealer;

                    // Avoid duplicates in dealer arrays
                    if (!$dealer_arr->contains($dealer->name)) {
                        $dealer_arr->push($dealer->name);
                        $dealer_code_arr->push($dealer->code);
                    }

                    return [
                        'encrypted_id' => Crypt::encrypt($unit->id),
                        'dealer_code' => $dealer->code,
                        'model' => $unit->car->car_model,
                        'cs_no' => $unit->cs_no,
                        'color_description' => $unit->color_description,
                        'invoice_date' => $unit->invoice_date? date('m/d/Y',strtotime($unit->invoice_date)): '--',
                        'updated_location' => $unit->updated_location,
                        'inspection_start' => optional($unit->inspected_start)->format('g:i A') ?? '--',
                        'hub' => $unit->hub ?? '--',
                        'remarks' => $unit->remarks ?? '--',
                        'status' => $unit->status,
                    ];
                });

                return [
                    'encrypted_id' => Crypt::encrypt($block->id),
                    'block_number' => $block->block_number,
                    'batch' => $block->batch,
                    'no_of_trips' => $block->no_of_trips,
                    'dealer' => $dealer_arr->isNotEmpty() ? $dealer_arr->implode(', ') : null,
                    'dealer_code' => $dealer_code_arr->isNotEmpty() ? $dealer_code_arr->implode(', ') : null,
                    'block_units' => $block_unit,
                    'status' => $block->status,
                    'created_by' => optional($block->created_by_emp)->fullname() ?? 'No record found',
                    'updated_by' => optional($block->updated_by_emp)->fullname() ?? 'No record found',
                ];

            })->toArray();
            $payload = base64_encode(json_encode($array));
            return ['status'=>'success','message' =>'success', 'payload' => $payload];
        }catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }
}
