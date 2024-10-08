<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trailer extends Model
{
    use HasFactory;

    public function trailer_type()
    {
        return $this->belongsTo(TrailerType::class,'trailer_type_id')->withDefault();
    }

    public function tractor_trailer()
    {
        return $this->hasOne(TractorTrailerDriver::class,'trailer_id')->latestOfMany();
    }
}
