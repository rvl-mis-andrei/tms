<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TractorTrailerDriver extends Model
{
    use HasFactory;

    protected $fillable=[
        'trailer_id',
        'tractor_id',
        'pdriver',
        'sdriver',
        'remarks',
        'status',
        'is_deleted',
        'deleted_at',
        'deleted_by',
        'created_by',
        'cluster_id',
        'updated_by',
    ];

    public function trailer(){
        return $this->belongsTo(Trailer::class,'trailer_id')->withDefault();
    }

    public function tractor(){
        return $this->belongsTo(Tractor::class,'tractor_id')->withDefault();
    }

    public function sdriver_emp()
    {
        return $this->belongsTo(Employee::class,'sdriver')->withDefault();
    }

    public function pdriver_emp()
    {
        return $this->belongsTo(Employee::class,'pdriver')->withDefault();
    }
}
