<?php

namespace App\Services;

use App\Models\TmsClusterDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ClusterDriverOption
{
    public function list(Request $rq)
    {
        $query = TmsClusterDriver::where('is_active',1);
        return match($rq->type){
            'options' => $this->options($rq,$query),
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
}
