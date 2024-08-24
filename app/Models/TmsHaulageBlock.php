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
}
