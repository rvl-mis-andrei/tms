<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsHaulage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'remarks',
        'status',
        'planning_date',
        'cluster_id',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];

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