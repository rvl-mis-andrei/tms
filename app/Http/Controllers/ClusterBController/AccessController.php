<?php

namespace App\Http\Controllers\ClusterBController;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{
    public function form()
    {
        return view('cluster_b.login');
    }

    public function login(LoginRequest $rq)
    {
        if(!Auth::attempt($rq->only('username','password')))
        {
            $this->_response('Incorrect username and password',401,'error',csrf_token());
        }

        $user = Auth::user() ?? false;
        $user_role =$user->user_roles;
        if(!$user->employee->is_active || !$user->is_active || !$user_role->is_active)
        {
            Auth::logout();
            $this->_response('Account is Deactivated',401,'error',csrf_token());
        }

        if($user->emp_cluster->cluster_id != 2)
        {
            Auth::logout();
            $this->_response('This Login is for Cluster B only',401,'error',csrf_token());
        }

        $this->_response('Login Success',200,'success','/tms/cco-b/'.strtolower($user_role->role->name).'/dashboard');
    }

    public function logout(Request $rq)
    {
        if(Auth::check())
        {
            Auth::logout();
            return redirect()->route('cco-b.form');
        }
    }
}
