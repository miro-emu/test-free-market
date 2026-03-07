@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}"/>
@endsection

@section('header')
<form action="/search" class="header-item__serch">
    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="serch-keyword">
    <button type="submit" style="display:none">検索</button> 
</form>
@endsection

@section('content')
<div class="edit-content">
    <h2 class="edit-title">プロフィール設定</h2>
    <form class="edit-form" action="/profile/update">
        <div class="edit-item__img">
            <div class="user-image__none">
                @if ($user->image)
                <img class="edit-user-image" src="{{ Storage::url($user->image) }}" alt="プロフィール画像">
                @else
                <img src="/images/default.png">
                @endif
            </div>
            <input type="file" placeholder="画像を選択する">
        </div>
        <div>
            <label for="">ユーザー名</label>
            <input type="text" name="name" value="{{ $user->name }}">
        </div>
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