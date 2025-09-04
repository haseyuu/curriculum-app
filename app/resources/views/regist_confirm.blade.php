@extends('layouts.header')
@section('content')
    <main class="py-4">
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class='text-center'>登録内容確認</h1>
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
                        <form action="regist_complete" method="post">
                            @csrf
                            <label for="user_name" class='mt-2'>{{old('user_name')}}</label>
                            <label for="user_id" class='mt-2'>{{old('user_id')}}</label>
                            <label for="email" class='mt-2'>{{old('email')}}</label><br>
                            <div class='row justify-content-center'>
                                <button type='submit' class='btn btn-primary w-25 mt-3'>新規登録</button>
                            </div>
                        </form><br>
                        <div class='row justify-content-center'>
                            <a href="{{ route('register') }}" class='btn btn-secondary w-25 mt-3'>戻る</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection