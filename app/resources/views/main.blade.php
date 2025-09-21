@extends('layouts.postlist')
@section('content')
    <div id="posts-container" style='margin-top:1vw;'>
        @foreach($posts as $post)
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-start mb-2">
                <a href="{{ url('/users/' . $post->user->user_id) }}">
                    <img src="{{ $post->user->icon ? asset('storage/' . $post->user->icon) : asset('default\_icon.png') }}" 
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
            @if($post->likedByUsers->contains(auth()->id()))
                <span class="btn-favo text-warning" data-id="{{ $post->id }}" data-liked="1" style="cursor:pointer;">★</span>
            @else
                <span class="btn-favo text-warning" data-id="{{ $post->id }}" data-liked="0" style="cursor:pointer;">☆</span>
            @endif
            <span class="favo-count">{{ $post->likedByUsers()->count() }}</span>
        </div>
        @endforeach
    </div>

    @if($posts->hasMorePages())
    <button id="load-more" data-next-page="{{ $posts->nextPageUrl() }}" class="btn btn-primary mt-3">もっと見る</button>
    @endif
       
@endsection