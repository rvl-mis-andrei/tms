<?php

use App\Services\DealershipLocation;
use App\Services\Dispatcher\ClientDealershipList;
use App\Services\Dispatcher\ClusterClientList;
use App\Services\ClusterDriverOption;
use App\Services\Dispatcher\ClusterDriverList;
use App\Services\Dispatcher\TractorList;
use App\Services\Dispatcher\TractorTrailerList;
use App\Services\Dispatcher\TrailerList;
use App\Services\TractorOption;
use App\Services\TrailerOption;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'services'], function() {

    Route::controller(ClusterClientList::class)->prefix('client')->group(function() {
        Route::post('/datatable', 'datatable');
        Route::post('/create', 'create');
        Route::post('/update', 'update');
        Route::post('/delete','delete');
        Route::post('/validate','validate');

        Route::post('/info','info');
    });

    Route::controller(ClientDealershipList::class)->prefix('client_dealership')->group(function() {
        Route::post('/datatable', 'datatable');
        Route::post('/create', 'create');
        Route::post('/update', 'update');
        Route::post('/delete','delete');
        Route::post('/validate','validate');

        Route::post('/info','info');
    });


    Route::controller(TractorTrailerList::class)->prefix('tractor_trailer')->group(function() {
        Route::post('/datatable', 'datatable');
        Route::post('/upsert', 'upsert');
        Route::post('/remove', 'remove');
        Route::post('/update_column', 'update_column');
        Route::post('/validate','validate');

        // Route::post('/info','info');
    });

    Route::controller(TractorList::class)->prefix('tractor')->group(function() {
        Route::post('/datatable', 'datatable');
        Route::post('/upsert', 'upsert');
        Route::post('/validate','validate');

        Route::post('/info','info');
    });

    Route::controller(TrailerList::class)->prefix('trailer')->group(function() {
        Route::post('/datatable', 'datatable');
        Route::post('/upsert', 'upsert');
        Route::post('/validate','validate');

        Route::post('/info','info');
    });

    Route::controller(ClusterDriverList::class)->prefix('cluster_driver')->group(function() {
        Route::post('/datatable', 'datatable');
        Route::post('/upsert', 'upsert');
        Route::post('/validate','validate');

        Route::post('/info','info');
    });



    Route::prefix('select')->group(function() {
        Route::post('/location', [DealershipLocation::class, 'list']);
        Route::post('/tractor', [TractorOption::class, 'list']);
        Route::post('/trailer', [TrailerOption::class, 'list']);
        Route::post('/cluster_drivers', [ClusterDriverOption::class, 'list']);

    });

});
