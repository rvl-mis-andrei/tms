<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsClusterClient extends Model
{
    use HasFactory;
    protected $fillable=[
        'cluster_id',
        'client_id',
        'status',
        'created_by',
        'updated_by',
    ];
}
