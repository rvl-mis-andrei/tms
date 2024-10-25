<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsClusterCarModel extends Model
{
    use HasFactory;

    protected $fillable=[
        'cluster_id',
        'car_model',
        'color_description',
        'is_active',
        'short_name',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    public function updated_by_emp()
    {
        return $this->belongsTo(Employee::class,'updated_by')->withDefault();

    }

    public function created_by_emp()
    {
        return $this->belongsTo(Employee::class,'created_by')->withDefault();
    }

}
