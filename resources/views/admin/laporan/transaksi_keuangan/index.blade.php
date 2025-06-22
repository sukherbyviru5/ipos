@extends('master')
@section('title', 'Data Transaksi Keuangan')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Manajemen Keuangan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Pendataan Unit Keuangan</div>
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
                        <form id="filterForm" method="GET" action="{{ url('admin/laporan/transaksi-keuangan') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <select name="year" id="year" class="form-control">
                                            @foreach ($years as $y)
                                                <option value="{{ $y }}"
                                                    {{ $y == $selectedYear ? 'selected' : '' }}>{{ $y }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bulan</label>
                                        <select name="month" id="month" class="form-control">
                                            @foreach ($months as $m => $name)
                                                <option value="{{ $m }}"
                                                    {{ $m == $selectedMonth ? 'selected' : '' }}>{{ $name }}
                                                </option>
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

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats">
                                <div class="card-stats-title">Keuangan Tahun {{ $selectedYear }}</div>
                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" style="font-size: 15px;">Rp
                                            {{ number_format($stats['denda'], 0, ',', '.') }}</div>
                                        <div class="card-stats-item-label">Pembayaran Denda</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" style="font-size: 15px;">Rp
                                            {{ number_format($stats['kredit'], 0, ',', '.') }}</div>
                                        <div class="card-stats-item-label">Pemasukan Kas</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" style="font-size: 15px;">Rp
                                            {{ number_format($stats['debit'], 0, ',', '.') }}</div>
                                        <div class="card-stats-item-label">Pengeluaran Kas</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon shadow-success bg-success">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Saldo</h4>
                                </div>
                                <div class="card-body">
                                    Rp {{ number_format($stats['saldo'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats">
                                <div class="card-stats-title">Keuangan Bulan {{ $months[$selectedMonth] }}
                                    {{ $selectedYear }}</div>
                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" style="font-size: 15px;">Rp
                                            {{ number_format($stats['month_kredit'], 0, ',', '.') }}</div>
                                        <div class="card-stats-item-label">Pemasukan</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" style="font-size: 15px;">Rp
                                            {{ number_format($stats['month_debit'], 0, ',', '.') }}</div>
                                        <div class="card-stats-item-label">Pengeluaran</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" style="font-size: 15px;">Rp
                                            {{ number_format($stats['month_saldo'], 0, ',', '.') }}</div>
                                        <div class="card-stats-item-label">Saldo</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon shadow-info bg-info">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Saldo</h4>
                                </div>
                                <div class="card-body">
                                    Rp {{ number_format($stats['month_saldo'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Pemasukan Kas</h4>
                                </div>
                                <div class="card-body">
                                    Rp {{ number_format($stats['kredit'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Pengeluaran Kas</h4>
                                </div>
                                <div class="card-body">
                                    Rp {{ number_format($stats['debit'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Jumlah Kas</h4>
                                </div>
                                <div class="card-body">
                                    Rp {{ number_format($stats['saldo'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Table -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Data Seluruh Transaksi Keuangan</h4>
                        <div class="card-header-form">
                            <div class="dropdown d-inline dropleft">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                    aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                                    Tambah Data
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" data-toggle="modal" data-target="#addCreditModal"
                                            href="#">Kredit</a></li>
                                    <li><a class="dropdown-item" data-toggle="modal" data-target="#addDebitModal"
                                            href="#">Debit</a></li>
                                </ul>
                            </div>
                            <div class="dropdown d-inline dropleft">
                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle" aria-haspopup="true"
                                    data-toggle="dropdown" aria-expanded="false">
                                    Cetak Data
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" target="blank"
                                            href="{{ url('admin/laporan/transaksi-keuangan/export/pdf') }}?year={{ $selectedYear }}&month={{ $selectedMonth }}">PDF</a></li>
                                    <li><a class="dropdown-item" target="blank"
                                            href="{{ url('admin/laporan/transaksi-keuangan/export/excel') }}?year={{ $selectedYear }}&month={{ $selectedMonth }}">Excel</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped mt-5">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Uraian</th>
                                    <th>Nominal</th>
                                    <th>Tipe</th>
                                    <th>Sumber</th>
                                    <th>Tanggal</th>
                                    <th width="10px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Add Debit Modal -->
        <div class="modal fade" id="addDebitModal" tabindex="-1" aria-labelledby="addDebitModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDebitModal">Tambah Transaksi Debit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ url('admin/laporan/transaksi-keuangan') }}" method="POST" class="needs-validation"
                        novalidate="">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Uraian</label>
                                <input type="text" placeholder="Masukkan Uraian" class="form-control" name="uraian"
                                    required="">
                                <div class="invalid-feedback">
                                    Masukkan Uraian Transaksi
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nominal</label>
                                <input type="number" placeholder="Masukkan Nominal" class="form-control" name="nominal"
                                    required="">
                                <div class="invalid-feedback">
                                    Masukkan Nominal
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Sumber</label>
                                <select name="sumber" class="form-control" required="">
                                    <option value="kas">Kas</option>
                                    <option value="denda">Denda</option>
                                </select>
                                <div class="invalid-feedback">
                                    Pilih Sumber
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" required="" value="{{ date('Y-m-d') }}">
                                <div class="invalid-feedback">
                                    Masukkan Tanggal
                                </div>
                            </div>
                            <input type="hidden" name="type" value="debit">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Credit Modal -->
        <div class="modal fade" id="addCreditModal" tabindex="-1" aria-labelledby="addCreditModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCreditModal">Tambah Transaksi Kredit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ url('admin/laporan/transaksi-keuangan') }}" method="POST" class="needs-validation"
                        novalidate="">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Uraian</label>
                                <input type="text" placeholder="Masukkan Uraian" class="form-control" name="uraian"
                                    required="">
                                <div class="invalid-feedback">
                                    Masukkan Uraian Transaksi
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nominal</label>
                                <input type="number" placeholder="Masukkan Nominal" class="form-control" name="nominal"
                                    required="">
                                <div class="invalid-feedback">
                                    Masukkan Nominal
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Sumber</label>
                                <select name="sumber" class="form-control" required="">
                                    <option value="kas">Kas</option>
                                    <option value="denda">Denda</option>
                                </select>
                                <div class="invalid-feedback">
                                    Pilih Sumber
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" required="" value="{{ date('Y-m-d') }}">
                                <div class="invalid-feedback">
                                    Masukkan Tanggal
                                </div>
                            </div>
                            <input type="hidden" name="type" value="kredit">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Modal -->
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModal">Update Transaksi Keuangan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ url('admin/laporan/transaksi-keuangan/update') }}" method="POST"
                        class="needs-validation" novalidate="">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="id" id="id">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Uraian</label>
                                <input type="text" placeholder="Masukkan Uraian" class="form-control" name="uraian"
                                    required="" id="uraian">
                                <div class="invalid-feedback">
                                    Masukkan Uraian Transaksi
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nominal</label>
                                <input type="number" placeholder="Masukkan Nominal" class="form-control" name="nominal"
                                    required="" id="nominal">
                                <div class="invalid-feedback">
                                    Masukkan Nominal
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tipe</label>
                                <select name="type" class="form-control" required="" id="type">
                                    <option value="debit">Debit</option>
                                    <option value="kredit">Kredit</option>
                                </select>
                                <div class="invalid-feedback">
                                    Pilih Tipe Transaksi
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Sumber</label>
                                <select name="sumber" class="form-control" required="" id="sumber">
                                    <option value="kas">Kas</option>
                                    <option value="denda">Denda</option>
                                </select>
                                <div class="invalid-feedback">
                                    Pilih Sumber
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" required=""
                                    id="tanggal">
                                <div class="invalid-feedback">
                                    Masukkan Tanggal
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/laporan/transaksi-keuangan/all') }}",
                    type: "GET",
                    data: function(d) {
                        d.year = $('#year').val();
                        d.month = $('#month').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'uraian',
                        name: 'uraian',
                        render: function(data, type, row) {
                            if (data.length > 30) {
                                const shortText = data.substr(0, 30);
                                return `
                                    <span class="uraian-wrapper" style="max-width: 150px;">
                                        <span class="uraian-short">${shortText}... <a href="#" class="read-more" data-full-text="${data}">[Read More]</a></span>
                                        <span class="uraian-full" style="display: none;">${data} <a href="#" class="read-less" data-short-text="${shortText}">[Read Less]</a></span>
                                    </span>
                                `;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                    {
                        data: 'type', 
                        name: 'type'
                    },
                    {
                        data: 'sumber',
                        name: 'sumber'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('.table').on('click', '.read-more', function (e) {
                e.preventDefault();
                const wrapper = $(this).closest('.uraian-wrapper');
                wrapper.find('.uraian-short').hide();
                wrapper.find('.uraian-full').show();
            });

            $('.table').on('click', '.read-less', function (e) {
                e.preventDefault();
                const wrapper = $(this).closest('.uraian-wrapper');
                wrapper.find('.uraian-full').hide();
                wrapper.find('.uraian-short').show();
            });

            $('#year, #month').on('change', function() {
                $('#filterForm').submit();
            });

            $('.table').on('click', '.edit[data-id]', function(e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        'id': $(this).data('id'),
                        '_token': "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ url('admin/laporan/transaksi-keuangan/get') }}",
                    beforeSend: function() {
                        $.LoadingOverlay("show", {
                            image: "",
                            fontawesome: "fa fa-cog fa-spin"
                        });
                    },
                    complete: function() {
                        $.LoadingOverlay("hide");
                    },
                    success: function(data) {
                        $('#id').val(data.id);
                        $('#uraian').val(data.uraian);
                        $('#nominal').val(data.nominal);
                        $('#type').val(data.type);
                        $('#sumber').val(data.sumber);
                        $('#tanggal').val(data.tanggal);
                        $('#updateModal').modal('show');
                    },
                    error: function(err) {
                        alert('Error: ' + err.responseText);
                        console.log(err);
                    }
                });
            });

            $('.table').on('click', '.hapus[data-id]', function(e) {
                e.preventDefault();
                swal({
                    title: "Hapus Transaksi Keuangan?",
                    text: "Data transaksi ini akan dihapus secara permanen!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            data: {
                                'id': $(this).data('id'),
                                '_token': "{{ csrf_token() }}"
                            },
                            type: 'DELETE',
                            url: "{{ url('admin/laporan/transaksi-keuangan') }}",
                            beforeSend: function() {
                                $.LoadingOverlay("show", {
                                    image: "",
                                    fontawesome: "fa fa-cog fa-spin"
                                });
                            },
                            complete: function() {
                                $.LoadingOverlay("hide");
                            },
                            success: function(data) {
                                swal(data.message).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(err) {
                                alert('Error: ' + err.responseText);
                                console.log(err);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection