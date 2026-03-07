<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // プロフィール編集画面
    public function edit()
    {
        $user = Auth::user();

        return view('edit', compact('user'));
    }

    public function updateProfile()
    {

    }

}
