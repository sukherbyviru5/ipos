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
            <a href="#">GURU</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">GU</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li {{($sb == 'Dashboard') ? "class=active" : ""}}>
                <a class="nav-link" href="{{ url('guru') }}"><i class="fas fa-fire"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">Menu Guru</li>
            <li {{($sb == 'Bank Soal') ? "class=active" : ""}}>
                <a class="nav-link" href="{{ url('guru/folder') }}"><i class="fa-solid fa-folder-open"></i>
                    <span>Bank Soal</span>
                </a>
            </li>
            <li {{($sb == 'Data Mapel') ? "class=active" : ""}}>
                <a class="nav-link" href="{{ url('guru/mapel') }}"><i class="fa-solid fa-book"></i>
                    <span>Data Mapel</span>
                </a>
            </li>
            <li class="menu-header">Ulangan Harian</li>
            <li
                class="nav-item dropdown {{ $sb == 'Mapel Harian' || $sb == 'Jadwal' || $sb == 'Nilai' || $sb == 'Nilai Ulangan' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fa-sharp fa-solid fa-database"></i>
                    <span>Ulangan Harian</span>
                </a>
                <ul class="dropdown-menu">
                    <li {{ $sb == 'Mapel Harian' ? 'class=active' : '' }}><a class="nav-link"
                            href="{{ url('guru/ulangan/mapel') }}">Mapel Ulangan Harian</a></li>
                    <li {{ $sb == 'Jadwal' ? 'class=active' : '' }}><a class="nav-link"
                            href="{{ url('guru/ulangan/jadwal') }}">Jadwal</a></li>
                    <li {{ $sb == 'Nilai Ulangan' ? 'class=active' : '' }}><a class="nav-link"
                            href="{{ url('guru/ulangan/mark') }}">Ujian</a></li>
                </ul>
            </li>
            <li {{($sb == 'Nilai Ujian') ? "class=active" : ""}}>
                <a class="nav-link" href="{{ url('guru/mark/mapel') }}"><i class="fa-solid fa-star"></i>
                    <span>Nilai Ujian</span>
                </a>
            </li>
            <li class="menu-header">Menu Pengawas</li>
            <li {{($sb == 'Ujian') ? "class=active" : ""}}>
                <a class="nav-link" href="{{ url('guru/ujian') }}"><i class="fa-solid fa-globe"></i>
                    <span>Ujian</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
