<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_no',
        'fname',
        'lname',
        'mname',
        'ext',
        'title',
        'mobile_no',
        'license_no',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by',
        'deleted_at',
    ];

    public function emp_details()
    {
        return $this->hasOne(EmployeePosition::class,'emp_id');
    }

    public function fullname()
    {
        return $this->fname.' '.$this->lname;
    }

    public function cluster_driver()
    {
        return $this->hasOne(TmsClusterDriver::class,'emp_id')->latestOfMany();
    }
}
