
<?php

use App\Services\WebRoute as SystemRoute;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController\PageController;


// Route::group(['prefix'=>'admin'], function() {
Route::group(['prefix'=>'tms/admin'], function() {
    Route::middleware('auth')->group(function () {
        Route::controller(PageController::class)->group(function () {

            Route::get('/', 'setup_page');
            Route::post('/setup-page', 'setup_page');

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

