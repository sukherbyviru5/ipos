<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('assets/img/avatar.png') }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, {{ session()->get('nama') }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">Tetap Semangat</div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item has-icon edit-profile">
                    <i class="far fa-user"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('logout') }}" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">ADMIN</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">AD</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li {{ $sb == 'Dashboard' ? 'class=active' : '' }}>
                <a class="nav-link" href="{{ url('admin') }}"><i class="fas fa-fire"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">ANGGOTA PERPUSTAKAAN</li>
            @php
                $menuAnggota = [
                    [
                        'title' => 'Kelas',
                        'url' => 'admin/manage-member/kelas',
                        'page' => 'Data Kelas'
                    ],
                    [
                        'title' => 'Guru',
                        'url' => 'admin/manage-member/guru',
                        'page' => 'Data Guru'
                    ],
                    [
                        'title' => 'Siswa',
                        'url' => 'admin/manage-member/siswa',
                        'page' => 'Data Siswa'
                    ],
                    [
                        'title' => 'Kenaikan Kelas',
                        'url' => 'admin/manage-member/kenaikan-kelas',
                        'page' => 'Kenaikan Kelas'
                    ],
                    [
                        'title' => 'Status Siswa',
                        'url' => 'admin/manage-member/status-siswa',
                        'page' => 'Status Siswa'
                    ],
                    
                   
                ];
                $pages = array_column($menuAnggota, 'page');
            @endphp

            <li class="nav-item dropdown {{ in_array($sb, $pages) ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-users"></i>
                    <span>Manage Anggota</span>
                </a>
                <ul class="dropdown-menu">
                    @foreach($menuAnggota as $menu)
                        <li {{ $sb == $menu['page'] ? 'class=active' : '' }}>
                            <a class="nav-link" href="{{ url($menu['url']) }}">{{ $menu['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>

            <li class="menu-header">DATA BUKU</li>
            @php
                $menuBuku = [
                    [
                        'title' => 'Kategori Buku',
                        'url' => 'admin/data-buku/kategori-buku',
                        'page' => 'Kategori Buku'
                    ],
                    [
                        'title' => 'DDC Buku',
                        'url' => 'admin/data-buku/ddc-buku',
                        'page' => 'DDC Buku'
                    ],
                    [
                        'title' => 'Kondisi Buku',
                        'url' => 'admin/data-buku/kondisi-buku',
                        'page' => 'Kondisi Buku'
                    ],
                    [
                        'title' => 'Jenis Buku',
                        'url' => 'admin/data-buku/jenis-buku',
                        'page' => 'Jenis Buku'
                    ],
                ];
                $pages = array_column($menuBuku, 'page');
            @endphp

            <li class="nav-item dropdown {{ in_array($sb, $pages) ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-book-reader"></i>
                    <span>Manage Data Buku</span>
                </a>
                <ul class="dropdown-menu">
                    @foreach($menuBuku as $menu)
                        <li {{ $sb == $menu['page'] ? 'class=active' : '' }}>
                            <a class="nav-link" href="{{ url($menu['url']) }}">{{ $menu['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li {{ $sb == 'Data Buku' ? 'class=active' : '' }}>
                <a class="nav-link" href="{{ url('admin/data-buku') }}"><i class="fas fa-book"></i>
                    <span>Data Buku</span>
                </a>
            </li>
            <li {{ $sb == 'QR Code Buku' ? 'class=active' : '' }}>
                <a class="nav-link" href="{{ url('admin/data-buku/qr-buku') }}"><i class="fas fa-qrcode"></i>
                    <span>QR Code Buku</span>
                </a>
            </li>
            <li class="menu-header">PEMINJAMAN BUKU SISWA</li>
            @php
                $menuPeminjaman = [
                    [
                        'title' => 'Setting Peminjaman',
                        'url' => 'admin/peminjaman/settings',
                        'page' => 'Setting Peminjaman',
                    ],
                    [
                        'title' => 'Peminjaman Siswa',
                        'url' => 'admin/peminjaman/peminjaman-siswa',
                        'page' => 'Peminjaman Siswa',
                    ],
                    [
                        'title' => 'Pengembalian Siswa',
                        'url' => 'admin/peminjaman/pengembalian-siswa',
                        'page' => 'Pengembalian Siswa',
                    ],
                    [
                        'title' => 'Buku Rusak/Hilang',
                        'url' => 'admin/peminjaman/buku-rusak-hilang',
                        'page' => 'Buku Rusak/Hilang',
                    ],
                ];
                $pages = array_column($menuPeminjaman, 'page');
            @endphp

            <li class="nav-item dropdown {{ in_array($sb, $pages) ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-book-reader"></i>
                    <span>Peminjaman Buku</span>
                </a>
                <ul class="dropdown-menu">
                    @foreach($menuPeminjaman as $menu)
                        <li {{ $sb == $menu['page'] ? 'class=active' : '' }}>
                            <a class="nav-link" href="{{ url($menu['url']) }}">{{ $menu['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
            
           {{-- --------------------------- stack cuy yang peminjaman dan pengembalian-------------------------------}}

            <li class="menu-header">PUBLIKASI</li>

            <li {{ $sb == 'Artikel' ? 'class=active' : '' }}>
                <a class="nav-link" href="{{ url('admin/publikasi/artikel') }}"><i class="fas fa-newspaper"></i>
                    <span>Artikel</span>
                </a>
            </li>

            <li class="menu-header">SETTING APPS</li>
            @php
                $menuSettings = [
                    [
                        'title' => 'Banner',
                        'url' => 'admin/setting/banner',
                        'page' => 'Banner',
                    ],
                    [
                        'title' => 'Video',
                        'url' => 'admin/setting/video',
                        'page' => 'Video',
                    ],
                    [
                        'title' => 'Foto Kegiatan',
                        'url' => 'admin/setting/foto',
                        'page' => 'Foto Kegiatan',
                    ],
                    [
                        'title' => 'Link',
                        'url' => 'admin/setting/link',
                        'page' => 'Link',
                    ],
                    [
                        'title' => 'Profil Perpustakaan',
                        'url' => 'admin/setting/profil_perpustakaan',
                        'page' => 'Profil Perpustakaan',
                    ],
                    [
                        'title' => 'Setting Apps',
                        'url' => 'admin/setting/apps',
                        'page' => 'Setting Apps',
                    ],
                    [
                        'title' => 'Admin Accounts',
                        'url' => 'admin/setting/admin',
                        'page' => 'Admin Accounts',
                    ],
                ];
                $pages = array_column($menuSettings, 'page');
            @endphp

            <li class="nav-item dropdown {{ in_array($sb, $pages) ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-cog"></i>
                    <span>Setting</span>
                </a>
                <ul class="dropdown-menu">
                    @foreach($menuSettings as $menu)
                        <li {{ $sb == $menu['page'] ? 'class=active' : '' }}>
                            <a class="nav-link" href="{{ url($menu['url']) }}">{{ $menu['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </aside>
</div>
