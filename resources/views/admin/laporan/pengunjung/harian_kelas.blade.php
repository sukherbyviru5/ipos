@extends('layout.kop')
@section('title')
    LAPORAN {{ strtoupper(str_replace('_', ' ', $reportType)) }} PERPUSTAKAAN <br>
    BULAN {{ strtoupper(\Carbon\Carbon::create()->month($monthStart)->translatedFormat('F')) }} -
    {{ strtoupper(\Carbon\Carbon::create()->month($monthEnd)->translatedFormat('F')) }} {{ $year }}
@endsection

@section('content')
   
    <div style="text-align: center; margin-bottom: 20px;">
        <h4>
            LAPORAN {{ strtoupper(str_replace('_', ' ', $reportType)) }} PERPUSTAKAAN <br>
            BULAN {{ strtoupper(\Carbon\Carbon::create()->month($monthStart)->translatedFormat('F')) }} -
            {{ strtoupper(\Carbon\Carbon::create()->month($monthEnd)->translatedFormat('F')) }} {{ $year }}
        </h4>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>HARI/TGL</th>
                    <th>NAMA GURU</th>
                    <th>MAPEL</th>
                    <th>KELAS</th>
                    <th>MATERI PELAJARAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d-m-Y') }}</td>
                        <td>{{ $item->guru->nama_guru ?? '-' }}</td>
                        <td>{{ $item->guru->nama_mata_pelajaran ?? '-' }}</td>
                        <td>Kelas 
                            {{ 
                                ($item->kelas->tingkat_kelas ?? '-') . ' ' . 
                                ($item->kelas->kelompok ?? '-') . 
                                ' ( ' . ($item->kelas->urusan_kelas ?? '-') . ' )' . 
                                ' ( Jurusan ' . ($item->kelas->jurusan ?? '-') . ' )' 
                            }}
                        </td>
                        <td>{{ $item->materi ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
