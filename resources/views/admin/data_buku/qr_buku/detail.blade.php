@extends('master')
@section('title', 'Data Buku - Qr Code Buku')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $buku->judul_buku }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ url('admin/data-buku') }}">Data Buku</a></div>
                    <div class="breadcrumb-item">Qr Code</div>
                </div>

            </div>

            <div class="section-body">
                <h2 class="section-title">Qr Code Buku</h2>
                <p class="section-lead">Berisi informasi terkait Qr Code buku.</p>
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="section-header-actions d-flex justify-content-between align-items-center">
                            <button type="button" id="printSelectedBtn" class="btn btn-primary">Print Terpilih (0)</button>
                            <div class="search-box">
                                <input type="text" id="searchInput" placeholder="Cari Kode QR..." class="form-control">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <form id="formPrintQr" method="get" action="{{ url('admin/data-buku/qr-buku/print') }}"
                                class="mt-4">
                                <input type="hidden" name="id" value="{{ $buku->id }}">
                                <div class="overflow-auto">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="10">
                                                    <div class="custom-checkbox custom-control">
                                                        <input type="checkbox" class="custom-control-input" id="parent">
                                                        <label for="parent" class="custom-control-label"></label>
                                                    </div>
                                                </th>
                                                <th>Kode QR</th>
                                                <th>QR Code</th>
                                            </tr>
                                        </thead>
                                        <tbody id="qrTable">
                                            @foreach ($qrBuku as $qr)
                                                <tr data-kode="{{ $qr->kode }}">
                                                    <td class="p-0 text-center">
                                                        <div class="custom-checkbox custom-control">
                                                            <input type="checkbox" name="qr_ids[]"
                                                                class="custom-control-input child"
                                                                id="qr_{{ $qr->id }}" value="{{ $qr->id }}">
                                                            <label for="qr_{{ $qr->id }}"
                                                                class="custom-control-label"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $qr->kode }}</td>
                                                    <td>
                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($qr->kode) }}"
                                                            alt="QR Code {{ $qr->kode }}" class="img-fluid my-2"
                                                            style="max-width: 100px;">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                        <div class="mt-3">
                            <a href="{{ url('admin/data-buku') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            $('#parent').on('change', function() {
                $('.child').prop('checked', $(this).prop('checked'));
                updateSelectedCount();
            });

            $('.child').on('change', function() {
                updateSelectedCount();
            });

            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#qrTable tr').filter(function() {
                    $(this).toggle($(this).data('kode').toLowerCase().indexOf(value) > -1 || !$(
                        this).data('kode'));
                });
            });

            $('#printSelectedBtn').on('click', function() {
                var qrIds = $('.child:checked').map(function() {
                    return $(this).val();
                }).get();
                if (qrIds.length > 0) {
                    $('#formPrintQr').submit();
                } else if ($('#qrTable tr').length === 1 && $('#qrTable tr td').text().includes(
                        'Tidak ada QR Code')) {
                    var win = window.open('', '_blank');
                    win.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Tidak Ada QR Code</title>
                            <style>
                                body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
                            </style>
                        </head>
                        <body>
                            <h2>Tidak Ada QR Code Tersedia untuk Dicetak</h2>
                        </body>
                        </html>
                    `);
                    win.document.close();
                } else {
                    alert('Pilih setidaknya satu QR Code untuk mencetak.');
                }
            });

            function updateSelectedCount() {
                var count = $('.child:checked').length;
                $('#printSelectedBtn').text('Print Terpilih (' + count + ')');
            }
        });
    </script>
@endsection
