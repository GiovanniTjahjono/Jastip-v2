<!DOCTYPE html>
<html>
<head>
    @include('includes.head')
</head>
<body>
    @include('includes.navigasi')
    <div style="padding-top: 56px;" class="container">
        @yield('content')
    </div>
    <footer class="py-5 mt-5 bg-dark">
        @include('includes.footer')
    </footer>
</body>
</html>