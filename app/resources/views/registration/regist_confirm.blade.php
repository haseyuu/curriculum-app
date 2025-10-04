@extends('layouts.header')
@section('content')
<main class="py-4">
    <div class="col-md-5 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">登録内容確認</h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('regist_complete') }}" method="post">
                    @csrf

                    @php
                        $data = session('registration', []);
                    @endphp

                    <div class="mb-2">
                        <label>ユーザー名</label>
                        <div>{{ $data['name'] ?? '' }}</div>
                    </div>

                    <div class="mb-2">
                        <label>ユーザーID</label>
                        <div>{{ $data['user_id'] ?? '' }}</div>
                    </div>

                    <!-- パスワードは表示しない -->

                    <div class="row justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary w-25">新規登録</button>
                    </div>
                </form>

                <div class="row justify-content-center mt-3">
                    <a href="{{ route('register_back') }}" class="btn btn-secondary w-25">戻る</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection