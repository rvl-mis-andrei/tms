<?php

use App\Services\Dispatcher\ClientDealershipList;
use App\Services\Dispatcher\ClientList;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'services'], function() {

    Route::controller(ClientList::class)->prefix('client')->group(function() {
        Route::post('/datatable', 'datatable');
        Route::post('/create', 'create');
        Route::post('/update', 'update');
        Route::post('/delete','delete');
        Route::post('/validate','validate');

        Route::post('/info','info');
    });

    Route::controller(ClientDealershipList::class)->prefix('client_dealership')->group(function() {
        Route::post('/datatable', 'datatable');
        Route::post('/upsert', 'upsert');
        Route::post('/delete','delete');
        Route::post('/validate','validate');

        Route::post('/info','info');
    });

});
