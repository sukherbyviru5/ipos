@extends('layout.kop')
@section('title')
    REKAPITULASI LAPORAN PEMINJAMAN/PENGEMBALIAN BUKU SISWA <br>
    BULAN {{ $months[$monthStart] }} SAMPAI DENGAN {{ $months[$monthEnd] }} <br>
    TAHUN {{ $yearStart }} SAMPAI DENGAN {{ $yearEnd }}
@endsection

@section('content')
    <div class="title">
        <h2>REKAPITULASI LAPORAN PEMINJAMAN/PENGEMBALIAN BUKU SISWA <br>
            BULAN {{ strtoupper($months[$monthStart]) }} SAMPAI DENGAN {{ strtoupper($months[$monthEnd]) }} <br>
            TAHUN  {{ $yearStart == $yearEnd ? $yearEnd :  $yearStart . '-' . $yearEnd }} 
        </h2>
    </div>
    <div class="table-container">
        <table>
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
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $months[$month] }}</td>
                        <td>{{ $data['peminjaman'] ?? '--' }}</td>
                        <td>{{ $data['pengembalian'] ?? '--' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection