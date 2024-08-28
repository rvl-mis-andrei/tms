<?php

use App\Services\WebRoute as SystemRoute;
use App\Http\Controllers\ClusterBController\Planner\ClusterBHaulageInfo;

use App\Http\Controllers\ClusterBController\PlannerPageController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'tms/cco-b/planner'], function() {

    Route::middleware('auth')->group(function () {

        Route::controller(PlannerPageController::class)->group(function () {

            Route::get('/', 'setup_page');
            Route::post('/setup-page', 'setup_page');

            Route::get('/hauling_plan_info/{id}', 'system_file');

            $routes = (new SystemRoute())->getPlannerRoutes();
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

        Route::controller(ClusterBHaulageInfo::class)->prefix('haulage_info')->group(function() {
            Route::post('/tripblock', 'tripblock');
            Route::post('/for_allocation', 'for_allocation');
            Route::post('/add_tripblock', 'add_tripblock');
            Route::post('/remove_tripblock', 'remove_tripblock');
            Route::post('/create', 'create');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');
            Route::post('/validate','validate');

            Route::post('/masterlist','masterlist');
            Route::post('/hauling_plan','hauling_plan');

            Route::post('/info','info');
        });

    });
});
