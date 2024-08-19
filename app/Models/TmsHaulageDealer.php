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
        'invoice_date',
        'invoice_time',
        'planning_cutoff',
        'vld_instruction',
        'updated_location',
        'vdn_number',
        'hub',
        'remarks',
        'vld_planner_confirmation',
        'assigned_lsp',
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
