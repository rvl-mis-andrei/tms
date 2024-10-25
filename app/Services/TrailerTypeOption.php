<?php

namespace App\Services;

use App\Models\TmsLocation;
use App\Models\Trailer;
use App\Models\TrailerType;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

class TrailerTypeOption
{
    public function list(Request $rq)
    {
        $query = TrailerType::query();
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
                $selected = $search === $row->id ? 'selected' : '';
                $id = Crypt::encrypt($row->id);
                $html .= '<option value="'.$id.'"'.$selected.'>'.$row->name.'</option>';
            }
            return $html;
        } else {
            return '<option disabled>No Available Option</option>';
        }
    }

}
