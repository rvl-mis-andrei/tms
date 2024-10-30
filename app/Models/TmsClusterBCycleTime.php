<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsClusterBCycleTime extends Model
{
    use HasFactory;
    protected $table = 'tms_clusterb_cycle_times';

    public function dealership()
    {
        return $this->belongsTo(TmsClientDealership::class,'client_dealership_id');
    }

    public function garage(){
        return $this->belongsTo(TmsGarage::class,'garage_id');
    }

    public function updated_by_emp()
    {
        return $this->belongsTo(Employee::class,'updated_by')->withDefault();

    }

    public function created_by_emp()
    {
        return $this->belongsTo(Employee::class,'created_by')->withDefault();
    }

}
