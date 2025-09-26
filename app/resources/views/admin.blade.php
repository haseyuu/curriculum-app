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
        margin-top: 56px;
        padding: 20px;
    }
</style>
    @yield('stylesheet')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- 左側：戻る -->
                <a class="navbar-brand" href="{{ url('/login') }}">
                    戻る
                </a>

                <!-- 中央：ユーザー名 -->
                <div class="mx-auto">
                    <p class="mb-0 fw-bold">管理者用ユーザー検索画面</p>
                </div>

                <!-- 空要素で中央寄せ -->
                <div style="width: 60px;"></div>
            </div>
        </nav>

        <div class="content">
            <div class="d-flex justify-content-center mb-3">
                <form action="{{ route('admin_search') }}" method="get" class="w-50">
                    <div class="input-group">
                        <input type="text" name="search_word" class="form-control" placeholder="ユーザーIDや日付を入力" value="{{ $word ?? '' }}">
                        <button class="btn btn-secondary" type="submit">検索</button>
                    </div>
                </form>
            </div>

            @if($users)
                @if($users->count()==0)
                    <p class="text-center">検索結果はありません</p>
                @endif

                <table class='table'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ユーザーID</th>
                            <th>ユーザーステータス</th>
                            <th>投稿数</th>
                            <th>削除日付</th>
                            <th>詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->user_id }}</td>
                            <td>{{ $user->state ?? 'ー' }}</td>
                            <td>{{ $user->posts()->count() }}</td>
                            <td>{{ $user->deleted_at ? $user->deleted_at->format('Y-m-d') : '' }}</td>
                            <td><a href="{{ url('/users/' . $user->user_id) }}">詳細</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
                
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>