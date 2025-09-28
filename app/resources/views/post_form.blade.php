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
    
    <div class ='panel-body'>
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $message)
            <p>{{ $message }}</p>
            @endforeach
        </div>
        @endif
    </div>
    <form action="{{ isset($post) ? route('posts.update', $post->id) : route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
        @if(isset($post))
            @method('PUT')
        @endif
        <div class="container border p-3" style="max-width: 600px;">
            <div class="d-flex justify-content-between mb-3">
                <a href="{{ $previousUrl ?? url()->previous() }}" class="btn btn-link">戻る</a>
                <input type="hidden" name="previousUrl" value="{{ $previousUrl ?? url()->previous() }}">
                <button type='submit' class='btn btn-primary w-25 mt-3'>投稿</button>
            </div>

            <div class="form-group mb-3">
                <textarea id="content" name="comment" class="form-control" maxlength="200" rows="4" placeholder="投稿内容を入力してください">{{ old('comment', $post->comment ?? '') }}</textarea>
                <small id="charCount" class="form-text text-muted">残り200文字</small>
            </div>

            <div class="form-group mb-3">
                <label for="images">画像（最大4枚）</label>
                <input type="file" id="images" name="images[]" class="form-control-file" accept="image/*" multiple onchange="previewImages(event)">

                <div id="previewArea" class="mt-2 d-flex flex-wrap">
                    {{-- 既存画像の表示 --}}
                    @if(isset($post) && $post->images)
                        @foreach($post->images as $image)
                        <div class="position-relative m-2">
                            <img src="{{ asset('storage/' . $image->image) }}" class="img-thumbnail" style="max-width:150px;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute"
                                    style="top:0; right:0; transform: translate(50%,-50%); border-radius:50%;"
                                    onclick="removeExistingImage(this, {{ $image->id }})">×</button>
                            <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="scope">公開範囲</label>
                <select id="scope" name="visibility" class="form-control">
                    <option value=0 {{ (old('visibility', $post->visibility ?? 0) == 0) ? 'selected' : '' }}>全体</option>
                    <option value=1 {{ (old('visibility', $post->visibility ?? 0) == 1) ? 'selected' : '' }}>相互フォローのみ</option>
                    <option value=2 {{ (old('visibility', $post->visibility ?? 0) == 2) ? 'selected' : '' }}>非公開(自分のみ閲覧可)</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="reserve">公開予約時間</label>
                <input type="datetime-local" id="reserve" name="reserve"
                    class="form-control"
                    value="{{ old('reserve', isset($post->reserve) ? \Carbon\Carbon::parse($post->reserve)->format('Y-m-d\TH:i') : '') }}">
            </div>
        </div>
    </form>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('content');
    const charCount = document.getElementById('charCount');
    const maxLength = textarea.getAttribute('maxlength');

    function updateCharCount() {
        const remaining = maxLength - textarea.value.length;
        charCount.textContent = `残り${remaining}文字`;
    }

    // 初期表示
    updateCharCount();

    // 入力ごとに更新
    textarea.addEventListener('input', updateCharCount);
});
    let selectedFiles = [];

    // 既存画像を削除する
    function removeExistingImage(btn, imageId) {
        // 親要素を削除
        btn.parentElement.remove();
    }

    function previewImages(event) {
        const files = Array.from(event.target.files);

        if (selectedFiles.length + files.length > 4) {
            alert("画像は最大4枚までです。");
            return;
        }

        files.forEach(file => {
            if (selectedFiles.length < 4) {
                selectedFiles.push(file);
            }
        });

        renderPreviews();
    }

    function renderPreviews() {
        const previewArea = document.getElementById('previewArea');

        // 既存画像は残す
        const existing = Array.from(previewArea.querySelectorAll('input[name="existing_images[]"]')).map(el => el.parentElement);
        previewArea.innerHTML = '';
        existing.forEach(el => previewArea.appendChild(el));

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.classList.add("position-relative", "m-2");
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add("img-thumbnail");
                img.style.maxWidth = "150px";

                const removeBtn = document.createElement('button');
                removeBtn.textContent = "×";
                removeBtn.classList.add("btn", "btn-danger", "btn-sm", "position-absolute");
                removeBtn.style.top = "0";
                removeBtn.style.right = "0";
                removeBtn.style.transform = "translate(50%,-50%)";
                removeBtn.style.borderRadius = "50%";
                removeBtn.addEventListener('click', () => {
                    removeImage(index);
                });

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                previewArea.appendChild(wrapper);
            }
            reader.readAsDataURL(file);
        });
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);
        renderPreviews();
    }
</script>