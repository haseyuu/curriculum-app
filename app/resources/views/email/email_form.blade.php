@extends('layouts.header')
@section('content')
    <main class="py-4">
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class='text-center'>{{$headtxt}}</h1>
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
                        <form action="{{ route($mode) }}" method="post">
                            @csrf
                            <input type="email" class='form-control' name='email'
                                value="{{old('email'?? '')}}" placeholder="メールアドレス"><br>
                            <div class='row justify-content-center'>
                                <button type='submit' class='btn btn-primary w-25 mt-3'>送信</button>
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