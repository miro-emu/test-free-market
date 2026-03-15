@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}"/>
@endsection

@section('header')
<form action="/search" class="header-item__serch">
    @csrf
    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="serch-keyword">
    <button type="submit" style="display:none">検索</button> 
</form>
@endsection

@section('content')
<div class="address-content">
    <h2 class="address-title">住所の変更</h2>
    <form class="address-form" action="/purchase/address/{{ $item->id }}" method="POST">
        @csrf
        <div class="address-form__group">
            <label class="address-form__label" for="postal_code">郵便番号</label>
            <input class="address-form__input" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}">
            @error('postal_code')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="address-form__group">
            <label class="address-form__label" for="address_line">住所</label>
            <input class="address-form__input" type="text" name="address_line" id="address_line" value="{{ old('address_line') }}">
            @error('address_line')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="address-form__group">
            <label class="address-form__label" for="building">建物名</label>
            <input class="address-form__input" type="text" name="building" id="building" value="{{ old('building') }}">
        </div>
        <button class="address-form__button-submit" type="submit">更新する</button>
    </form>
</div>
@endsection