<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset(mix('css/landing.css')) }}" rel="stylesheet">
</head>
<body>
<div id="header" class="header navbar navbar-expand-md bg-dark  fixed-top">
    <!-- begin container -->
    <div class="container">
        <!-- begin navbar-header -->
        <div class="navbar-header">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="/" class="navbar-brand">
                <img src="/images/vue-laravel.png" alt="Laravel Vue App" class="brand-image">
            </a>
        </div>
        <!-- end navbar-header -->
        <!-- begin navbar-collapse -->
        <div class="collapse navbar-collapse" id="header-navbar">

            <ul class="nav navbar-nav ml-md-auto">
                @if (Route::has('login'))
                    @auth
                        <li class="nav-item"><a href="/app" class="nav-link" data-click="scroll-to-target">Dashboard</a></li>
                    @else
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link" data-click="scroll-to-target">LOGIN</a></li>

                        @if (Route::has('register'))
                            <li class="nav-item"><a href="{{ route('register') }}" class="nav-link" data-click="scroll-to-target">REGISTER</a></li>
                        @endif
                    @endauth
                @endif
            </ul>
        </div>
        <!-- end navbar-collapse -->
    </div>
    <!-- end container -->
</div>
    <main>
        @yield('content')
    </main>
</body>
</html>
