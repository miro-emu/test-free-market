@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css') }}"/>
@endsection

@section('header')
<form action="/search" class="header-item__serch">
    @csrf
    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="serch-keyword">
    <button type="submit" style="display:none">検索</button> 
</form>
@endsection

@section('content')


@endsection