<?php

namespace App\Services;

use App\Models\TmsClusterDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ClusterDriverOption
{
    public function list(Request $rq)
    {
        $query = TmsClusterDriver::whereNotIn('status', [2, 3, 4]);
        return match($rq->type){
            'options' => $this->options($rq,$query),
            'search_modal' => $this->search_modal($rq,$query),
        };
    }


    public function options($rq,$query)
    {
        $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $data = $query->with('employee')->get();
        if ($data->count()) {
            $html = '<option></option>';
            foreach ($data as $row) {
                $selected = $search == $row->id ? 'selected' : '';
                $id = Crypt::encrypt($row->id);
                $html .= '<option value="'.$id.'"'.$selected.'>'.$row->employee->fullname().'</option>';
            }
            return $html;
        } else {
            return '<option disabled>No Available Driver</option>';
        }
    }


    public function datatable($rq, $query)
    {

    }

    public function search_modal($rq,$query)
    {
        $array = [];
        if(isset($rq->search)){
            $data = $query->whereHas('employee', function($query) use ($rq) {
                $query->where(function($subQuery) use ($rq) {
                    $subQuery->whereRaw(
                        "CONCAT(fname, ' ', lname) LIKE ?",
                        ["%{$rq->search}%"]
                    );
                })->orWhere('emp_no', 'LIKE', "%{$rq->search}%");
            })
            ->get();
            if($data)
            {
                foreach($data as $row)
                {
                    $array[]=[
                        'name' => $row->employee->fullname(),
                        'emp_no' => $row->employee->emp_no??'--',
                        'status' =>config('value.cluster_driver_status.'. $row->status),
                        'id' => Crypt::encrypt($row->id),
                    ];
                }
            }
        }
        return $array;
    }
}
