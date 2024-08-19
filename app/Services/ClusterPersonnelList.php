<?php

namespace App\Services;

use App\Models\TmsClusterClient;
use App\Models\TmsClusterPersonnel;
use App\Models\TmsHaulage;
use App\Services\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ClusterPersonnelList
{

    public function get_personnel($cluster_id)
    {
        try{
            $personnel = TmsClusterPersonnel::where([['cluster_id',$cluster_id],['is_active',1]])->pluck('id');
            if($personnel)
            {
                return $personnel;
            }
            return false;
        }catch(Exception $e){
            return response()->json([ 'status' => 400,  'message' =>  $e->getMessage() ]);
        }
    }

}
