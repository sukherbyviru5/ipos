@extends('master')
@section('title', 'Data Buku - Detail Buku')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $buku->judul_buku }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ url('admin/data-buku') }}">Data Buku</a></div>
                    <div class="breadcrumb-item">Detail</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Detail Buku</h2>
                <p class="section-lead">Berisi informasi terkait detail buku.</p>
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
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" class="col-md-3">Kode Buku</th>
                                        <td>{{ $buku->kode_buku }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">DDC</th>
                                        <td>{{ $buku->ddc_buku->nama_klasifikasi ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Kategori</th>
                                        <td>{{ $buku->kategori_buku->nama_kategori ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Singkatan Buku</th>
                                        <td>{{ $buku->singkatan_buku ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">ISBN</th>
                                        <td>{{ $buku->isbn ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Judul Buku</th>
                                        <td>{{ $buku->judul_buku }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Penulis</th>
                                        <td>{{ $buku->penulis_buku ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Penerbit</th>
                                        <td>{{ $buku->penerbit_buku ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tempat Terbit</th>
                                        <td>{{ $buku->tempat_terbit ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tahun Terbit</th>
                                        <td>{{ $buku->tahun_terbit ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Asal Buku</th>
                                        <td>{{ $buku->asal_buku ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Sinopsis</th>
                                        <td>{{ $buku->sinopsis ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Kondisi</th>
                                        <td>{{ $buku->kondisi_buku->nama_kondisi ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Jenis Buku</th>
                                        <td>{{ $buku->jenis_buku->nama_jenis ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Harga Buku</th>
                                        <td>{{ $buku->harga_buku ? 'Rp ' . number_format($buku->harga_buku, 0, ',', '.') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Stok Buku</th>
                                        <td>{{ $buku->stok_buku }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Lokasi Lemari</th>
                                        <td>{{ $buku->lokasi_lemari ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Lokasi Rak</th>
                                        <td>{{ $buku->lokasi_rak ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Cover Buku</th>
                                        <td>
                                            @if ($buku->cover_buku)
                                                <img src="{{ asset($buku->cover_buku) }}" alt="Cover Buku" class="img-fluid my-2" style="max-width: 150px;">
                                            @else
                                                Tidak ada cover
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">E-Book Tersedia</th>
                                        <td>{{ $buku->ebook_tersedia ? 'Ya' : 'Tidak' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">File E-Book</th>
                                        <td>
                                            @if ($buku->ebook_file && $buku->ebook_tersedia)
                                                <a href="{{ asset($buku->ebook_file) }}" target="_blank">Lihat E-Book</a>
                                            @else
                                                Tidak tersedia
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Jumlah Dilihat</th>
                                        <td>{{ $buku->view_count }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tanggal Dibuat</th>
                                        <td>{{ $buku->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Terakhir Diperbarui</th>
                                        <td>{{ $buku->updated_at->format('d M Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="{{ url('admin/data-buku') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection