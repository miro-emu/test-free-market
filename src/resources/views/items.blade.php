@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}"/>
@endsection

@section('header')
<form action="" class="header-item__serch">
    <input type="text" placeholder="なにをお探しですか？" class="serch-keyword">
</form>
@endsection

@section('content')
<div class="top-contents">
    <div class="top-contents__recommend">
        <a claas="recommend-link" href="">おすすめ</a>
    </div>
    <div class="top-contents__mylist">
        <a class="mylist-link" href="">マイリスト</a>
    </div>
</div>

<div class="items-contents">
    @foreach ($items as $item)
    <div class="item-card">
        <a href="{{ url('/item/'.$item->id) }}">
            <img src="{{ Storage::url($item->image) }}" alt="商品画像" class="item-img" />
            <p class="item-name">{{$item->name}}</p>
        </a>               
    </div>
    @endforeach
</div>

@endsection