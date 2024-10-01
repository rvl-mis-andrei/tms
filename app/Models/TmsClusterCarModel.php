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
        'is_active'
    ];
}
