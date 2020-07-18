<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
    <style type="text/css">
        #splash-screen {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #2D323D;
            z-index: 99999;
            pointer-events: none;
        }

        #splash-screen .center {
            display: block;
            width: 100%;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }

        #splash-screen .logo {
            width: 128px;
            margin: 0 auto;
        }

        #splash-screen .logo img {
            filter: drop-shadow(0px 10px 6px rgba(0, 0, 0, 0.2))
        }

        #splash-screen .spinner-wrapper {
            display: block;
            position: relative;
            width: 100%;
            min-height: 100px;
            height: 100px;
        }
    </style>
</head>
<body >
    <noscript>
        <strong>We're sorry but Vue.js doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div id="app">
        <!-- begin #page-loader -->
            <div id="splash-screen">
            <div class="center">

                <div class="logo">
                    <img width="128" src="{{ asset('images/vue-laravel.png') }}" alt="logo">
                </div>
                <div class="spinner-wrapper">
                    <div class="spinner">
                        <span class="spinner-border  text-success" role="status">
                            <span class="sr-only">Loading...</span>
                        </span>
                    </div>

                </div>
            </div>
        </div>
        <!-- end #page-loader -->
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <script type="text/javascript">
        window.application = @json(['name'=>config('app.name', 'Laravel')]);
    </script>
    @auth
        <script>
            window.user = @json(auth()->user());
        </script>
    @endauth
    <script src="{{ asset(mix('js/manifest.js')) }}"></script>
    <script src="{{ asset(mix('js/vendor.js')) }}"></script>
    <script src="{{ asset(mix('js/app.js')) }}"></script>

</body>
</html>
