@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}"/>
@endsection

@section('content')
<div class="register-content">
    <h2 class="register-title">会員登録</h2>

    <form action="/register" method="post" @submit.prevent novalidate>
        @csrf
        <div class="register-form__group">
            <label class="register-form__label" for="name">ユーザー名</label>
            <input class="register-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
            @error('name')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="register-form__group">
            <label class="register-form__label" for="email">メールアドレス</label>
            <input class="register-form__input" type="email" name="email" id="email" value="{{ old('email') }}">
            @error('email')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="register-form__group">
            <labe class="register-form__label" for="password">パスワード</labe>
            <input class="register-form__input" type="password" name="password" id="password">
            @error('password')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="register-form__group">
            <label class="register-form__label" for="password_confirmation">パスワード確認用</label>
            <input class="register-form__input" type="password" name="password_confirmation" id="password_confirmation">
            @error('password')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="register-form__button">
            <button class="register-form__button-submit" type="submit">登録する</button>
        </div>
        
    </form>
    <div class="register-item__link">
        <a class="login-link" href="/login">ログインはこちら</a>
    </div>
    
</div>

@endsection
