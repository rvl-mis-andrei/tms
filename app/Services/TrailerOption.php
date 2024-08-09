<?php

namespace App\Services;

use App\Models\TmsLocation;
use App\Models\Trailer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

class TrailerOption
{
    public function list(Request $rq)
    {
        $query = Trailer::whereNotIn('status', [2, 3, 4]);
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
                $selected = $search == $row->id ? 'selected' : '';
                $id = Crypt::encrypt($row->id);
                $html .= '<option value="'.$id.'"'.$selected.'>'.$row->name.'</option>';
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
            $data = $query->where(function($q) use ($rq) {
                $q->where('description', 'LIKE', '%'.$rq->search.'%')
                  ->orWhere('plate_no', 'LIKE', '%'.$rq->search.'%');
            })
            ->whereHas('trailer_type', function($q) use ($rq) {
                $q->where('name', 'LIKE', '%'.$rq->search.'%');
            })
            ->get();
            if($data)
            {
                foreach($data as $row)
                {
                    $array[]=[
                        'trailer_type' => $row->trailer_type->name,
                        'plate_no' => $row->plate_no,
                        'status' =>config('value.trailer_status.'. $row->status),
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
