@extends('master')
@section('title', 'Laporan Pengunjung - ')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporan Pengunjung</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Laporan Pengunjung</div>
                </div>
            </div>

            <div class="section-body">
                {{-- CARD FILTER --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Cetak Laporan Kunjungan</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengunjung.cetak') }}" method="GET" target="_blank">
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-3">
                                    <label for="report_type">Jenis Laporan</label>
                                    <select name="report_type" id="report_type" class="form-control">
                                        <optgroup label="Harian">
                                            <option value="harian_kelas"
                                                {{ request('report_type') == 'harian_kelas' ? 'selected' : '' }}>
                                                Harian Pengunjung Kelas
                                            </option>
                                            <option value="harian_siswa_guru"
                                                {{ request('report_type') == 'harian_siswa_guru' ? 'selected' : '' }}>
                                                Harian Siswa/Guru
                                            </option>
                                        </optgroup>
                                        <optgroup label="Bulanan">
                                            <option value="grafik_pengunjung"
                                                {{ request('report_type') == 'grafik_pengunjung' ? 'selected' : '' }}>
                                                Grafik Pengunjung Bulanan
                                            </option>
                                            <option value="rekap_bulanan"
                                                {{ request('report_type') == 'rekap_bulanan' ? 'selected' : '' }}>
                                                Rekapitulasi Bulanan
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>

                                @php
                                    $now = now();
                                    $defaultMonthStart = request('month_start') ?? $now->month;
                                    $defaultMonthEnd = request('month_end') ?? $now->copy()->addMonth()->month;
                                    $defaultYear = request('year') ?? $now->year;
                                @endphp

                                <div class="form-group col-md-2">
                                    <label for="month_start">Dari Bulan</label>
                                    <select name="month_start" class="form-control">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}"
                                                {{ $defaultMonthStart == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="month_end">Sampai Bulan</label>
                                    <select name="month_end" class="form-control">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}"
                                                {{ $defaultMonthEnd == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="year">Tahun</label>
                                    <select name="year" class="form-control">
                                        @for ($y = now()->year - 5; $y <= now()->year + 1; $y++)
                                            <option value="{{ $y }}"
                                                {{ $defaultYear == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>


                                <div class="form-group col-md-3">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Cetak Laporan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
