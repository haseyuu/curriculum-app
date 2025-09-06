@extends('layouts.header')
@section('content')
    <main class="py-4">
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class='text-center'>新規登録</h1>
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
                        <h5 class='text-center'>*は必須項目です。</h5>
                        <form action="{{ route('regist_confirm') }}" method="post">
                            @csrf
                            <input type="text" class='form-control' name='user_name'
                                value="{{old('user_name'?? '')}}" placeholder="*ユーザー名"><br>
                            <input type="text" class='form-control' name='user_id'
                                value="{{old('user_id'?? '')}}" placeholder="ユーザーID"><br>
                            <input type="password" class='form-control' name='password'
                                placeholder="*パスワード"><br>
                            <input type="password" class='form-control' name='password_confirm'
                                placeholder="*パスワード再入力"><br>
                            <div class='row justify-content-center'>
                                <button type='submit' class='btn btn-primary w-25 mt-3'>新規登録</button>
                            </div>
                        </form><br>
                        <div class='row justify-content-center'>
                            <a href="{{ route('login') }}" class='btn btn-secondary w-25 mt-3'>戻る</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection