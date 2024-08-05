<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsClusterTractorTrailer extends Model
{
    use HasFactory;

    public function tractor_trailer(){
        return $this->belongsTo(TractorTrailer::class,'tractor_trailer_id');
    }
}
