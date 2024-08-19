<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsHaulageCollaborator extends Model
{
    use HasFactory;
    protected $fillable = [
        'haulage_id',
        'cluster_personnel_id',
        'status',
        'is_deleted',
        'deleted_at',
        'deleted_by',
        'deleted_by',
        'created_by',
        'updated_by',
    ];
}
