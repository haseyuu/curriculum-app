@if($users)
    @if($users->count() == 0)
    <h5>結果はありません</h5>
    @endif
    <div id="posts-container" style='margin-top:1vw;'>
        @foreach($users as $user)
                <div class="card p-4 mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- アイコン + ユーザー情報 -->
                        <div class="d-flex align-items-start">
                            <!-- ユーザーアイコン -->
                            <img src="{{ $user->icon ? asset('storage/' . $user->icon) : asset('default\_icon.png') }}" 
                                class="rounded-circle border me-3" 
                                style="width:100px; height:100px; object-fit:cover;margin-right:1vw;">

                            <!-- ユーザー名 / ID / プロフィール -->
                            <div class="ms-3">
                                <a href="{{ url('/users/' . $user->user_id) }}" class="text-decoration-none text-dark">
                                    <h5 class="mb-1">
                                        {{ $user->name }} <small class="text-muted">{{ $user->user_id }}</small>
                                    </h5>
                                </a>
                                <p class="profile-text mb-0" style=
                                "display: -webkit-box;
                                -webkit-line-clamp: 2;
                                -webkit-box-orient: vertical;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                max-width:60%">
                                    {{$user->profile}}
                                </p>
                            </div>
                        </div>

                        <!-- フォローボタン -->
                        <div>
                            @if($user->follows->contains(auth()->id()))
                                <button class="btn btn-outline-primary" id="btn-follow" value='0' data-id="{{ $user->id }}">フォロー解除</button>
                            @elseif(!$user->follows->contains(auth()->id()))
                                <button class="btn btn-outline-primary" id="btn-follow" value='1' data-id="{{ $user->id }}">フォローする</button>
                            @endif
                        </div>
                    </div>
                </div>
        @endforeach
    </div>
    
    @if($users->hasMorePages())
    <button id="load-more" data-next-page="{{ $users->nextPageUrl() }}" class="btn btn-primary mt-3">もっと見る</button>
    @endif
@endif