<?php

namespace App\Services;

use App\Models\Tractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TractorOption
{
    public function list(Request $rq)
    {
        $query = Tractor::query();
        return match($rq->type){
            'options' => $this->options($rq,$query),
            'search_modal' => $this->search_modal($rq,$query),
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
                $html .= '<option value="'.$id.'"'.$selected.'>'.$row->name.' ['.$row->plate_no.']'.'</option>';
            }
            return $html;
        } else {
            return '<option disabled>No Available Location</option>';
        }
    }


    public function search_modal($rq,$query)
    {
        $array = [];
        if(isset($rq->search)){
            $data = $query->where('body_no', 'LIKE', '%'.$rq->search.'%')->orWhere('plate_no', 'LIKE', '%'.$rq->search.'%')->get();
            if($data)
            {
                foreach($data as $row)
                {
                    $array[]=[
                        'description' => $row->description,
                        'plate_no' => $row->plate_no,
                        'status' =>config('value.tractor_status.'. $row->status),
                        'id' => Crypt::encrypt($row->id),
                    ];
                }
            }
        }
        return $array;
    }


    public function datatable($rq, $query)
    {

    }
}
