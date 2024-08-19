<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsClusterbPlanning extends Model
{
    use HasFactory;
    protected $fillable=[
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
        'model_id',
        'cs_no',
        'color_description',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];
}
