<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsHaulageAttendance extends Model
{
    use HasFactory;
    protected $fillable =[
        'cluster_id',
        'tractor_trailer_id',
        'haulage_id',
        'is_present_pdriver',
        'is_present_sdriver',
        'pdriver',
        'sdriver',
        'remarks',
        'tractor_trailer_status',
        'tractor_id',
        'trailer_id',
        'is_deleted',
        'deleted_by',
        'deleted_at',
        'created_by',
        'updated_by',
        'is_final',
    ];

    public function trailer(){
        return $this->belongsTo(Trailer::class,'trailer_id')->withDefault();
    }

    public function tractor(){
        return $this->belongsTo(Tractor::class,'tractor_id')->withDefault();
    }

    public function sdriver_emp()
    {
        return $this->belongsTo(TmsClusterDriver::class,'sdriver')->withDefault();
    }

    public function pdriver_emp()
    {
        return $this->belongsTo(TmsClusterDriver::class,'pdriver')->withDefault();
    }

    public function updated_by_emp()
    {
        return $this->belongsTo(Employee::class,'updated_by')->withDefault();

    }

    public function created_by_emp()
    {
        return $this->belongsTo(Employee::class,'updated_by')->withDefault();
    }

    public function deleted_by_emp()
    {
        return $this->belongsTo(Employee::class,'deleted_by')->withDefault();
    }

    public function block_delivery(){
        return $this->belongsTo(TmsHaulageBlockDelivery::class,'attendance_id')->withDefault();
    }
}
