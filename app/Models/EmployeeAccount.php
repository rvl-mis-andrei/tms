<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class EmployeeAccount extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'emp_id',
        'username',
        'password',
        'is_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'bypass_key',
    ];

    public function user_roles(){
       return $this->hasOne(TmsUserRole::class,'emp_id','emp_id');
    }

    public function employee(){
        return $this->hasOne(Employee ::class,'emp_id','emp_id');
    }

    public function employee_details(){
        return $this->hasOne(EmployeePosition::class,'emp_id','emp_id');
    }

    public function emp_cluster(){
        return $this->hasOne(TmsClusterPersonnel::class,',emp_id');
    }

}
