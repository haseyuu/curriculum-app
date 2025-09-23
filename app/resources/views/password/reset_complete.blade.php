@extends('layouts.header')
@section('content')
    <main class="py-4">
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <h6 class='text-center'>{{$message}}</h6>
                        <div class='row justify-content-center'>
                            <a href="{{ route('login') }}" >ログイン画面へ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection