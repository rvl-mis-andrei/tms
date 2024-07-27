<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id', 'name', 'description', 'created_by', 'updated_by',
    ];
}
