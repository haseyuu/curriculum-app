@extends('layouts.postlist')

@section('content')
<div class="container">
    <div class="card p-4">
        <!-- ユーザー情報 -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ $user->icon ?? asset('default_icon.png') }}" 
                     class="rounded-circle border me-3" 
                     style="width:100px; height:100px; object-fit:cover;margin-right:1vw;">
                <div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->user_id }}</p>
                </div>
            </div>
            <div>
                @if(auth()->id() === $user->id)
                    <a href="" class="btn btn-outline-secondary">プロフィール編集</a>
                @elseif($user->follows->contains(auth()->id()))
                    <button class="btn btn-outline-primary" id="btn-follow">フォロー解除</button>
                @elseif(!$user->follows->contains(auth()->id()))
                    <button class="btn btn-outline-primary" id="btn-follow">フォローする</button>
                @endif
            </div>
        </div>

        <hr>

        <!-- フォロー／フォロワー／投稿／いいね -->
        <div class="d-flex justify-content-around border-top pt-3 mb-3">
            <div class="text-center">
                <strong>フォロー</strong><br>
                <span>{{ $user->follows()->count() }}</span>
            </div>
            <div class="text-center">
                <strong>フォロワー</strong><br>
                <span>{{ $user->followers()->count() }}</span>
            </div>
            <div class="text-center">
                <button id="btn-posts" class="btn btn-link text-decoration-none"><strong>投稿一覧</strong></button>
            </div>
            <div class="text-center">
                <button id="btn-likes" class="btn btn-link text-decoration-none"><strong>いいね</strong></button>
            </div>
        </div>

        <!-- 投稿といいねのコンテナ -->
        <div id="posts-container" style="margin-top:1vw;">
            <div id="posts-list">
                @foreach($posts as $post)
                <div class="card mb-3 p-3">
                    <div class="d-flex align-items-start mb-2">
                        <a href="{{ url('/users/' . $post->user->user_id) }}">
                            <img src="{{ $post->user->icon ?? asset('default_icon.png') }}" 
                                class="rounded-circle me-3" 
                                style="width:50px; height:50px; object-fit:cover; margin-right:1vw;">
                        </a>
                        <div class="flex-grow-1">
                            <a href="{{ url('/users/' . $post->user->user_id) }}" class="text-decoration-none text-dark">
                                <div><strong>{{ $post->user->name }}</strong></div>
                                <div class="text-muted">{{ $post->user->user_id }}</div>
                            </a>
                        </div>
                        @if(auth()->id() === $post->user_id)
                        <div class="ms-3">
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-outline-primary me-1">編集</a>
                            <button class="btn btn-sm btn-outline-danger btn-delete-post" data-id="{{ $post->id }}">削除</button>
                        </div>
                        @endif
                    </div>
                    <p>{{ $post->comment ?? '' }}</p>
                    @if($post->images->count() > 0)
                    <div class="d-flex flex-wrap mb-2">
                        @foreach($post->images as $image)
                        <img src="{{ asset('storage/' . $image->image) }}" class="img-thumbnail me-2 mb-2" style="max-width:150px;">
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
                @if($posts->hasMorePages())
                <button id="post-more" data-next-page="{{ $posts->nextPageUrl() }}" class="btn btn-primary mt-3">もっと見る</button>
                @endif
            </div>

            <div id="likes-list" style="display:none;">
                @foreach($likes as $like)
                <div class="card mb-3 p-3">
                    <div class="d-flex align-items-start mb-2">
                        <a href="{{ url('/users/' . $like->user->user_id) }}">
                            <img src="{{ $like->user->icon ?? asset('default_icon.png') }}" 
                                class="rounded-circle me-3" 
                                style="width:50px; height:50px; object-fit:cover; margin-right:1vw;">
                        </a>
                        <div class="flex-grow-1">
                            <a href="{{ url('/users/' . $like->user->user_id) }}" class="text-decoration-none text-dark">
                                <div><strong>{{ $like->user->name }}</strong></div>
                                <div class="text-muted">{{ $like->user->user_id }}</div>
                            </a>
                        </div>
                    </div>
                    <p>{{ $like->comment ?? '' }}</p>
                    @if($like->images->count() > 0)
                    <div class="d-flex flex-wrap mb-2">
                        @foreach($like->images as $image)
                        <img src="{{ asset('storage/' . $image->image) }}" class="img-thumbnail me-2 mb-2" style="max-width:150px;">
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
                @if($likes->hasMorePages())
                <button id="like-more" data-next-page="{{ $likes->nextPageUrl() }}" class="btn btn-primary mt-3">もっと見る</button>
                @endif
            </div>
        </div>
    </div>
@endsection