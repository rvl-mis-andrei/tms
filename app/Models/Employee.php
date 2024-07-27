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
    ];

    public function emp_details(){
        return $this->hasOne(EmployeePosition::class);
    }
}
