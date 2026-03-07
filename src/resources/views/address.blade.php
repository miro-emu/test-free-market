@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}"/>
@endsection

@section('header')
<form action="/search" class="header-item__serch">
    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="serch-keyword">
    <button type="submit" style="display:none">検索</button> 
</form>
@endsection

@section('content')
<div class="address-content">
    <h2 class="address-title">住所の変更</h2>
    <form class="address-form" action="/address" method="post">
        <div>
            <label for="">郵便番号</label>
            <input type="text">
        </div>
        <div>
            <label for="">住所</label>
            <input type="text">
        </div>
        <div>
            <label for="">建物名</label>
            <input type="text">
        </div>
        <button>更新する</button>
    </form>
</div>
@endsection