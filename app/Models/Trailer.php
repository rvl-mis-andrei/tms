<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trailer extends Model
{
    use HasFactory;
    protected $fillable = [
        'cluster_id',
        'name',
        'plate_no',
        'description',
        'trailer_type_id',
        'status',
        'remarks',
        'is_deleted',
        'deleted_by',
        'deleted_at',
        'created_by',
        'updated_by'
    ];

    public function trailer_type()
    {
        return $this->belongsTo(TrailerType::class,'trailer_type_id')->withDefault();
    }

    public function tractor_trailer()
    {
        return $this->hasOne(TractorTrailerDriver::class,'trailer_id')->latestOfMany();
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
