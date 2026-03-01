<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{   
    public function redirectPath()
    {
        return 'profiel';
    }
    
// ログイン画面
    public function login(){
        return view('auth.login');
    }


}
