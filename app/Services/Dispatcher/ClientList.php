<?php

namespace App\Services\Dispatcher;

use App\Models\TmsClient;
use App\Services\DTServerSide;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

class ClientList
{
    public function datatable(Request $rq){
        $data = TmsClient::where('is_active',1)->orderBy('id','ASC')->get();

        $data->transform(function ($item,$key){

            $item->count = $key+1;

            $item->is_active = config('value.is_active.'.$item->is_active);
            $item->name = $item->name;
            $item->encrypt_id = Crypt::encrypt($item->id);

            return $item;
        });

        $table = new DTServerSide($rq, $data);
        $table->renderTable();

        return response()->json([
            'draw' => $table->getDraw(),
            'recordsTotal' => $table->getRecordsTotal(),
            'recordsFiltered' =>  $table->getRecordsFiltered(),
            'data' => $table->getRows()
        ]);
    }

    public function upsert(Request $rq){

    }
}
