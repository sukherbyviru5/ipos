@extends('master')
@section('title', 'Data Buku Rusak/Hilang - ')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Buku Rusak/Hilang</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Data Buku Rusak/Hilang</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Data Buku Rusak/Hilang</h2>
                <p class="section-lead">Berikut adalah data buku yang rusak atau hilang.</p>
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
                        <h4>Data Buku Rusak/Hilang</h4>
                        <div class="card-header-form">
                            <a href="/admin/peminjaman/buku-rusak-hilang/create" class="btn btn-primary btn-sm">
                                Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Kode Buku</th>
                                    <th>Nama Siswa</th>
                                    <th>Status Sanksi</th>
                                    <th>Tanggal Laporan</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/peminjaman/buku-rusak-hilang/all') }}",
                    type: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'kode_qr', name: 'kode_qr' },
                    { data: 'siswa_nama', name: 'siswa_nama' },
                    { data: 'status_sanksi', name: 'status_sanksi' },
                    { data: 'tanggal_laporan', name: 'tanggal_laporan' },
                    { data: 'action', name: 'action' }
                ]
            });

            

            // Delete button click
            $('.table').on('click', '.hapus[data-id]', function(e) {
                e.preventDefault();
                swal({
                    title: "Hapus Data Buku Rusak/Hilang?",
                    text: "Data akan dihapus secara permanen!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            data: {
                                id: $(this).data('id'),
                                _token: "{{ csrf_token() }}"
                            },
                            type: 'DELETE',
                            url: "{{ url('admin/peminjaman/buku-rusak-hilang') }}",
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