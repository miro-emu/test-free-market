@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}"/>
@endsection

@section('header')
<form action="/search" class="header-item__serch">
    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="serch-keyword">
    <button type="submit" style="display:none">検索</button> 
</form>
@endsection

@section('content')

<div class="detail-content">
    <div class="detail-item__img">
        <div>
            <img src="{{ Storage::url($item->image) }}" alt="商品画像" class="item-img" />
        </div>
    </div>
    <div class="detail-item__information">

        <p class="item-name">{{$item->name}}</p> 
        <p class="item-brand">{{$item->brand}}</p>
        <p class="item-price">￥<span class="item-price__span">{{$item->price}}</span>(税込)</p>

        <div class="item-icons">
            <div class="item-icons__likes">
                @if($item->is_liked_by_auth_user())
                    <a href="{{ route('item.unlike', ['id' => $item->id]) }}" class="liles-button__like">
                        <img src="{{ asset('/images/ハートロゴ_ピンク.png') }}" alt="いいね_ピンク">
                        <p class="badge">{{ $item->likes->count() }}</p>
                    </a>
                @else
                    <a href="{{ route('item.like', ['id' => $item->id]) }}"  class="liles-button__unlike">
                        <img src="{{ asset('/images/ハートロゴ_デフォルト.png') }}" alt="いいね_デフォ">
                        <p class="badge">{{ $item->likes->count() }}</p></a>
                @endif
            </div>
            <div class="item-icons__comments">
                <img src="{{ asset('/images/ふきだしロゴ.png') }}" alt="ふきだしロゴ">
                <p class="badge">{{ $item->comments->count() }}</p>
            </div>
        </div>

        <div class="item__button">
            <a class="button__purchase" href="/purchase/{{ $item->id }}">購入手続きへ</a>
        </div>

        <h2 class="item__sub-title">商品説明</h2>
        <p class="item-description">{{$item->description}}</p>

        <h2 class="item__sub-title">商品の情報</h2>
        <table>
            <tr>
                <th class="item__table-header">カテゴリー</th>
                @foreach ($item->categories as $category)
                <td class="item__table-category">
                    <div class="item__table-category--span">{{$category->name}}</div>
                </td>
                @endforeach                
            </tr>
            <tr>

                <th class="item__table-header">商品の状態</th>
                <td class="item__table-condition">{{$item->condition->name}}</td>
            </tr>
        </table>

        <h2 class="item-comment__title">コメント<span>({{ $item->comments->count() }})</span></h2>
        @foreach ($item->comments as $comment)
        <div class="comment-user">
            <div class="user-icon__default">
                @if ($comment->user->image)
                <img class="commemt-user-icon" src="{{ Storage::url($comment->user->image) }}" alt="ユーザーアイコン">
                @endif
            </div>
            <p class="comment-user__name">{{ $comment->user->name }}</p>
        </div>
        <p class="comment-content">{{ $comment->content }}</p>
        @endforeach

        <form class="commemt-form" action="/comment/{{ $item->id }}" method="POST">
            @csrf
            <label class="commemt-form__label" for="comment">商品へのコメント</label>
            <textarea class="commemt-form__textarea" name="comment" id="comment"></textarea>
            @error('content')
            <p class="error">{{ $message }}</p>
            @enderror
            <button class="comment-button" type="submit">コメントを送信する</button>
        </form>
    </div>     
</div>
@endsection