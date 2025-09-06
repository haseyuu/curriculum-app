<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }
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
    .content {
        margin-left: 15vw;
        margin-top: 56px;
        padding: 20px;
    }
    .profile-text {
    display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width:60%
    }
    img{
        margin-right:2.5vw;
    }
    .btn{
        white-space: nowrap;
    }
</style>
    @yield('stylesheet')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- 左側：戻る -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    戻る
                </a>

                <!-- 中央：ユーザー名 -->
                <div class="mx-auto">
                    <p class="mb-0 fw-bold">ユーザー名</p>
                </div>

                <!-- 右側（空の要素で中央寄せを維持） -->
                <div style="width: 60px;"></div>
            </div>
        </nav>

        <div class="content">
            <div class="card-body">
                @yield('content')
            </div>
            @for($i=0;$i<5;$i++)
            <div class="card-body">
                <div class="card p-4 mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- アイコン + ユーザー情報 -->
                        <div class="d-flex align-items-start">
                            <!-- ユーザーアイコン -->
                            <img src="https://via.placeholder.com/150"
                                class="rounded-circle border"
                                style="width:80px; height:80px; object-fit:cover;">

                            <!-- ユーザー名 / ID / プロフィール -->
                            <div class="ms-3">
                                <h5 class="mb-1">
                                    ユーザー名 <small class="text-muted">@user_id</small>
                                </h5>
                                <p class="profile-text mb-0">
                                    ここにプロフィール文が入ります。ここにプロフィール文が入ります。ここにプロフィール文が入ります。ここにプロフィール文が入ります。
                                </p>
                            </div>
                        </div>

                        <!-- フォローボタン -->
                        <div>
                            <button class="btn btn-outline-primary btn-sm">　フォロー　</button>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>