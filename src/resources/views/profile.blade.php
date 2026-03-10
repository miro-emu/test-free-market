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

<div class="user-image">
    @if ($user->image)
    <img class="edit-user-image" src="{{ Storage::url($user->image) }}" alt="プロフィール画像">
    @else
    <img class="edit-user-image__default" src="/images/default.png">
    @endif
</div>
<p>{{ $user->name }}</p>
<div>
    <a class="button__edit" href="/mypage/profile">プロフィールを編集</a>
</div>
<div>
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