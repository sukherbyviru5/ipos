@extends('layout.kop')
@section('title')
    LAPORAN AKTIVITAS SISWA <br>{{ $kop->nama_madrasah ?? 'Nama Madrasah' }}
@endsection

@section('content')
    <div style="text-align: center; margin-bottom: 20px;">
        <h4>TANGGAL {{ strtoupper(Carbon\Carbon::parse($date_start)->isoFormat('DD MMMM Y')) }} SAMPAI DENGAN {{ strtoupper(Carbon\Carbon::parse($date_end)->isoFormat('DD MMMM Y')) }} <br>
            TAHUN {{ Carbon\Carbon::parse($date_start)->year == Carbon\Carbon::parse($date_end)->year ? Carbon\Carbon::parse($date_end)->year : Carbon\Carbon::parse($date_start)->year . '-' . Carbon\Carbon::parse($date_end)->year }}
        </h4>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Nama Siswa</th>
                    <th style="text-align: center">Judul Buku</th>
                    <th style="text-align: center">Aktivitas</th>
                    <th style="text-align: center">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($query as $index => $log)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $log->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $log->buku->judul_buku ?? '-' }}</td>
                        <td>{{ $log->aktivitas ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada data aktivitas siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
   
@endsection