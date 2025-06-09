@extends('master')
@section('title', 'Peminjaman Siswa')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Peminjaman Siswa</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Peminjaman Siswa</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Peminjaman Siswa</h2>
                <p class="section-lead">Berikut adalah daftar peminjaman buku siswa terbaru.</p>
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
                        <h4>Peminjaman Siswa</h4>
                        <div class="card-header-form">
                            <a href="/admin/peminjaman/peminjaman-siswa/create" 
                                class="btn btn-primary btn-sm">Buat Pinjaman Baru</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="peminjamanSiswaTable">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Nama Siswa</th>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Jatuh Tempo</th>
                                    <th>Status</th>
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
            $('#peminjamanSiswaTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/peminjaman/peminjaman-siswa/all') }}",
                    type: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'siswa.nama_siswa', name: 'siswa.nama_siswa' },
                    { data: 'qr_buku.buku.judul_buku', name: 'qr_buku.buku.judul_buku' },
                    { data: 'tgl_pinjam', name: 'tgl_pinjam' },
                    { data: 'tgl_jatuh_tempo', name: 'tgl_jatuh_tempo' },
                    { data: 'status_peminjaman', name: 'status_peminjaman' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $('#peminjamanSiswaTable').on('click', '.hapus[data-id]', function(e) {
                e.preventDefault();
                swal({
                    title: "Hapus Peminjaman?",
                    text: "Data peminjaman akan dihapus dan tidak dapat dikembalikan.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            data: {
                                id: $(this).data('id'),
                                _token: "{{ csrf_token() }}"
                            },
                            type: 'DELETE',
                            url: "{{ url('admin/peminjaman/peminjaman-siswa') }}",
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
                                swal(data.message)
                                    .then((result) => {
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