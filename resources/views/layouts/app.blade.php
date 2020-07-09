<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="flex justify-end py-6">
        @guest
            <a class="mx-8 text-orange-700 text-xl font-bold underline uppercase" href="{{ route('event.join')}}">Join Raffle</a>
            <a class="mx-8 text-orange-700 text-xl font-bold underline uppercase" href="{{ route('login') }}">Sign in</a>
        @endguest
        @auth
            @if(url()->current() !== route('raffle'))
                <a class="mx-8 text-orange-700 underline uppercase" href="{{ route('raffle') }}">Raffle</a>
            @else
                <a class="mx-8 text-orange-700 underline uppercase" href="{{ route('event.dashboard') }}">Manage raffle</a>
            @endif
            <a class="mx-8 text-orange-700 underline uppercase" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Sign out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endauth
    </nav>

    <header class="flex flex-col items-center">
        <img src="https://res.cloudinary.com/felipeflor/image/upload/c_scale,f_auto,w_250/v1583333994/vehikl.png"
             alt="Vehikl Logo">
        <h1 class="mb-1 font-bold">{{ config('app.name', 'Laravel') }}</h1>
    </header>
    <main class="mx-auto py-4 container">
        @yield('content')
    </main>
</div>
</body>
</html>
