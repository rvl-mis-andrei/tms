<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemFile extends Model
{
    use HasFactory;

    public function file_layer(){
        return $this->hasMany(SystemFileLayer::class,'file_id');
    }
}
