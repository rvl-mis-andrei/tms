<?php

use App\Http\Controllers\ClusterBController\AccessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DispatcherController\Login\Auth as DispatcherLoginController;

//CCO-B ACCESS ROUTE remove tms
Route::group(['prefix'=>'tms/cco-b'], function() {
// Route::group(['prefix'=>'cco-b'], function() {

    Route::controller(AccessController::class)->group(function () {

        Route::middleware('prevent.verified.user')->group(function () {

            Route::get('/', 'form')->name('cco-b.form');
            Route::post('/login', 'login')->name('cco-b.login');

        });

        Route::post('/logout', 'logout')->name('cco-b.logout');

    });

});



