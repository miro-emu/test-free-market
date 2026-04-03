@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}"/>
@endsection

@section('header')
<form action="/search" class="header-item__serch">
    @csrf
    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="serch-keyword">
    <button type="submit" style="display:none">検索</button> 
</form>
@endsection

@section('content')
<div class="sell-content">
    <h2 class="sell-title">商品の出品</h2>
    <form action="/sell" method="post" enctype="multipart/form-data">
        @csrf
        <p class="item-content__header">商品画像</p>
        <div class="item-image__area">
            <label class="item-image__button" for="image">
                画像を選択する
                <input class="item-image" type="file" name="image" id="image">
            </label>
            @error('image')
                <p class="error-image">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <h3 class="item-detail">商品の詳細</h3>
            <div clss="form-group">
                <p class="item-content__header">カテゴリー</p>
                @foreach($categories as $category)
                    <label class="item-category" for="category-{{ $category->name }}">
                        <input type="checkbox" name="category_ids[]" id="category-{{ $category->name }}"  value="{{ $category->id }}"{{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                        <span class="item-category__button" >{{ $category->name }}</span>
                    </label>
                @endforeach
                @error('category_ids')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>         
            <div class="form-group">
                <label class="item-content__header" for="condition">商品の状態</label>
                <select class="item-condition__select" id="condition" name="condition_id">
                    <option disabled selected value="">選択してください</option>
                    @foreach($conditions as $condition)
                    <option value="{{ $condition['id'] }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>{{ $condition['name'] }}</option>
                    @endforeach
                </select> 
                @error('condition_id')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="">
            <h3 class="item-detail">商品名と説明</h3>
            <div clss="form-group">
                <label class="item-content__header" for="name">商品名</label>
                <input class="item-content__name" type="text" name="name" id="name">
                @error('name')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div clss="form-group">
                <label class="item-content__header" for="brand">ブランド名</label>
                <input class="item-content__brand" type="text" name="brand" id="brand">
            </div>
            <div clss="form-group">
                <label class="item-content__header" for="description">商品の説明</label>
                <textarea class="item-content__description" name="description" id="description"></textarea>
                @error('description')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div clss="form-group">
                <label class="item-content__header" for="price">販売価格</label>
                <span class="yen-icon">
                    <img class="yen-icon__img" src="images/円マークアイコン.png" alt="円マーク">
                </span>
                <input class="item-content__price" type="text" name="price" id="price">
                @error('price')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <button class="sell-button" type="submit">出品する</button>
    </form>
</div>
@endsection