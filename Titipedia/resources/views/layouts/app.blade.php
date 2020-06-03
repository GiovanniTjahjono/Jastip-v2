<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('includes.head')
    </head>
<body>
    <div id="app">
        @include('includes.navigasi')
        <main class="py-4 mt-5">
            @yield('content')
        </main>
    </div>
</body>
</html>
