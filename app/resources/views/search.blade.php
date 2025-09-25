@extends('layouts.postlist')
@section('content')
    <div class="d-flex justify-content-center mb-3">
        <form action="search" method="get" class="w-50">
            @csrf
            <div class="input-group">
                <input type="text" name="search_word" class="form-control" placeholder="検索ワードを入力">
                <button class="btn btn-secondary" type="submit">検索</button>
            </div>
        </form>
    </div>

        @include('layouts.posts', ['posts' => $posts])
        @include('layouts.users', ['users' => $users])
    
@endsection