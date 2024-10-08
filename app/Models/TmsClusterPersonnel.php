<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsClusterPersonnel extends Model
{
    use HasFactory;

    public function cluster(){
        return $this->belongsTo(TmsCluster::class,'cluster_id');
    }

    
}


