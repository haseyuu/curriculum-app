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
    <form action="/" method='post'>
        <div class="container border p-3" style="max-width: 600px;">
            <div class="d-flex justify-content-between mb-3">
                <a href="#" class="btn btn-link">戻る</a>
                <button type='submit' class='btn btn-primary w-25 mt-3'>投稿</button>
            </div>

            <div class="form-group mb-3">
                <textarea id="content" class="form-control" maxlength="200" rows="4" placeholder="投稿内容を入力してください"></textarea>
                <small id="charCount" class="form-text text-muted">残り200文字</small>
            </div>

            <div class="form-group mb-3">
                <label for="images">画像（最大4枚）</label>
                <input type="file" id="images" class="form-control-file" accept="image/*" multiple onchange="previewImages(event)">
        
                <div id="previewArea" class="mt-2 d-flex flex-wrap"></div>
            </div>

            <div class="form-group mb-3">
                <label for="scope">投稿範囲</label>
                <select id="scope" class="form-control">
                    <option value="public">全体</option>
                    <option value="mutual">相互フォローのみ</option>
                    <option value="private">非公開(自分のみ閲覧可)</option>
                </select>
            </div>
        </div>
    </form>
</main>

<script>
let selectedFiles = [];

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
    previewArea.innerHTML = '';

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