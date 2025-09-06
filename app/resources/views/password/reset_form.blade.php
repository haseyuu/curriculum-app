@extends('layouts.header')
@section('content')
    <main class="py-4">
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class='text-center'>パスワード再設定</h1>
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
                        <form action="{{ route('reset_comp') }}" method="post">
                            @csrf
                            <input type="password" class='form-control' name='password'
                                placeholder="*パスワード"><br>
                            <input type="password" class='form-control' name='password_confirm'
                                placeholder="*パスワード再入力"><br>
                            <div class='row justify-content-center'>
                                <button type='submit' class='btn btn-primary w-25 mt-3'>送信</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection