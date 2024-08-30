<?php

namespace App\Services;

use App\Models\TmsClientDealership;
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

class DealerOption
{

    public function list(Request $rq)
    {
        $query = TmsClientDealership::where('is_active',1);
        return match($rq->type){
            'options' => $this->options($rq,$query),
        };
    }


    public function options($rq,$query)
    {
        $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $data = $query->get();
        if ($data->count()) {
            $html = '<option></option>';
            foreach ($data as $row) {
                $selected = $search == $row->id ? 'selected' : '';
                $id = Crypt::encrypt($row->id);
                $html .= '<option value="'.$id.'"'.$selected.'>'.$row->name.' ['.$row->code.']'.'</option>';
            }
            return $html;
        } else {
            return '<option disabled>No Available Location</option>';
        }
    }


    public function datatable($rq, $query)
    {

    }

}
