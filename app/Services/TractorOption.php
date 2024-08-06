<?php

namespace App\Services;

use App\Models\Tractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TractorOption
{
    public function list(Request $rq)
    {
        $query = Tractor::whereNotIn('status', [2, 3, 4]);
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
                $html .= '<option value="'.$id.'"'.$selected.'>'.$row->name.' ['.$row->plate_no.']'.'</option>';
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
