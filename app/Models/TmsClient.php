<?php

namespace App\Models;

use App\Services\Dispatcher\ClientDealershipList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmsClient extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function client_dealership()
    {
        return $this->hasMany(ClientDealershipList::class,'client_id');
    }
}
