<?php

use App\Http\Controllers\ClusterBController\Planner\Dashboard;
use App\Services\WebRoute as SystemRoute;
use App\Http\Controllers\ClusterBController\Planner\HaulageInfo;

use App\Http\Controllers\ClusterBController\PlannerPageController;
use Illuminate\Support\Facades\Route;

//remove tms
// Route::group(['prefix'=>'tms/cco-b/planner'], function() {
Route::group(['prefix'=>'cco-b/planner'], function() {

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

        Route::controller(HaulageInfo::class)->prefix('haulage_info')->group(function() {
            Route::post('/tripblock', 'tripblock');
            Route::post('/for_allocation', 'for_allocation');
            Route::post('/add_tripblock', 'add_tripblock');
            Route::post('/remove_tripblock', 'remove_tripblock');
            Route::post('/remove_unit', 'remove_unit');
            Route::post('/add_block_unit', 'add_block_unit');
            Route::post('/update_block_units', 'update_block_units');
            Route::post('/finalize_plan', 'finalize_plan');
            Route::post('/update_transfer', 'update_transfer');
            Route::post('/update_unit_remarks', 'update_unit_remarks');
            Route::post('/reupload_hauling_plan', 'reupload_hauling_plan');
            Route::post('/reupload_masterlist', 'reupload_masterlist');

            Route::post('/create', 'create');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');
            Route::post('/validate','validate');

            Route::post('/masterlist','masterlist');
            Route::post('/hauling_plan','hauling_plan');
            Route::post('/tripblock_list','tripblock_list');

            Route::post('/export_reports', 'export_reports');

            Route::post('/info','info');
        });

        Route::controller(Dashboard::class)->prefix('dashboard')->group(function() {
            Route::post('/','index');
        });

    });
});
