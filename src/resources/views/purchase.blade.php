@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}"/>
@endsection

@section('header')
<form action="/search" class="header-item__serch">
    @csrf
    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="serch-keyword">
    <button type="submit" style="display:none">検索</button> 
</form>
@endsection

@section('content')
<form class="purchase-content" action="/purchase/{{ $item->id }}" method="POST">
    @csrf 
    <div class="purcahe-content__left">
        <div class="purchase-item">
            <img class="purchase-item__img" src="{{ Storage::url($item->image) }}" alt="商品画像">
            <div class="purchase-item__info">
                <p class="purchase-ite__name">{{$item->name}}</p>
                <p class="purchase-item__price"><span class="purchase-item__price--sapi_windows_cp_conv">￥</span>{{$item->price_formatted}}</p>
            </div>
        </div>

        <div class="purchase-order">
            <label class="purchase-order__label" for="payment">支払い方法</label>
            <select class="purchase-order__payment" name="payment_method" id="payment">
                <option disabled selected>選択してください</option>
                <option class="purchase-order__payment--option" value="convenience">コンビニ払い</option>
                <option class="purchase-order__payment--option" value="card">カード支払い</option>
            </select>
        </div>
        
        <div class="purchase-order">
            <label class="purchase-order__label">配送先</label>
            <a class="purchase-order__address--link" href="/purchase/address/{{ $item->id }}">変更する</a>
            <div class="purchase-order__address">
                <p>〒{{$address->postal_code}}</p>
                <p>{{$address->address_line}}{{$address->building}}</p>
            </div>
        </div>
    </div>

    <div class="purcahe-content__right">
        <table class="purchase-table">
            <tr class="purchase-table__price">
                <th class="purchase-table__header">商品代金</th>
                <td class="purchase-table__content">￥{{$item->price_formatted}}</td>
            </tr>
            <tr>
                <th class="purchase-table__header">支払い方法</th>
                <td class="purchase-table__content" id="payment-method">サンプル</td>
            </tr>
        </table>
        <button class="purchase-form__button" type="submit">購入する</button>
    </div>
 </form>

    <!-- document.getElementById('payment').addEventListener('change', function(){
    document.getElementById('payment-method').innerText = this.options[this.selectedIndex].text;
}); -->
@endsection