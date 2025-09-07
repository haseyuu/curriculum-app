@extends('layouts.header')
@section('content')
    <main class="py-4">
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <h6 class='text-center'>下記メールアドレス宛に送信しました。</h6><br>
                        <h6 class='text-center'>{{ session('email') }}</h6><br>
                        <div class='row justify-content-center'>
                            <a href="{{ route('login') }}" class='btn btn-secondary w-25 mt-3'>戻る</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection