<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\SystemFile;
use App\Models\TmsRoleAccess;
use App\Services\Admin\AdminPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function system_file(Request $rq)
    {
        $user_role = Auth::user()->user_roles;
        if(!$user_role->is_active)
        {
            //throw error
        }

        $query = TmsRoleAccess::with('system_file.file_layer')
        ->where([['is_active', 1],['role_id', $user_role->role_id]])->orderBy('file_order')->get();
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
        return view('layout.admin.app',compact('result'));

    }

    public function setup_page(Request $rq)
    {
        $page = new AdminPage;
        $rq->session()->put("admin_page",$rq->page);
        $view = $rq->session()->get("admin_page", "dashboard");
        $role    = strtolower(Auth::user()->user_roles->role->name);

        switch($view){

            default :
                $row = SystemFile::with(["file_layer" => function($q) use($view) {
                    $q->where([["status", 1], ["href", $view]]);
                }])
                ->where(function($query) use($view) {
                    $query->where([["status", 1], ["href", $view]])
                    ->orWhereHas("file_layer", function ($q) use($view) {
                        $q->where([["status", 1], ["href", $view]]);
                    });
                })
                ->first();
                if (!$row) { return view("admin.not_found"); }
                $folders = !$row->file_layer->isEmpty()? $row->folder.'.'.$row->file_layer[0]->folder :$row->folder;
                $file    = $row->file_layer[0]->href??$row->href;
                return response([ 'page' => view("admin.$folders.$file")->render() ],200);
            break;
        };
    }
}
