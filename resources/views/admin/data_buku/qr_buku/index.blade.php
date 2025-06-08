@extends('master')
@section('title', 'QR Code Buku')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>QR Code Buku</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">QR Code Buku</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">QR Code Buku</h2>
                <p class="section-lead">Berikut adalah QR Code Buku yang telah dibuat.</p>
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session()->get('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4>QR Code Buku</h4>
                        <div class="card-header-form">
                            <div class="dropdown d-inline dropleft">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true"
                                    data-toggle="dropdown" aria-expanded="false">
                                    Tambah
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" 
                                            href="{{ url('/admin/data-buku/create') }}">Input Manual</a></li>
                                    <li><a class="dropdown-item" data-toggle="modal" data-target="#importModal"
                                            href="#">Import Excel</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="qrBukuTable">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Cover Buku</th>
                                    <th>Judul Buku</th>
                                    <th>Stok Buku</th>
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
    </div>

    <script>
        $(document).ready(function() {
            $('#qrBukuTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/data-buku/qr-buku/all') }}",
                    type: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'cover_buku', name: 'cover_buku', orderable: false, searchable: false },
                    { data: 'judul_buku', name: 'judul_buku' },
                    { data: 'stok_buku', name: 'stok_buku' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endsection