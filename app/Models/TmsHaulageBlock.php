<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsHaulageBlock extends Model
{
    use HasFactory;
    protected $fillable = [
        'haulage_id',
        'block_number',
        'batch',
        'no_of_trips',
        'tractor_id',
        'trailer_id',
        'pdriver',
        'status',
        'sdriver',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];


    public function block_unit()
    {
        return $this->hasMany(TmsHaulageBlockUnit::class,'block_id');
    }

    public function tractor()
    {
        return $this->belongsTo(Tractor::class,'tractor_id');
    }

    public function trailer()
    {
        return $this->belongsTo(Trailer::class,'trailer_id');
    }

    public function created_by_emp()
    {
        return $this->belongsTo(Employee::class,'created_by');
    }

    public function deleted_by_emp()
    {
        return $this->belongsTo(Employee::class,'deleted_by');
    }

    public function updated_by_emp()
    {
        return $this->belongsTo(Employee::class,'updated_by');
    }
}
