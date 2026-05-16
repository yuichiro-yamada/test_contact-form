<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            'password'=> Hash::make($request->password),
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
        // 1. 画面から送られてきたメールアドレスとパスワードを取得
        $credentials = $request->only('email', 'password');

        // 2. ログイン（認証）を試みる
        if (Auth::attempt($credentials)) {
            // ログイン成功：セッションを新しくしてセキュリティを高める
            $request->session()->regenerate();

            // 💡 修正ポイント：直接 view() を返さず、管理画面のURLに「リダイレクト」する
            // これにより、ContactController の admin メソッドが正しく走り、必要なデータが画面に渡されます
            return redirect()->intended('/admin');
        }

        // 3. ログイン失敗：エラーメッセージを伴ってログイン画面に戻す
        return back()->withErrors([
            'login_error' => 'メールアドレスまたはパスワードが正しくありません。',
        ])->onlyInput('email');
    }
}
