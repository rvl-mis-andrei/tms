<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsClusterClient extends Model
{
    use HasFactory;
    protected $fillable=[
        'cluster_id',
        'name',
        'description',
        'is_active',
        'is_deleted',
        'deleted_at',
        'deleted_by',
        'deleted_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'date:M d Y',
    ];

    public function client_dealership()
    {
        return $this->hasMany(TmsClientDealership::class,'client_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'created_by');
    }
}
