@extends('layouts.postlist')

@section('content')
<div class="container">
    <div class="card p-4">

        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <!-- ユーザーアイコン -->
                <img src="https://via.placeholder.com/150" 
                     class="rounded-circle border me-3" 
                     style="width:100px; height:100px; object-fit:cover;">

                <!-- ユーザー名とID -->
                <div>
                    <h4 class="mb-1">ユーザー名</h4>
                    <p class="text-muted mb-0">@user_id</p>
                </div>
            </div>

            <!-- ① フォローステータス（自分のページなら「プロフィール編集」） -->
            <div>
                <button class="btn btn-outline-primary">フォローする</button>
                {{-- <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">プロフィール編集</a> --}}
            </div>
        </div>

        <hr>

        <div class="mb-3">
            <h6 class="fw-bold">プロフィール</h6>
            <p class="border rounded p-2">ここにプロフィール文が入ります。</p>
        </div>

        <div class="d-flex justify-content-around border-top pt-3">
            <div class="text-center">
                <strong>② フォロー</strong><br>
                <span>10</span>
            </div>
            <div class="text-center">
                <strong>③ フォロワー</strong><br>
                <span>20</span>
            </div>
            <div class="text-center">
                <a href="#" class="text-decoration-none">
                    <strong>④ 投稿一覧</strong>
                </a>
            </div>
            <div class="text-center">
                <a href="#" class="text-decoration-none">
                    <strong>⑤ いいね</strong>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection