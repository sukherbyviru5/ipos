@extends('master')
@section('title', 'Detail Pinjaman Buku - Detail Buku')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $peminjaman?->qrBuku?->buku?->judul_buku }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ url('/admin/peminjaman/peminjaman-siswa/') }}">Detail Pinjaman Buku</a></div>
                    <div class="breadcrumb-item">Detail</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Detail Buku</h2>
                <p class="section-lead">Berisi informasi terkait detail buku dan pinjaman buku.</p>
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="section-title">Detail Pinjaman</div>
                        <div class="row">
                            <!-- Cover Buku -->
                            <div class="col-md-4 text-center">
                                @if ($peminjaman?->qrBuku?->buku->cover_buku)
                                    <img src="{{ asset($peminjaman?->qrBuku?->buku->cover_buku) }}" alt="Cover Buku"
                                        class="img-fluid mb-3"
                                        style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px;">
                                @else
                                    <p>Tidak ada cover</p>
                                @endif
                            </div>

                            <!-- Detail Buku -->
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th>Kode Buku</th>
                                                <td>{{ $peminjaman?->qrBuku?->kode }}</td>
                                            </tr>
                                            <tr>
                                                <th>Judul Buku</th>
                                                <td>{{ $peminjaman?->qrBuku?->buku?->judul_buku }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kode Pinjaman</th>
                                                <td>{{ $peminjaman->kode ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Grup Pinjaman</th>
                                                <td>{{ $peminjaman->grup ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tgl Pinjaman</th>
                                                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tgl Tempo Pinjaman</th>
                                                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_jatuh_tempo)->format('d M Y') ?? 'N/A' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Tgl Kembali</th>
                                                <td>{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>

                                                <th>Status Pinjaman</th>
                                                <td>
                                                    @if ($peminjaman->status_peminjaman == 'dipinjam')
                                                        <span class="badge badge-success">Masih Dipinjam</span>
                                                    @elseif ($peminjaman->status_peminjaman == 'dikembalikan')
                                                        <span class="badge badge-secondary">Sudah Dikembalikan</span>
                                                    @else
                                                        <span class="badge badge-danger">Telat dan Belum
                                                            dikembalikan</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="section-title">Detail Peminjam</div>
                        <div class="row">
                            <!-- profil -->
                            <div class="col-md-4 text-center">
                                @if ($peminjaman?->siswa?->foto)
                                    <img src="{{ asset($peminjaman?->siswa?->foto) }}" alt="profil" class="img-fluid mb-3"
                                        style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px;">
                                @else
                                    <p>Tidak ada profil siswa</p>
                                @endif
                            </div>

                            <!-- Detail Buku -->
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th>Nama Siswa</th>
                                                <td>{{ $peminjaman?->siswa?->nama_siswa }}</td>
                                            </tr>
                                            <tr>
                                                <th>NIK</th>
                                                <td>{{ $peminjaman?->siswa?->nik }}</td>
                                            </tr>
                                            <tr>
                                                <th>NISN</th>
                                                <td>{{ $peminjaman?->siswa?->nisn ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kelas</th>
                                                <td>{{ $peminjaman?->siswa?->kelas?->tingkat_kelas }} {{ $peminjaman?->siswa?->kelas?->kelompok }}
                                                    ({{ $peminjaman?->siswa?->kelas?->urusan_kelas }})
                                                    ( Jurusan {{ $peminjaman?->siswa?->kelas?->jurusan }} ) </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ url('/admin/peminjaman/peminjaman-siswa/') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
