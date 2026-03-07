@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}"/>
@endsection

@section('header')
<form action="/search" class="header-item__serch">
    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="serch-keyword">
    <button type="submit" style="display:none">検索</button> 
</form>
@endsection

@section('content')
<div class="purchase-content">
    <form action="post">
        <div>
            <img src="{{ Storage::url($item->image) }}" alt="商品画像">
            <p>{{$item->name}}</p>
            <p>￥<span>{{$item->price}}</span></p>
        </div>

        <div>
            <label for="">支払い方法</label>
            <select name="" id="">
                <option disabled value="">選択してください</option>
                <option value="">コンビニ払い</option>
                <option value="">カード支払い</option>
            </select>
        </div>
        
        <div>
            <label for="">配送先</label>
            <a href="/purchase/address/{item_id}">変更する</a>
            <p></p>
        </div>
    
        <table class="purchase-table">
            <tr>
                <th>商品代金</th>
                <td>￥<span>{{$item->price}}</span></td>
            </tr>
            <tr>
                <th>支払い方法</th>
                <td></td>
            </tr>
        </table>
        <button>購入する</button>
    </form>
</div>
@endsection