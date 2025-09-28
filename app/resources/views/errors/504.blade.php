@extends('layouts.header')
@section('content')
    <main class="py-4">
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <h6 class='text-center'>もう一度お試しください。</h6>
                        <div class='row justify-content-center'>
                            <a href="{{ url()->previous() }}" >元のページへ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection