<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function form(Request $rq){
        return view('login.admin');

    }

    public function login(Request $rq){

    }

    public function logout(Request $rq){

    }
}
