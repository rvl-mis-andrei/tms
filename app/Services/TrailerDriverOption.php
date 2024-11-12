<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\TmsLocation;
use App\Models\Trailer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

class TrailerDriverOption
{
    public function list(Request $rq)
    {
        $query = Employee::with('cluster_driver')
        ->whereHas('emp_details',function($q){
            $q->where([['position_id',61],['status',1]]);
        })
        ->where(function($q) {
            $q->whereDoesntHave('cluster_driver') // No cluster_driver records
              ->orWhereHas('cluster_driver', function($subQuery) {
                  $subQuery->where([['status', 2],['is_deleted',null]]); // cluster_driver status is 0
              });
        });

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
                $html .= '<option value="'.$id.'"'.$selected.'>'.$row->fullname().'</option>';
            }
            return $html;
        } else {
            return '<option disabled>No Available Option</option>';
        }
    }
}
