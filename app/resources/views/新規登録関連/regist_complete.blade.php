@extends('layouts.header')
@section('content')
    <main class="py-4">
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class='text-center'>登録完了</h1>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class='row justify-content-center'>
                            <a href="{{ route('login') }}" >ログイン画面へ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection