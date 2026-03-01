<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FreeMarket</title>
        <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" >
        <link rel="stylesheet" href="{{ asset('css/header.css') }}" >
        @yield('css')
    </head>

    <body>
        <header class="header">
            <h1 class="header-logo">
                <img src="{{ asset('/images/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECHヘッダーロゴ" class="header-logo__img">
            </h1>
            @yield('header')

            <nav>
                <ul class="header-nav">
                    @if (Auth::check())
                    <li class="header-nav__item">
                        <form action="/logout" method="post">
                        @csrf
                        <button class="header-nav__logout">ログアウト</button>
                        </form>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__mypage" href="/mypage">マイページ</a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__list" href="/mypage">出品</a>
                    </li>
                    @endif
                </ul>
            </nav>

        </header>

        <main>
            @yield('content')
        </main>
    </body>
</html>
