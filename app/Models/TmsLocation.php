<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsLocation extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'is_active',
        'created_by',
        'updated_by',
    ];
}
