<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '家計簿') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
   /* 固定ヘッダー */
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }
    /* サイドバー */
    .sidebar {
        width: 15vw;
        height: 100vh;
        position: fixed;
        top: 56px;
        left: 0;
        background-color: #f8f9fa;
        border-right: 1px solid #dee2e6;
        padding: 20px 10px;
    }
    /* メインコンテンツ */
    .content {
        margin-left: 15vw;
        margin-top: 56px;
        padding: 20px;
    }
</style>
    @yield('stylesheet')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand mx-auto" href="{{ url('/') }}">
                    test
                </a>
            </div>
        </nav>
       <div id="app">
        <!-- ✅ サイドバー -->
        <div class="sidebar">
            <h4 class="text-center">メニュー</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="{{ url('/mypage') }}" class="nav-link">ホーム</a></li>
                <li class="nav-item"><a href="{{ url('/post') }}" class="nav-link">投稿</a></li>
                <li class="nav-item"><a href="{{ url('/search') }}" class="nav-link">検索</a></li>
                <li class="nav-item"><a href="{{ url('/logout') }}" class="nav-link">ログアウト</a></li>
            </ul>
        </div>

        <!-- ✅ メインコンテンツ -->
        <div class="content">
            <div class="card-body">
                @yield('content')
            </div>
            @for($i=0;$i<5;$i++)
            <div class="card-body">
                test
            </div>
            @endfor
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>