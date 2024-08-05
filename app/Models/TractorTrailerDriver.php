<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TractorTrailerDriver extends Model
{
    use HasFactory;

    public function trailer(){
        return $this->belongsTo(Trailer::class,'trailer_id');
    }

    public function tractor(){
        return $this->belongsTo(Tractor::class,'tractor_id');
    }

    public function sdriver_emp()
    {
        return $this->belongsTo(Employee::class,'sdriver');
    }

    public function pdriver_emp()
    {
        return $this->belongsTo(Employee::class,'pdriver');
    }
}
