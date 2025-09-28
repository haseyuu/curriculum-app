@extends('layouts.header')
@section('content')
    <main class="py-4">
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class='text-center'>ログイン</h1>
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
                        <form action="" method="post">
                            @csrf
                            <label for="user_info" class='mt-2'>メールアドレス or ユーザーID</label>
                                <input type="text" class='form-control' name='user_info'
                                value="{{old('user_info'?? '')}}">
                            <label for="user_info" class='mt-2'>パスワード</label>
                                <input type="password" class='form-control' name='password'>
                            <a href="{{route('resetEmail')}}">パスワードを忘れた方はこちら</a><br>
                            <div class='row justify-content-center'>
                                <button type='submit' class='btn btn-primary w-25 mt-3'>ログイン</button>
                            </div>
                        </form><br>
                        <div class='row justify-content-center'>
                            <a href="{{ route('registEmail') }}">新規登録はこちら</a>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('login.google') }}" class="btn btn-danger w-100">
                                Googleでログイン
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection