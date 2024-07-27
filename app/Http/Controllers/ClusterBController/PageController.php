<?php

namespace App\Http\Controllers\ClusterBController;

use App\Http\Controllers\Controller;
use App\Models\TmsRoleAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function setup_page(Request $rq)
    {
        $user_role = Auth::user()->user_roles;
        if(!$user_role->is_active)
        {
            //throw error
        }

        $query = TmsRoleAccess::with('system_file.file_layer')->where([['is_active', 1],['role_id', $user_role->role_id]])->get();
        if(!$query)
        {
            //throw error
        }

        $result = [];
        foreach($query as $data)
        {
            $file_layer = [];
            foreach($data->system_file->file_layer as $row)
            {
                $file_layer[]=[
                    'name'=>$row->name,
                    'href'=>$row->href,
                    'icon'=>$row->icon,
                ];
            }

            $result[]=[
                'name'=>$data->system_file->name,
                'href'=>$data->system_file->href,
                'icon'=>$data->system_file->icon,
                'file_layer'=>$file_layer,
            ];
        }
        return view('cluster_b.'.strtolower($user_role->role->name).'.layout.app', compact('result'));

    }
}
