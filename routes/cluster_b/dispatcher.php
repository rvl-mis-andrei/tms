<?php

use App\Services\WebRoute as SystemRoute;

use App\Http\Controllers\ClusterBController\AccessController;
use App\Http\Controllers\ClusterBController\Dispatcher\ClientListing;
use App\Http\Controllers\ClusterBController\Dispatcher\Dashboard;
use App\Http\Controllers\ClusterBController\Dispatcher\DriverListing;
use App\Http\Controllers\ClusterBController\Dispatcher\TractorTrailerDriverListing;
use App\Http\Controllers\ClusterBController\DispatcherPageController;
use App\Services\Dispatcher\ClientList;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'tms/cco-b/dispatcher'], function() {

    Route::middleware('auth')->group(function () {

        Route::controller(DispatcherPageController::class)->group(function () {

            Route::get('/', 'setup_page');
            Route::post('/setup-page', 'setup_page');

            Route::get('/client_info/{id}','system_file');
            Route::get('/tractor_trailer_info/{id}','system_file');

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

    });
});


