<?php

use App\Services\Dispatcher\ClientDealershipList;
use App\Services\Dispatcher\ClientList;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'services'], function() {

    Route::controller(ClientList::class)->prefix('client_list')->group(function() {
        Route::post('/datatable', 'datatable');
        Route::post('/upsert', 'upsert');
    });

    Route::controller(ClientDealershipList::class)->prefix('client_dealership_list')->group(function() {
        Route::post('/datatable', 'datatable');
        Route::post('/upsert', 'upsert');
    });

});
