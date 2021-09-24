<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <div class="top text-center">
                <img src="/images/YodaTop.jpg" alt="Yoda"></img>
            </div>
            <main>
                <router-view></router-view>
                @yield('content')
            </main>
            <footer class="fixed-bottom p-2">
                <div class="container">
                    <div class="row">
                        <div class="p-2 col-12 col-md-4 text-center text-md-start">
                            <i class="far fa-copyright"></i> 2021 - David Velasco
                        </div>
                        <div class="p-2 col-12 col-md-4 text-center"><img src="/images/YodaBottom.png" alt="Yoda"></img></div>
                        <div class="p-2 col-12 col-md-4 text-center text-md-end">
                            <a href="mailto:davidvr1992@gmail.com"><i class="far fa-envelope"></i> davidvr1992@gmail.com</a><br>
                            <a href="tel:+34636543865"><i class="fas fa-phone-alt"></i> 636 543 865</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>