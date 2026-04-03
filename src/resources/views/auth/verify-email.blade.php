@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}"/>
@endsection

@section('content')
<div class="email-content">
    <p class="email-message">登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p class="email-message">メール認証を完了してください。</p>
    <div class="email-auth__button">
        <a class="email-auth__button--link" href="http://localhost:8025/#">認証はこちらから</a>
    </div>
    
    

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="email-resend__link" type="submit">認証メールを再送する</button>
    </form>
    @if (session('message'))
        <div class="alert">
            {{ session('message') }}
        </div>
    @endif
</div>
@endsection