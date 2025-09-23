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
                            @csrf
                            <div class="mb-3 text-center position-relative" style="width:150px; margin:auto;">
                                <img id="avatarPreview" src="{{ auth()->user()->icon ? asset('storage/' . auth()->user()->icon) : asset('default\_icon.png') }}" 
                                    class="rounded-circle border" 
                                    style="width:150px; height:150px; object-fit:cover; cursor:pointer;">
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ auth()->user()->name }}</label><br>
                                <label for="name" class="form-label">{{ auth()->user()->user_id }}</label><br>
                                <label for="name" class="form-label">{{ auth()->user()->email }}</label>
                            </div>
                            <div class="d-flex justify-content-center mb-3">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">戻る</a>
                            </div><br><br><br>
                            <div class="d-flex justify-content-center mb-3">
                            <button type="button" class="btn btn-sm btn-danger btn-delete-user" data-id="{{ auth()->user()->user_id }}">アカウント削除</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
