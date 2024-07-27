<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'is_active', 'created_by', 'updated_by',
    ];

    public function company_location(){
        return $this->hasMany(CompanyLocation::class,'company_id');
     }
}
