@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}"/>
@endsection

@section('header')
<form action="/search" class="header-item__serch">
    @csrf
    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="serch-keyword">
    <button type="submit" style="display:none">検索</button> 
</form>
@endsection

@section('content')
<div class="top-contents">
    <a class="recommend-link" href="/">おすすめ</a>
    <a class="mylist-link" href="/?tab=mylist">マイリスト</a>
</div>

<div class="items-contents">
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