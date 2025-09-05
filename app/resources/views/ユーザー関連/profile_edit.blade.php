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
                            <form id="profileForm" action="#" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 text-center position-relative" style="width:150px; margin:auto;">
                                    <img id="avatarPreview" src="" 
                                        class="rounded-circle border" 
                                        style="width:150px; height:150px; object-fit:cover; cursor:pointer;">
                                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">

                                    <button type="button" id="cropDoneBtn" class="btn btn-success btn-sm" 
                                            style="position:absolute; bottom:-35px; left:50%; transform:translateX(-50%); display:none;">
                                        完了
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">ユーザー名</label>
                                    <input type="text" name="name" id="name" value="" class="form-control">
                                    <label for="name" class="form-label">ユーザーID</label>
                                    <input type="text" name="name" id="name" value="" class="form-control">
                                    <label for="name" class="form-label">プロフィール</label>
                                    <textarea name="name" id="name" value="" class="form-control"></textarea><br>
                                    <a href="{{route('email_change')}}">メールアドレス変更はこちら</a><br>
                                    <a href="{{route('email_change')}}">パスワード変更はこちら</a>
                                </div>
                                <div class="d-flex justify-content-around mb-3">
                                    <a href="" class="btn btn-secondary">戻る</a>
                                    <button type="submit" class="btn btn-primary">更新</button>
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


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
const avatarPreview = document.getElementById('avatarPreview');
const avatarInput = document.getElementById('avatarInput');
const cropDoneBtn = document.getElementById('cropDoneBtn');
const form = document.getElementById('profileForm');

let cropper;

avatarPreview.addEventListener('click', function() {
    if (!cropper) {
        avatarInput.click();
    }
});

avatarInput.addEventListener('change', function(e){
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(event){
        avatarPreview.src = event.target.result;

        if (cropper) cropper.destroy();
        cropper = new Cropper(avatarPreview, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
        });

        cropDoneBtn.style.display = 'inline-block'; // 完了ボタン表示
    };
    reader.readAsDataURL(file);
});

cropDoneBtn.addEventListener('click', function(){
    if (!cropper) return;

    cropper.getCroppedCanvas().toBlob((blob) => {
        const file = new File([blob], 'avatar.png', { type: 'image/png' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        avatarInput.files = dataTransfer.files;

        const url = URL.createObjectURL(blob);
        avatarPreview.src = url;

        cropper.destroy();
        cropper = null;
        cropDoneBtn.style.display = 'none';
    });
});

form.addEventListener('submit', function(e){
    if (cropper) {
        e.preventDefault();
        cropper.getCroppedCanvas().toBlob((blob) => {
            const file = new File([blob], 'avatar.png', { type: 'image/png' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            avatarInput.files = dataTransfer.files;

            cropper.destroy();
            cropper = null;
            cropDoneBtn.style.display = 'none';
            form.submit();
        });
    }
});
</script>