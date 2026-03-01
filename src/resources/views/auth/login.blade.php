@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}"/>
@endsection

@section('content')
<div class="login-content">
    <h2 class="login-title">ログイン</h2>

    <form action="/login" method="post">
        @csrf
        <div class="login-form__group">
            <label class="login-form__label" for="email">メールアドレス</label>
            <input class="login-form__input" type="email" name="email" id="email">
        </div>
        <div class="login-form__group">
            <labe class="login-form__label" for="password">パスワード</labe>
            <input class="login-form__input" type="text" name="password" id="password">
        </div>
        <div class="login-form__button">
            <button class="login-form__button-submit" type="submit">ログインする</button>
        </div>
    </form>

    <div class="login-item__link">
        <a class="register-link" href="/register">会員登録はこちら</a>
    </div>
    
</div>

@endsection
