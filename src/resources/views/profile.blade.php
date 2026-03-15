@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}"/>
@endsection

@section('header')
<form action="/search" class="header-item__serch">
    @csrf
    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="serch-keyword">
    <button type="submit" style="display:none">検索</button> 
</form>
@endsection

@section('content')
<div class="profile-content__top">
    <div class="user-image">
        @if ($user->image)
        <img class="edit-user-image" src="{{ Storage::url($user->image) }}" alt="プロフィール画像">
        @else
        <img class="edit-user-image__default" src="/images/default.png">
        @endif
    </div>
    <p class="top-item__user-name">{{ $user->name }}</p>
    <div class="top-item__button">
        <a class="button__edit" href="/mypage/profile">プロフィールを編集</a>
    </div>
</div>

<div class="profile-content__tub">
    <a class="sell-link {{ request('page') == 'sell' ? 'active' : '' }}" href="/mypage?page=sell">出品した商品</a>
    <a class="buy-link {{ request('page') == 'buy' ? 'active' : '' }}" href="/mypage?page=buy">購入した商品</a>
</div>

<div class="profile-content__items">
    @foreach ($items as $item)
    <div class="item-card">
        <a href="{{ url('/item/'.$item->id) }}" class="item-link">
            <img src="{{ Storage::url($item->image) }}" alt="商品画像" class="item-img" />
            <p class="item-name">{{$item->name}}</p>
        </a>               
    </div>
    @endforeach
</div>
@endsection