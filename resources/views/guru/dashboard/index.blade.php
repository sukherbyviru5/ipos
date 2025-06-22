@extends('guest')
@section('title', 'Dashboard')
@section('content')
    <br>
    <!-- Banner -->
    <div class="container">
        <div class="cta-text dir-rtl p-4 p-lg-5">
            <div class="row">
                <div class="col-9">
                    <h5 class="text-white">Selamat datang di perpustakaan digital {{ App\Models\SettingApp::first()->nama_madrasah }}.</h5>
                </div>
            </div>
            <img src="{{ asset('assets/img/book.png') }}" alt="">
        </div>
    </div>
    <div class="container">
        <div class="section-heading pt-3 rtl-text-right">
            <h6>Dashboard Guru</h6>
        </div>
        <div class="row g-2 rtl-flex-d-row-r">
            <!-- Article Approved -->
            <div class="col-6">
                <div class="card blog-catagory-card">
                    <div class="card-body">
                        <a>
                            <i class="ti ti-check"></i>
                            <span class="d-block">Artikel Disetujui</span>
                            <h4 class="mt-2">{{ $artikel_setuju }}</h4>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Article Rejected -->
            <div class="col-6">
                <div class="card blog-catagory-card">
                    <div class="card-body">
                        <a>
                            <i class="ti ti-x"></i>
                            <span class="d-block">Artikel Ditolak</span>
                            <h4 class="mt-2">{{ $artikel_tolak }}</h4>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Article Draft -->
            <div class="col-6">
                <div class="card blog-catagory-card">
                    <div class="card-body">
                        <a>
                            <i class="ti ti-edit"></i>
                            <span class="d-block">Artikel Draft</span>
                            <h4 class="mt-2">{{ $artikel_draft }}</h4>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Active Loans -->
            <div class="col-6">
                <div class="card blog-catagory-card">
                    <div class="card-body">
                        <a href="{{ url('guru/peminjaman') }}">
                            <i class="ti ti-book"></i>
                            <span class="d-block">Peminjaman Aktif</span>
                            <h4 class="mt-2">{{ $peminjaman_belum_kembali }}</h4>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
@endsection