<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tractor extends Model
{
    use HasFactory;
    protected $fillable=[
        'plate_no',
        'body_no',
        'description',
        'cluster_id',
        'name',
        'remarks',
        'status',
        'is_deleted',
        'deleted_by',
        'deleted_at',
        'created_by',
        'updated_by'
    ];

    public function tractor_trailer()
    {
        return $this->hasOne(TractorTrailerDriver::class,'tractor_id')->latestOfMany();
    }

    public function updated_by_emp()
    {
        return $this->belongsTo(Employee::class,'updated_by')->withDefault();

    }

    public function created_by_emp()
    {
        return $this->belongsTo(Employee::class,'created_by')->withDefault();
    }

    public function deleted_by_emp()
    {
        return $this->belongsTo(Employee::class,'deleted_by');
    }
}
