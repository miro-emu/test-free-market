@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}"/>
@endsection

@section('content')
    
<img src="{{ Storage::url($item->image) }}" alt="商品画像" class="item-img" />
<div>
    <p class="item-name">{{$item->name}}</p> 
    <p class="item-brand">{{$item->brand}}</p>
    <p class="item-price">￥{{$item->price}}(税込)</p>
    <button class="button">購入手続きへ</button>
    <h2>商品説明</h2>
    <p class="item-description">{{$item->description}}</p>
    <h2>商品の情報</h2>
    <table>
        <tr>
            <th>カテゴリー</th>
            <td>サンプル</td>
        </tr>
        <tr>
            <th>商品の状態</th>
            <td>サンプル</td>
        </tr>
    </table>
    <h2>コメント</h2>

</div>     

@endsection