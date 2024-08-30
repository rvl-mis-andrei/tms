<?php

namespace App\Services;

use App\Models\TmsClientDealership;
use App\Models\TmsClusterCarModel;
use App\Models\TmsClusterClient;
use App\Models\TmsClusterPersonnel;
use App\Models\TmsHaulage;
use App\Services\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CarModelOption
{

    public function list(Request $rq)
    {
        $search = $rq->input('search');
        $query = TmsClusterCarModel::where('car_model', 'LIKE', '%' . $search . '%')->where('is_active',1)->limit(10)->get();
        return response()->json($query->map(function($item) {
            return [
                'id' => Crypt::encrypt($item->id),
                'name' => $item->car_model // Adjust this to your data structure
            ];
        }));
    }


    // public function options($rq,$query)
    // {
    //     $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
    //     $data = $query->get();
    //     if ($data->count()) {
    //         $html = '<option></option>';
    //         foreach ($data as $row) {
    //             $selected = $search == $row->id ? 'selected' : '';
    //             $id = Crypt::encrypt($row->id);
    //             $html .= '<option value="'.$id.'"'.$selected.'>'.$row->car_model.'</option>';
    //         }
    //         return $html;
    //     } else {
    //         return '<option disabled>No Available Location</option>';
    //     }
    // }


    // public function datatable($rq, $query)
    // {

    // }

}
