@extends('master')
@section('title', 'Data Buku - ')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Buku</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Data Buku</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Data Buku</h2>
                <p class="section-lead">Berikut adalah Data Buku yang telah dibuat.</p>
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
                        <h4>Data Buku</h4>
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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Cover Buku</th>
                                    <th>Judul Buku</th>
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

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModal">Import Data Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/data-buku/import') }}" id="formImport" method="POST"
                    class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label>File Excel</label>
                            <input type="file" name="file" required="" class="form-control" accept=".xlsx">
                            <small class="mt-1">
                                <a href="{{ route('admin.buku.export') }}">
                                    Download template
                                </a>
                            </small>
                            <div class="invalid-feedback">
                                Masukkan File Excel Sesuai Format
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
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
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'cover_buku', name: 'cover_buku', orderable: false, searchable: false },
                    { data: 'judul_buku', name: 'judul_buku' },
                    { data: 'action', name: 'action' }
                ]
            });

            $('.table').on('click', '.hapus[data-id]', function(e) {
                e.preventDefault();
                    swal({
                        title: "Hapus Data Buku ?",
                        text: "Data Buku akan dihapus secara permanen!",
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
                                url: "{{ url('admin/data-buku/') }}",
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

            $('#formImport').on('submit', function(e) {
                e.preventDefault(); 

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ url('admin/data-buku/import') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        $.LoadingOverlay("show", {
                            image: "",
                            fontawesome: "fa fa-cog fa-spin"
                        });
                    },
                    complete: function() {
                        $.LoadingOverlay("hide");
                    },
                    success: function(response) {
                        $('#importModal').modal('hide');
                        swal({
                            title: "Sukses!",
                            text: response.message,
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(err) {
                        let errorMessage = err.responseJSON?.message ||
                            'Terjadi kesalahan saat mengimpor.';
                        swal({
                            title: "Gagal!",
                            text: errorMessage,
                            icon: "error"
                        });
                        console.log(err);
                    }
                });
            });
        });
    </script>
@endsection
