<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsHaulageBlockUnit extends Model
{
    use HasFactory;
    protected $fillable = [
        'block_id',
        'dealer_id',
        'car_model_id',
        'cs_no',
        'color_description',
        'is_allocated',
        'updated_location',
        'invoice_date',
        'inspected_start',
        'inspected_end',
        'cs_no',
        'remarks',
        'hub',
        'planning_cutoff',
        'vld_instruction',
        'vdn_number',
        'vld_planner_confirmation',
        'assigned_lsp',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_at',
        'deleted_by'
    ];
}
