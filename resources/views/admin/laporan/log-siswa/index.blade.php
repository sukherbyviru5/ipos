@extends('master')
@section('title', 'Laporan Aktivitas Siswa - ')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporan Aktivitas Siswa</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Laporan Aktivitas Siswa</div>
                </div>
            </div>

            <div class="section-body">
                {{-- CARD FILTER --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Filter Laporan</h4>
                    </div>
                    <div class="card-body">
                        <form id="filter-form" action="{{ url('admin/laporan/log-siswa') }}" method="GET">
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-3 mb-2">
                                    <label for="nik_siswa">Pilih Siswa</label>
                                    <select name="nik_siswa" id="nik_siswa" class="form-control select2">
                                        <option value="">Semua Siswa</option>
                                        @foreach ($siswa as $s)
                                            <option value="{{ $s->nik }}"
                                                {{ $selected_nik == $s->nik ? 'selected' : '' }}>
                                                {{ $s->nama_siswa }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3 mb-2">
                                    <label for="date_start">Dari Tanggal</label>
                                    <input type="date" name="date_start" id="date_start" class="form-control"
                                        value="{{ $date_start ?? '' }}">
                                </div>
                                <div class="form-group col-md-3 mb-2">
                                    <label for="date_end">Sampai Tanggal</label>
                                    <input type="date" name="date_end" id="date_end" class="form-control"
                                        value="{{ $date_end ?? '' }}">
                                </div> 
                                <div class="form-group col-md-2 mb-2">
                                    <button type="submit" class="btn btn-primary btn-block">
                                      Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">

                    <div class="card-header">
                        <h4>Laporan Aktivitas Siswa</h4>
                        <div class="card-header-form">
                            <form id="filter-form" action="{{ url('admin/laporan/log-siswa') }}" method="GET">
                                <div class="input-group">
                                    <div class="dropdown d-inline dropleft">
                                        <button type="button" class="btn btn-danger btn-sm dropdown-toggle"
                                            aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                                            Cetak Data
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" target="_blank"
                                                    href="{{ url('admin/laporan/log-siswa/export/pdf') . '?' . http_build_query(request()->only(['nik_siswa', 'date_start', 'date_end'])) }}">PDF</a>
                                            </li>
                                            <li><a class="dropdown-item" target="_blank"
                                                    href="{{ url('admin/laporan/log-siswa/export/excel') . '?' . http_build_query(request()->only(['nik_siswa', 'date_start', 'date_end'])) }}">Excel</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Nama Siswa</th>
                                    <th>Judul Buku</th>
                                    <th>Aktivitas</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/laporan/log-siswa/all') }}",
                    type: "GET",
                    data: function(d) {
                        d.nik_siswa = $('select[name="nik_siswa"]').val();
                        d.date_start = $('input[name="date_start"]').val();
                        d.date_end = $('input[name="date_end"]').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_siswa',
                        name: 'nama_siswa'
                    },
                    {
                        data: 'judul_buku',
                        name: 'judul_buku'
                    },
                    {
                        data: 'aktivitas',
                        name: 'aktivitas'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                ]
            });
        });
    </script>
@endsection
