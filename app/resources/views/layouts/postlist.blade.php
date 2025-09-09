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
        <div class="sidebar">
            <h4 class="text-center">メニュー</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="{{ url('/mypage') }}" class="nav-link">ホーム</a></li>
                <li class="nav-item"><a href="{{ url('/posting') }}" class="nav-link">投稿</a></li>
                <li class="nav-item"><a href="{{ url('/search') }}" class="nav-link">検索</a></li>
                <li class="nav-item"><a href="{{ url('/login') }}" class="nav-link">ログアウト</a></li>
            </ul>
        </div>

        <div class="content">
            <div id="posts-container">
                @foreach($posts as $post)
                <div class="card mb-3 p-3">
                    <!-- 投稿ヘッダー -->
                    <div class="d-flex align-items-start mb-2">
                        <img src="{{ $post->user->icon ?? asset('default_icon.png') }}" class="rounded-circle me-3" style="width:50px; height:50px; object-fit:cover; margin-right:1vw;">
                        <div class="flex-grow-1">
                            <div><strong>{{ $post->user->name }}</strong></div>
                            <div class="text-muted">{{ $post->user->user_id }}</div>
                        </div>
                        @if(auth()->id() === $post->user_id)
                        <div class="ms-3">
                            <button class="btn btn-sm btn-outline-primary me-1">編集</button>
                            <button class="btn btn-sm btn-outline-danger">削除</button>
                        </div>
                        @endif
                    </div>

                    <!-- 投稿本文 -->
                    <p>{{ $post->comment }}</p>

                    <!-- 画像 -->
                    @if($post->images->count() > 0)
                    <div class="d-flex flex-wrap mb-2">
                        @foreach($post->images as $image)
                        <img src="{{ asset('storage/' . $image->image) }}" class="img-thumbnail me-2 mb-2" style="max-width:150px;">
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            @if($posts->hasMorePages())
            <button id="load-more" data-next-page="{{ $posts->nextPageUrl() }}" class="btn btn-primary mt-3">もっと見る</button>
            @endif
        </div>

    

<script>
document.getElementById('load-more')?.addEventListener('click', function(){
    let button = this;
    let url = button.dataset.nextPage;

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        // 取得したHTMLから投稿部分だけを抽出して追加
        let tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        let newPosts = tempDiv.querySelectorAll('#posts-container > div.card');
        newPosts.forEach(post => document.getElementById('posts-container').appendChild(post));

        // 次ページ URL 更新 or ボタン非表示
        let nextPageUrl = tempDiv.querySelector('#load-more')?.dataset.nextPage;
        if(nextPageUrl){
            button.dataset.nextPage = nextPageUrl;
        } else {
            button.style.display = 'none';
        }
    });
});
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>