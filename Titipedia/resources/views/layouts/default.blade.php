<!DOCTYPE html>
<html>
<head>
    @include('includes.head')
</head>
<body>
    <!-- Navigation -->
    @include('includes.navigasi')
    <!-- Page Content -->
    <div style="padding-top: 56px;" class="container">
        <div class="row">
            <div class="col-lg-3">
                <!-- Sidebar -->
                @include('includes.sidebarKategori')
            </div>
            <div class="col-lg-9">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="py-5 bg-dark">
    @include('includes.footer')
    </footer>
</body>
</html>