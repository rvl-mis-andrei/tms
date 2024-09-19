<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsHaulageBlockUnit extends Model
{
    use HasFactory;
    protected $fillable = [
        'haulage_id',
        'block_id',
        'unit_order',
        'dealer_id',
        'car_model_id',
        'cs_no',
        'color_description',
        'is_allocated',
        'updated_location',
        'invoice_date',
        'inspected_start',
        'status',
        'inspected_end',
        'cs_no',
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
        'deleted_by',
        'is_transfer',
        'is_exported',
    ];

    public function dealer(){
        return $this->belongsTo(TmsClientDealership::class,'dealer_id');
    }

    public function car(){
        return $this->belongsTo(TmsClusterCarModel::class,'car_model_id');
    }

    public function block(){
        return $this->belongsTo(TmsHaulageBlock::class,'block_id','id');
    }
}
