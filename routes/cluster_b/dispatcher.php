<?php

use App\Services\WebRoute as SystemRoute;

use App\Http\Controllers\ClusterBController\AccessController;
use App\Http\Controllers\ClusterBController\Dispatcher\ClientListing;
use App\Http\Controllers\ClusterBController\Dispatcher\Dashboard;
use App\Http\Controllers\ClusterBController\Dispatcher\DriverListing;
use App\Http\Controllers\ClusterBController\Dispatcher\HaulageAttendance;
use App\Http\Controllers\ClusterBController\Dispatcher\HaulageInfo;
use App\Http\Controllers\ClusterBController\Dispatcher\TractorTrailerDriver;
use App\Http\Controllers\ClusterBController\Dispatcher\TractorTrailerDriverListing;
use App\Http\Controllers\ClusterBController\DispatcherPageController;
use App\Services\Dispatcher\ClientList;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'cco-b/dispatcher'], function() {
    // Route::group(['prefix'=>'tms/cco-b/dispatcher'], function() {

    Route::middleware('auth')->group(function () {

        Route::controller(DispatcherPageController::class)->group(function () {

            Route::get('/', 'setup_page');
            Route::post('/setup-page', 'setup_page');

            Route::get('/client_info/{id}','system_file');
            Route::get('/tractor_trailer_info/{id}','system_file');
            Route::get('/hauling_plan_info/{id}', 'system_file');

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

        Route::controller(HaulageInfo::class)->prefix('haulage_info')->group(function() {
            Route::post('/tripblock', 'tripblock');
        });

        Route::controller(HaulageAttendance::class)->prefix('haulage_attendance')->group(function() {
            Route::post('/create_attendance', 'create_attendance');
            Route::post('/update_attendance', 'update_attendance');
            Route::post('/update_tractor_trailer_att', 'update_tractor_trailer_att');
        });

        Route::controller(TractorTrailerDriver::class)->prefix('tractor_trailer_driver')->group(function() {
            Route::post('/dt', 'dt');
        });

    });
});


