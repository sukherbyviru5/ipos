@extends('layout.kop')
@section('title')
    DETAIL LAPORAN PEMINJAMAN/PENGEMBALIAN BUKU SISWA <br>
    BULAN {{ $months[$monthStart] }} SAMPAI DENGAN {{ $months[$monthEnd] }} <br>
    TAHUN {{ $yearStart }} SAMPAI DENGAN {{ $yearEnd }}
@endsection

@section('content')
    <div class="title">
        <h2> DETAIL LAPORAN PEMINJAMAN/PENGEMBALIAN BUKU SISWA <br>
            BULAN {{ strtoupper($months[$monthStart]) }} SAMPAI DENGAN {{ strtoupper($months[$monthEnd]) }} <br>
            TAHUN {{ $yearStart == $yearEnd ? $yearEnd : $yearStart . '-' . $yearEnd }}
        </h2>
    </div>
    <div class="table-container">
        <table>
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
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $detail->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $detail->qrBuku->buku->judul_buku ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td>{{ $detail->tanggal_kembali ? \Carbon\Carbon::parse($detail->tanggal_kembali)->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            @if ($detail->status_peminjaman == 'dikembalikan' && !$detail->denda?->jumlah_denda)
                               Sudah Dikembalikan
                            @elseif ($detail->status_peminjaman == 'telat' || $detail->denda?->jumlah_denda)
                                {{ $detail->status_peminjaman == 'telat' ? 'Terlambat' : ($detail->status_peminjaman == 'dikembalikan' ? 'Sudah Dikembalikan' : $detail->status_peminjaman) }}
                                @if ($detail->denda?->jumlah_denda)
                                    - Denda Rp {{ number_format($detail->denda->jumlah_denda, 0, ',', '.') }}
                                    ({{ $detail->denda->status_denda == 'lunas' ? 'Lunas' : 'Belum Lunas' }})
                                @endif
                            @else
                                {{ $detail->status_peminjaman }}
                            @endif
                        </td>
                        <td>{{ $detail->denda?->jumlah_denda ? 'Rp ' . number_format($detail->denda->jumlah_denda, 0, ',', '.') : 'Rp 0' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
