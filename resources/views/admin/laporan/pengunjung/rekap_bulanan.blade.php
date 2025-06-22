@extends('layout.kop')
@section('title', 'Laporan Pengunjung - Rekap Bulanan')
@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h4>
                            REKAPITULASI LAPORAN BULANAN PENGUNJUNG <br>
                            PERPUSTAKAAN {{ strtoupper($kop->nama_madrasah ?? 'Nama Madrasah') }} <br>
                            BULAN {{ strtoupper(\Carbon\Carbon::create()->month($monthStart)->translatedFormat('F')) }} s/d {{ strtoupper(\Carbon\Carbon::create()->month($monthEnd)->translatedFormat('F')) }} {{ $year }}
                        </h4>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2">BULAN</th>
                                    <th colspan="31" style="text-align: center;">TANGGAL</th>
                                    <th rowspan="2">JUMLAH</th>
                                </tr>
                                <tr>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <th>{{ $day }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($months as $monthName => $days)
                                    <tr>
                                        <td>{{ $monthName }}</td>
                                        @for ($day = 1; $day <= 31; $day++)
                                            <td>{{ $days[$day] ?? '-' }}</td>
                                        @endfor
                                        <td>{{ $monthlyTotals[$monthName] }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="32" style="text-align: center; font-weight: bold;">JUMLAH</td>
                                    <td style="font-weight: bold;">{{ $grandTotal }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection