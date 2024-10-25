<?php

use App\Http\Controllers\ClusterBController\AccessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DispatcherController\Login\Auth as DispatcherLoginController;

Route::get('/', function(){
    return redirect()->route('login.cco_cluster');
})->middleware('prevent.verified.user');

//CCO ACCESS ROUTE remove tms
Route::group(['prefix'=>'tms/'], function() {

    Route::get('/', function(){
        return view('login.cco_cluster');
    })->name('login.cco_cluster')->middleware('prevent.verified.user');

    Route::group(['prefix'=>'cco-b'], function() {
        Route::controller(AccessController::class)->group(function () {

            Route::middleware('prevent.verified.user')->group(function () {

                Route::get('/', 'form')->name('cco-b.form');
                Route::post('/login', 'login')->name('cco-b.login');

            });

            Route::post('/logout', 'logout')->name('cco-b.logout');

        });
    });
});



