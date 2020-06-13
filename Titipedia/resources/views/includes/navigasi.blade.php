<nav style="background-color: #65587f;" class="navbar navbar-dark  navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand text-light" href="/home">
      <img src="{{ asset('images/titipedia.png') }}" width="30" height="30" class="d-inline-block align-top" alt="">
      Titipedia
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        @guest
        <li class="nav-item">
          <a class="nav-link text-light" href="/login">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-light" href="/register">Register</a>
        </li>
        @else
        <?php
        $notify = DB::table('pesans')
          ->where('dibaca', 'belum')
          ->where('id_penerima', Auth::user()->id)->get();
        ?>
        <li class="nav-item">
          <a class="nav-link text-light" href="/produk">Produk & Request</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-light" href="/order/daftar_pembelian_preorder/{{Auth::user()->id}}">Pembelian</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-light" href="/pesan"> <i class="fa fa-envelope"></i> <span> Pesan <span class="label label-danger"><?= $notify->count() ?: '' ?></span></span></a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="{{ asset('photo_profile/'.Auth::user()->foto)}}" width="30" height="30" class="rounded-circle">
            {{Auth::user()->name}}
          </a>
          <div class="dropdown-menu " aria-labelledby="navbarDropdownMenuLink">
            <a class="nav-link text-dark" href="/profile">Profile</a>
            <a class="nav-link  text-dark" href="/topup">Top Up Saldo</a>
            <a class="nav-link text-dark" href="/pesan">Pesan</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
            <a class="nav-link text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
            </a>
          </div>
        </li>
      </ul>
      @endguest
    </div>
  </div>
</nav>