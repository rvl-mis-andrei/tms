<?php

use App\Services\WebRoute as SystemRoute;

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

    });
});
