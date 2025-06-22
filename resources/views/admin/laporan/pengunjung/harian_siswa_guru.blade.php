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
                    <th>NAMA SISWA/GURU</th>
                    <th>KELAS</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d-m-Y') }}</td>
                        <td>{{ $item?->guruNik?->nama_guru ?? $item?->siswaNik?->nama_siswa }}</td>
                        <td>
                            @if ($item->nik_guru)
                                -
                            @else
                                Kelas
                                {{ ($item?->siswaNik?->kelas?->tingkat_kelas ?? '-') .
                                    ' ' .
                                    ($item?->siswaNik?->kelas?->kelompok ?? '-') .
                                    ' ( ' .
                                    ($item?->siswaNik?->kelas?->urusan_kelas ?? '-') .
                                    ' )' .
                                    ' ( Jurusan ' .
                                    ($item?->siswaNik?->kelas?->jurusan ?? '-') .
                                    ' )' }}
                            @endif
                        </td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
