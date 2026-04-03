@extends('composents/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}"/>
@endsection

@section('header')
<form action="/search" class="header-item__serch">
    @csrf
    <input type="text" name="keyword" placeholder="なにをお探しですか？" class="serch-keyword">
    <button type="submit" style="display:none">検索</button> 
</form>
@endsection

@section('content')
<div class="edit-content">
    <h2 class="edit-title">プロフィール設定</h2>
    <form class="edit-form" action="/profile/update" method="post" enctype="multipart/form-data">
        @csrf
        <div class="edit-item__img">
            <div class="user-image">
                @if ($user->image)
                <img class="edit-user-image" id="image-preview" src="{{ Storage::url($user->image) }}" alt="プロフィール画像">
                @else
                <img class="edit-user-image__default" id="image-preview" src="/images/default.png">
                @endif
            </div>
            <div class="img-upload__group">
                <label class="img-upload__button" for="file_upload">
                画像を選択する
                <input class="img-upload" type="file" name="image" id="file_upload">
                </label>
                @error('image')
                <p class="error-image">{{ $message }}</p>
                @enderror
            </div>
            
        </div>
        <div class="edit-form__group">
            <label class="edit-form__label" for="">ユーザー名</label>
            <input class="edit-form__input" type="text" name="name" value="{{ $user->name }}">
            @error('name')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="edit-form__group">
            <label class="edit-form__label" for="">郵便番号</label>
            <input class="edit-form__input" type="text" name="postal_code" value="{{ $address->postal_code }}">
            @error('postal_code')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="edit-form__group">
            <label class="edit-form__label" for="">住所</label>
            <input class="edit-form__input" type="text" name="address_line" value="{{ $address->address_line }}">
            @error('address_line')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="edit-form__group">
            <label class="edit-form__label" for="">建物名</label>
            <input class="edit-form__input" type="text" name="building" value="{{ $address->building }}">
        </div>
        <button class="edit-form__button" type="submit">更新する</button>
    </form>
</div>

<script>
document.getElementById('file_upload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');

    if (file) {
        const reader = new FileReader();

        reader.onload = function(event) {
            preview.src = event.target.result;
        }

        reader.readAsDataURL(file);
    }
});
</script>
@endsection