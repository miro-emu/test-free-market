@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}"/>
@endsection

@section('content')
<div class="register-content">
    <h2 class="register-title">会員登録</h2>

    <form action="/register" method="post">
        @csrf
        <div class="register-form__group">
            <label class="register-form__label" for="name">ユーザー名</label>
            <input class="register-form__input" type="text" name="name" id="name">
        </div>
        <div class="register-form__group">
            <label class="register-form__label" for="email">メールアドレス</label>
            <input class="register-form__input" type="email" name="email" id="email">
        </div>
        <div class="register-form__group">
            <labe class="register-form__label" for="password">パスワード</labe>
            <input class="register-form__input" type="text" name="password" id="password">
        </div>
        <div class="register-form__group">
            <label class="register-form__label" for="password_confirmation">パスワード確認用</label>
            <input class="register-form__input" type="text" name="password_confirmation" id="password_confirmation">
        </div>
        <div class="register-form__button">
            <button class="register-form__button-submit" type="submit">登録する</button>
        </div>
        
    </form>
    <a href="/login">ログインはこちら</a>
</div>

@endsection
