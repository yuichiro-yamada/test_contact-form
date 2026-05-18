@extends('layouts.authapp')

@section('css')
<link rel="stylesheet" href="{{asset('css/register.css')}}">
@endsection

@section('header')
<a href="/login" class="toLogin">Login</a>
@endsection

@section('content')
<h1>Register</h1>
<div class="register-form">
    <form action="/register" method="post" novalidate>
        @csrf
        <div class="register-info">
            <p>お名前</p>
            <input class="input-box" name="name" type="text" placeholder="例: 山田 太郎" value="{{old('name')}}">
            <div class="error-message">
                @error('name')
                {{$message}}
                @enderror
            </div>
        </div>
        <div class="register-info">
            <p>メールアドレス</p>
            <input class="input-box" name="email" type="email" placeholder="例: test@exapmple.com" value="{{old('email')}}">
            <div class="error-message">
                @error('email')
                {{$message}}
                @enderror
            </div>
        </div>
        <div class="register-info">
            <p>パスワード</p>
            <input name="password" type="password" placeholder="例: coachtech1106">
            <div class="error-message">
                @error('password')
                {{$message}}
                @enderror
            </div>
        </div>
        <div class="register-button">
            <button type="submit">登録</button>
        </div>
    </form>
</div>

@endsection