@extends('master')
@section('title', 'Dashboard Admin - ')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <!-- Jumlah Anggota Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Jumlah Anggota</h4>
                                </div>
                                <div class="card-body">
                                    {{ $anggotaCount }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jumlah Buku Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Jumlah Buku</h4>
                                </div>
                                <div class="card-body">
                                    {{ $bukuCount }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Peminjaman Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Peminjaman Aktif</h4>
                                </div>
                                <div class="card-body">
                                    {{ $peminjamanCount }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pengembalian Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-undo"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Pengembalian Buku</h4>
                                </div>
                                <div class="card-body">
                                    {{ $pengembalianCount }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Buku Terbaru -->
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Buku Terbaru</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled list-unstyled-border">
                                    @foreach ($bukuTerbaru as $buku)
                                        <li class="media">
                                            <img class="mr-3 rounded" width="50" src="{{ asset($buku->cover_buku) }}"
                                                alt="avatar">
                                            <div class="media-body">
                                                <div class="float-right text-primary">
                                                    {{ $buku->created_at->diffForHumans() }}</div>
                                                <div class="media-title">{{ $buku->judul_buku }}</div>
                                                <span class="text-small text-muted">{{ $buku->penulis_buku }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Info</h4>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-primary alert-has-icon">
                                    <div class="alert-icon"><i class="far fa-solid fa-{{ $pengunjungHariIni }}"></i></div>
                                    <div class="alert-body">
                                        <div class="alert-title">Pengunjung Hari Ini</div>
                                    </div>
                                </div>
                                <div class="alert alert-info alert-has-icon">
                                    <div class="alert-icon"><i class="far fa-solid fa-{{ $peminjamHariIni }}"></i></div>
                                    <div class="alert-body">
                                        <div class="alert-title">Anggota Meminjam Buku Hari Ini</div>
                                    </div>
                                </div>
                                <div class="alert alert-warning alert-has-icon">
                                    <div class="alert-icon"><i class="far fa-solid fa-{{ $tempoHariIni }}"></i></div>
                                    <div class="alert-body">
                                        <div class="alert-title">Tempo Mengembalikan Buku</div>
                                    </div>
                                </div>
                                <div class="alert alert-danger alert-has-icon">
                                    <div class="alert-icon"><i class="far fa-solid fa-{{ $terlambatCount }}"></i></div>
                                    <div class="alert-body">
                                        <div class="alert-title">Terlambat Mengembalikan Buku</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pemberitahuan -->
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Pemberitahuan</h4>
                            </div>
                            <div class="card-body">
                                @foreach ($logSiswa as $log)
                                    <div class="alert alert-success alert-dismissible show fade">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert">
                                                <span>×</span>
                                            </button>
                                            {{ $log->aktivitas }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
