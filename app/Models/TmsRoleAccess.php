<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsRoleAccess extends Model
{
    use HasFactory;

    protected $table = 'tms_role_access';
    protected $fillable=[
        'role_id',
        'file_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function system_file(){
        return $this->belongsTo(SystemFile::class,'file_id');
    }
}
