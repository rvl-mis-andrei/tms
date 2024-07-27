<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsClientDealership extends Model
{
    use HasFactory;
    protected $fillable=[
        'dealer',
        'location_id',
        'client_id',
        'is_active',
        'created_by',
        'updated_by',
    ];
}
