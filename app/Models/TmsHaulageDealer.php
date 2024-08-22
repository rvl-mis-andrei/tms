<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsHaulageDealer extends Model
{
    use HasFactory;
    protected $fillable = [
        'cluster_id',
        'haulage_id',
        'dealer_id',
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


    public function haulage_unit()
    {
        return $this->hasMany(TmsHaulageUnit::class,'haulage_dealers_id');
    }
}
