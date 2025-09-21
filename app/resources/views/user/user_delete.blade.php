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
    @yield('stylesheet')
</head>
<main class="py-4">
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class='text-center'>アカウント削除</h1>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="container">
                            <form id="profileForm" action="#" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 text-center position-relative" style="width:150px; margin:auto;">
                                    <img id="avatarPreview" src="" 
                                        class="rounded-circle border" 
                                        style="width:150px; height:150px; object-fit:cover; cursor:pointer;">
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">ユーザー名</label><br>
                                    <label for="name" class="form-label">ユーザーID</label><br>
                                    <label for="name" class="form-label">メールアドレス</label>
                                </div>
                                <div class="d-flex justify-content-center mb-3">
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary">戻る</a>
                                </div><br><br><br>
                                <div class="d-flex justify-content-center mb-3">
                                <a href="" class="btn btn-danger">アカウント削除</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
