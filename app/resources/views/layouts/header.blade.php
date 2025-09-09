<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }
    .sidebar {
        width: 15vw;
        height: 100vh;
        position: fixed;
        top: 56px;
        left: 0;
        background-color: #f8f9fa;
        border-right: 1px solid #dee2e6;
        padding: 20px 10px;
    }
    .content {
        margin-top: 56px;
        padding: 20px;
    }
</style>
    @yield('stylesheet')
</head>

<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand mx-auto" href="{{ url('/') }}">test</a>
    </div>
</nav>
<div class='content'>
    @yield('content')
</div>