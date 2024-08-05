<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsClientDealership extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'code',
        'location_id',
        'client_id',
        'is_active',
        'is_deleted',
        'deleted_by',
        'deleted_at',
        'pv_lead_time',
        'created_by',
        'updated_by',
    ];

    public function location()
    {
        return $this->belongsTo(TmsLocation::class,'location_id');
    }
}
