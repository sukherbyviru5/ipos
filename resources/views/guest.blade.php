<!DOCTYPE html>
<html lang="en">
<head>
    @include('meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    {{-- link css --}}
    <link rel="stylesheet" href="{{ asset('mobile/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mobile/dist/css/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mobile/dist/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('mobile/dist/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mobile/dist/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('mobile/dist/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('mobile/dist/style.css') }}">
    <link rel="manifest" href="{{ asset('mobile/dist/manifest.json') }}">
</head>
@yield('styles')
<body>
    <div class="preloader" id="preloader">
        <div class="spinner-grow text-secondary" role="status">
            <div class="sr-only"></div>
        </div>
    </div>
    <div class="header-area" id="headerArea">
        <div class="container h-100 p-2 d-flex align-items-center justify-content-between d-flex rtl-flex-d-row-r">
            @if (request()->is('/'))
                <div class="logo-wrapper">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset(App\Models\SettingApp::first()->logo ?? 'mobile/dist/img/core-img/logo-small.png') }}" alt="Logo" width="40">
                    </a>
                </div>
            @else
                <div class="back-button me-2">
                  <a href="javascript:history.back()"><i class="ti ti-arrow-left"></i></a>
                </div>
                <div class="page-heading">
                  <h6 class="mb-0">@yield('title')</h6>
                </div>
            @endif
           
            <div class="navbar-logo-container d-flex align-items-center">
                @if (session()->get('is_siswa') || session()->get('is_guru'))
                  <div class="suha-navbar-toggler ms-2" data-bs-toggle="offcanvas" data-bs-target="#suhaOffcanvas" aria-controls="suhaOffcanvas">
                    <div><span></span><span></span><span></span></div>
                  </div>
                @elseif (session()->get('is_admin'))
                  <a href="{{ url('admin') }}" class="btn btn-warning ms-2">Dashboard</a>
                @else
                  <a href="{{ url('login') }}" class="btn btn-warning ms-2">
                  Masuk <i class="ti ti-login"></i> 
                  </a>
                @endif
            </div>
        </div>
    </div>
    @if (session()->get('is_siswa') || session()->get('is_guru'))
        <div class="offcanvas offcanvas-start suha-offcanvas-wrap" tabindex="-1" id="suhaOffcanvas" aria-labelledby="suhaOffcanvasLabel">
            <button class="btn-close btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            <div class="offcanvas-body">
                <div class="sidenav-profile">
                    <div class="user-profile">
                        <img style="border-radius: 5px !important;" src="{{ asset( App\Models\Siswa::getall()->foto ?? App\Models\SettingApp::first()->logo) }}" alt="User Profile">
                    </div>
                    <div class="user-info">
                        <h5 class="user-name mb-1 text-white">{{ session()->get('nama') }}</h5>
                        @php
                            if (session()->get('is_siswa')) {
                                $siswa = App\Models\Siswa::getall();
                                if ($siswa && $siswa->kelas) {
                                    $data = 'Kelas ' . $siswa->kelas->tingkat_kelas . ' ' . $siswa->kelas->kelompok . ' ( ' . $siswa->kelas->urusan_kelas . ' ) ( Jurusan ' . $siswa->kelas->jurusan . ' )';
                                } else {
                                    $data = '-';
                                }
                            } elseif (session()->get('is_guru')) {
                                $data = App\Models\Guru::getall()->nama_mata_pelajaran;
                            } else {
                                $data = 'Admin';
                            }
                        @endphp
                        <p class="available-balance text-white">{{ $data }}</p>
                    </div>
                </div>
                <ul class="sidenav-nav ps-0">
                    @if (session()->get('is_guru'))
                        <li><a href="{{ url('guru') }}"><i class="ti ti-dashboard"></i>Dashboard</a></li>
                        <li><a href="{{ url('guru/profil') }}"><i class="ti ti-user"></i>My Profile</a></li>
                        <li><a href="{{ url('guru/publikasi') }}"><i class="ti ti-file-text"></i>Publikasi</a></li>
                        <li><a href="{{ url('guru/peminjaman') }}"><i class="ti ti-book"></i>Buku Pinjaman</a></li>
                    @else
                        <li><a href="{{ url('siswa') }}"><i class="ti ti-dashboard"></i>Dashboard</a></li>
                        <li><a href="{{ url('siswa/profil') }}"><i class="ti ti-user"></i>My Profile</a></li>
                        <li><a href="{{ url('siswa/publikasi') }}"><i class="ti ti-file-text"></i>Publikasi</a></li>
                        <li><a href="{{ url('siswa/peminjaman') }}"><i class="ti ti-book"></i>Buku Pinjaman</a></li>
                    @endif
                    
                    <li><a href="{{ url('logout') }}"><i class="ti ti-logout"></i>Sign Out</a></li>
                </ul>
            </div>
        </div>
    @endif
    <div class="page-content-wrapper">
        @yield('content')
    </div>
    <div class="internet-connection-status" id="internetStatus"></div>
    <div class="footer-nav-area" id="footerNav">
        <div class="suha-footer-nav">
            <ul class="h-100 d-flex align-items-center justify-content-between ps-0 d-flex rtl-flex-d-row-r">
                <li><a href="{{ url('/') }}"><i class="ti ti-home"></i>Home</a></li>
                <li><a href="{{ url('profil') }}"><i class="ti ti-user"></i>Profil</a></li>
                <li><a href="{{ url('foto') }}"><i class="ti ti-photo"></i>Foto Kegiatan</a></li>
                <li><a href="{{ url('buku') }}"><i class="ti ti-book"></i>Buku</a></li>
                <li><a href="{{ url('artikel') }}"><i class="ti ti-file-text"></i>Publikasi</a></li>
            </ul>
        </div>
    </div>
    @yield('scripts')
    <script src="{{ asset('mobile/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/jquery.min.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/jquery.passwordstrength.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/theme-switching.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/no-internet.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/active.js') }}"></script>
    <script src="{{ asset('mobile/dist/js/pwa.js') }}"></script>
</body>
</html>