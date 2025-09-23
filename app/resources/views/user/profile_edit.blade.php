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
                    <h4 class='text-center'>プロフィール編集</h1>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class ='panel-body'>
                            @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $message)
                                <p>{{ $message }}</p>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="container">
                            <form id="profileForm" action="{{route('profile_edit',$user->user_id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3 text-center position-relative" style="width:150px; margin:auto;">
                                    <img id="avatarPreview" src="{{ $user->icon ? asset('storage/' . $user->icon) : asset('default\_icon.png') }}" 
                                        class="rounded-circle border" 
                                        style="width:150px; height:150px; object-fit:cover; cursor:pointer;">
                                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">

                                    <button type="button" id="cropDoneBtn" class="btn btn-success btn-sm" 
                                            style="position:absolute; bottom:-35px; left:50%; transform:translateX(-50%); display:none;">
                                        完了
                                    </button>
                                </div>

                                <!-- ユーザー情報 -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">ユーザー名</label>
                                    <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control">

                                    <label for="user_id" class="form-label">ユーザーID</label>
                                    <input type="text" name="user_id" id="user_id" value="{{ $user->user_id }}" class="form-control">

                                    <label for="profile" class="form-label">プロフィール</label>
                                    <textarea name="profile" id="profile" class="form-control">{{ $user->profile }}</textarea><br>

                                    <a href="{{ route('resetEmail') }}">パスワード変更はこちら</a><br>
                                    <a href="{{ route('chngeEmail') }}">メールアドレス変更はこちら</a>
                                </div>
                                
                                <div class="d-flex justify-content-around mb-3">
                                    <a href="{{ url('/users/' . $user->user_id) }}" class="btn btn-secondary">戻る</a>
                                    <button type="submit" class="btn btn-primary">更新</button>
                                </div><br><br><br>
                                <div class="d-flex justify-content-center mb-3">
                                <a href="{{ route('user_delete') }}" class="btn btn-danger">アカウント削除</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<script src="{{ asset('js/script.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
