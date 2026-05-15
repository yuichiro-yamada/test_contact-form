<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerView()
    {
        return view('auth.register');
    }
    public function store(RegisterRequest $request)
    {
        $userData = [
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> $request->password,
        ];
        User::create($userData);
        return view('auth.login');
    }



    public function loginView()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        return view('admin');
    }
}
