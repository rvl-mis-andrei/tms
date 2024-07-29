<?php

use App\Services\WebRoute as SystemRoute;

use App\Http\Controllers\ClusterBController\AccessController;
use App\Http\Controllers\ClusterBController\Dispatcher\ClientListing;
use App\Http\Controllers\ClusterBController\Dispatcher\Dashboard;
use App\Http\Controllers\ClusterBController\Dispatcher\DriverListing;
use App\Http\Controllers\ClusterBController\Dispatcher\TractorTrailerDriverListing;
use App\Http\Controllers\ClusterBController\PageController;

use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'tms/cco-b/dispatcher'], function() {

    Route::middleware('auth')->group(function () {

        Route::controller(PageController::class)->group(function () {

            Route::post('/setup-page', 'setup_page');

            $routes = (new SystemRoute())->getDispatcherRoutes();
            if ($routes) {
                foreach ($routes as $row) {
                    if (!$row->file_layer->isEmpty()) {
                        foreach ($row->file_layer as $layer) {
                            Route::get('/'.$layer->href,'system_file');
                        }
                    }else{
                        Route::get('/'.$row->href,'system_file');
                    }
                }
            }
        });

        // Route::controller(ClientListing::class)->group(function () {
        //     Route::get('/dashboard', 'index');
        //     Route::get('/show', 'show');
        //     Route::get('/upsert', 'upsert');
        //     Route::get('/delete', 'delete');
        // });

        // Route::controller(DriverListing::class)->group(function () {
        //     Route::get('/dashboard', 'index');
        //     Route::get('/show', 'show');
        //     Route::get('/upsert', 'upsert');
        //     Route::get('/delete', 'delete');
        // });

        // Route::controller(TractorTrailerDriverListing::class)->group(function () {
        //     Route::get('/dashboard', 'index');
        //     Route::get('/show', 'show');
        //     Route::get('/upsert', 'upsert');
        //     Route::get('/delete', 'delete');
        // });

    });

});


