<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\SystemFile;
use App\Models\TmsRoleAccess;
use Illuminate\Support\Facades\Log;

class WebRoute
{
    public function getDispatcherRoutes()
    {
        try {
            return Cache::rememberForever('dispatcher_routes', function () {
                $access = TmsRoleAccess::where('role_id',1)->pluck('file_id');
                return SystemFile::with([
                    'file_layer'=>function($q){
                        $q->where('status',1);
                    }
                ])->whereIn('id',$access)->where('status',1)->get();
            });
        } catch (\Exception $e) {
            Log::error('Error retrieving dispatcher routes: ' . $e->getMessage());
            return array();
        }
    }

    public function getPlannerRoutes()
    {
        try {
            return Cache::rememberForever('planner_routes', function () {
                $access = TmsRoleAccess::where('role_id',2)->pluck('file_id');
                return SystemFile::with([
                    'file_layer'=>function($q){
                        $q->where('status',1);
                    }
                ])->whereIn('id',$access)->where('status',1)->get();
            });
        } catch (\Exception $e) {
            Log::error('Error retrieving planner routes: ' . $e->getMessage());
            return array();
        }
    }

    public function getAdminRoutes()
    {
        try {
            return Cache::rememberForever('admin_routes', function () {
                $access = TmsRoleAccess::where('role_id',3)->pluck('file_id');
                return SystemFile::with([
                    'file_layer'=>function($q){
                        $q->where('status',1);
                    }
                ])->whereIn('id',$access)->where('status',1)->get();
            });
        } catch (\Exception $e) {
            Log::error('Error retrieving admin routes: ' . $e->getMessage());
            return array();
        }
    }


    // public function getHRSSRoutes()
    // {
    //     try {
    //         return Cache::rememberForever('system_routes_hrss', function () {
    //             return RoleRoute::where([['status', 1], ['role',2]])->get(['id','url', 'method', 'name']);
    //         });
    //     } catch (\Exception $e) {
    //         Log::error('Error retrieving HRSS routes: ' . $e->getMessage());
    //         return array();
    //     }
    // }

    // public function getGuestRoutes()
    // {
    //     try {
    //         return Cache::rememberForever('system_routes_guest', function () {
    //             return RoleRoute::where([['status', 1], ['role',3]])->get(['id','url', 'method', 'name']);
    //         });
    //     } catch (\Exception $e) {
    //         Log::error('Error retrieving Guest routes: ' . $e->getMessage());
    //         return array();
    //     }
    // }
}

?>
