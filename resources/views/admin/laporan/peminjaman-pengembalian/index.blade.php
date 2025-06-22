@extends('master')
@section('title', 'Laporan Peminjaman Pengembalian')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporan Peminjaman Pengembalian</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Peminjaman Pengembalian</div>
                </div>
            </div>

            <div class="section-body">
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session()->get('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session()->get('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif

                <!-- Global Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="filterForm" method="GET" action="{{ url('admin/laporan/peminjaman-pengembalian') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tahun Awal</label>
                                        <select name="year_start" id="year_start" class="form-control">
                                            @foreach ($years as $y)
                                                <option value="{{ $y }}" {{ $y == $selectedYearStart ? 'selected' : '' }}>{{ $y }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bulan Awal</label>
                                        <select name="month_start" id="month_start" class="form-control">
                                            @foreach ($months as $m => $name)
                                                <option value="{{ $m }}" {{ $m == $selectedMonthStart ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tahun Akhir</label>
                                        <select name="year_end" id="year_end" class="form-control">
                                            @foreach ($years as $y)
                                                <option value="{{ $y }}" {{ $y == $selectedYearEnd ? 'selected' : '' }}>{{ $y }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bulan Akhir</label>
                                        <select name="month_end" id="month_end" class="form-control">
                                            @foreach ($months as $m => $name)
                                                <option value="{{ $m }}" {{ $m == $selectedMonthEnd ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Recapitulation Table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Rekapitulasi Peminjaman/Pengembalian Buku Siswa</h4>
                        <div class="card-header-form">
                            <div class="dropdown d-inline dropleft">
                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                                    Cetak Data
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" target="_blank" href="{{ url('admin/laporan/peminjaman-pengembalian/export/pdf?year_start=' . $selectedYearStart . '&month_start=' . $selectedMonthStart . '&year_end=' . $selectedYearEnd . '&month_end=' . $selectedMonthEnd . '&type=rekap') }}">PDF</a></li>
                                    <li><a class="dropdown-item" target="_blank" href="{{ url('admin/laporan/peminjaman-pengembalian/export/excel?year_start=' . $selectedYearStart . '&month_start=' . $selectedMonthStart . '&year_end=' . $selectedYearEnd . '&month_end=' . $selectedMonthEnd . '&type=rekap') }}">Excel</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th>Jumlah Peminjaman</th>
                                    <th>Jumlah Pengembalian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekapitulasi as $month => $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $months[$month] }}</td>
                                        <td>{{ $data['peminjaman'] ?? '--' }}</td>
                                        <td>{{ $data['pengembalian'] ?? '--' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detail Table -->
                <div class="card">
                    <div class="card-header">
                        <h4>Detail Peminjaman/Pengembalian Buku Siswa</h4>
                        <div class="card-header-form">
                            <div class="dropdown d-inline dropleft">
                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                                    Cetak Data
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" target="_blank" href="{{ url('admin/laporan/peminjaman-pengembalian/export/pdf?year_start=' . $selectedYearStart . '&month_start=' . $selectedMonthStart . '&year_end=' . $selectedYearEnd . '&month_end=' . $selectedMonthEnd . '&type=detail') }}">PDF</a></li>
                                    <li><a class="dropdown-item" target="_blank" href="{{ url('admin/laporan/peminjaman-pengembalian/export/excel?year_start=' . $selectedYearStart . '&month_start=' . $selectedMonthStart . '&year_end=' . $selectedYearEnd . '&month_end=' . $selectedMonthEnd . '&type=detail') }}">Excel</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="detailTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Peminjaman</th>
                                    <th>Tanggal Pengembalian</th>
                                    <th>Keterangan</th>
                                    <th>Denda</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $detail->siswa->nama_siswa ?? '-' }}</td>
                                        <td>{{ $detail->qrBuku->buku->judul_buku ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($detail->tanggal_pinjam)->format('d/m/Y') }}</td>
                                        <td>{{ $detail->tanggal_kembali ? \Carbon\Carbon::parse($detail->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            @if ($detail->status_peminjaman == 'dikembalikan' && !$detail->denda?->jumlah_denda)
                                               Sudah Dikembalikan
                                            @elseif ($detail->status_peminjaman == 'telat' || $detail->denda?->jumlah_denda)
                                                {{ $detail->status_peminjaman == 'telat' ? 'Terlambat' : ($detail->status_peminjaman == 'dikembalikan' ? 'Sudah Dikembalikan' : $detail->status_peminjaman) }}
                                                @if ($detail->denda?->jumlah_denda)
                                                    - Denda Rp {{ number_format($detail->denda->jumlah_denda, 0, ',', '.') }} ({{ $detail->denda->status_denda == 'lunas' ? 'Lunas' : 'Belum Lunas' }})
                                                @endif
                                            @else
                                                {{ $detail->status_peminjaman }}
                                            @endif
                                        </td>
                                        <td>{{ $detail->denda?->jumlah_denda ? 'Rp ' . number_format($detail->denda->jumlah_denda, 0, ',', '.') : 'Rp 0' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            $('#detailTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'asc']]
            });

            $('#year_start, #month_start, #year_end, #month_end').on('change', function() {
                $('#filterForm').submit();
            });
        });
    </script>
@endsection