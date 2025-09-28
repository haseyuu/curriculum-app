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
                <a class="navbar-brand" href="{{ url()->previous() }}">
                    戻る
                </a>

                <!-- 中央：ユーザー名 -->
                <div class="mx-auto">
                    <p class="mb-0 fw-bold">{{$name}}</p>
                </div>

                <!-- 右側（空の要素で中央寄せを維持） -->
                <div style="width: 60px;"></div>
            </div>
        </nav>

        <div class="content">
            <div class="card-body">
                @yield('content')
            </div>
            @foreach($users as $user)
            <div class="card-body">
                <div class="card p-4 mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- アイコン + ユーザー情報 -->
                        <div class="d-flex align-items-start">
                            <!-- ユーザーアイコン -->
                            <img src="{{ $user->icon ? asset('storage/' . $user->icon) : asset('default\_icon.png') }}" 
                                class="rounded-circle border me-3" 
                                style="width:100px; height:100px; object-fit:cover;margin-right:1vw;">

                            <!-- ユーザー名 / ID / プロフィール -->
                            <div class="ms-3">
                                <a href="{{ url('/users/' . $user->user_id) }}" class="text-decoration-none text-dark">
                                    <h5 class="mb-1">
                                        {{ $user->name }} <small class="text-muted">{{ $user->user_id }}</small>
                                    </h5>
                                </a>
                                <p class="profile-text mb-0">
                                    {{$user->profile}}
                                </p>
                            </div>
                        </div>

                        <!-- フォローボタン -->
                        <div>
                            @if(auth()->id())
                                @if($user->follows->contains(auth()->id()))
                                    <button class="btn btn-outline-primary" id="btn-follow" value='0' data-id="{{ $user->id }}">フォロー解除</button>
                                @elseif(!$user->follows->contains(auth()->id()))
                                    <button class="btn btn-outline-primary" id="btn-follow" value='1' data-id="{{ $user->id }}">フォローする</button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>