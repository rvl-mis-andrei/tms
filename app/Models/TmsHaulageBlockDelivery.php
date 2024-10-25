<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsHaulageBlockDelivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'block_id',
        'haulage_id',
        'attendance_id',
        'dr_no',
        'pick_up1',
        'pick_up2',
        'dest1',
        'dest2',
        'dest_type',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public function attendance(){
        return $this->belongsTo(TmsHaulageAttendance::class,'attendance_id');
    }
}
