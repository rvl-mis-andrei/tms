<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsHaulageUnit extends Model
{
    use HasFactory;
    protected $fillable = [
        'haulage_dealers_id',
        'model_id',
        'cs_no',
        'color_description',
        'is_allocated',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_at',
        'deleted_by'
    ];
}
