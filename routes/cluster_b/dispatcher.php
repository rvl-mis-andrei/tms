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

    Route::middleware('prevent.verified.user')->controller(AccessController::class)->group(function () {
        Route::get('/', 'form')->name('cco-b.dispatcher.form');
        Route::post('/login', 'login')->name('cco-b.dispatcher.login');
        Route::post('/logout', 'logout')->name('cco-b.dispatcher.logout');
    });

    Route::middleware('auth')->group(function () {

        Route::controller(PageController::class)->group(function () {
            $routes = (new SystemRoute())->getDispatcherRoutes();
            if ($routes) {
                foreach ($routes as $row) {
                    Route::get('/'.$row->href,'setup_page');
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


