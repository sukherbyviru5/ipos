@extends('master')
@section('title', 'Laporan Data Buku - ')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporan Data Buku</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Laporan Data Buku</div>
                </div>
            </div>

            <div class="section-body">

                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Jumlah Buku</h4>
                                </div>
                                <div class="card-body">
                                    {{ $jumlah }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Laporan Data Buku</h4>
                        <div class="card-header-form">
                            <div class="dropdown d-inline dropleft">
                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle" aria-haspopup="true"
                                    data-toggle="dropdown" aria-expanded="false">
                                    Cetak Data
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" target="blank"
                                            href="{{ url('admin/laporan/buku/export/pdf') }}">PDF</a></li>
                                    <li><a class="dropdown-item" target="blank"
                                            href="{{ url('admin/laporan/buku/export/excel') }}">Excel</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Cover Buku</th>
                                    <th>Judul Buku</th>
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
                    url: "{{ url('admin/data-buku/all') }}",
                    type: "GET"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'cover_buku',
                        name: 'cover_buku',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul_buku',
                        name: 'judul_buku'
                    },
                ]
            });


        });
    </script>
@endsection
