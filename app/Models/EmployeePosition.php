<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePosition extends Model
{
    use HasFactory;
    protected $fillable = [
        'position_id', 'department_id', 'company_id', 'company_location_id', 'created_by', 'updated_by',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class,'position_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function company_location()
    {
        return $this->belongsTo(CompanyLocation::class,'company_location_id');
    }
}
