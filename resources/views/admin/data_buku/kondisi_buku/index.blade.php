@extends('master')
@section('title', 'Data Kondisi Buku - ')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Kondisi Buku</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Data Kondisi Buku</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Data Kondisi Buku</h2>
                <p class="section-lead">Berikut adalah Data Kondisi Buku yang telah dibuat.</p>
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
                        <h4>Data Kondisi Buku</h4>
                        <div class="card-header-form">
                            <div class="dropdown d-inline dropleft">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true"
                                    data-toggle="dropdown" aria-expanded="false">
                                    Tambah
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" data-toggle="modal" data-target="#addModal"
                                            href="#">Input Manual</a></li>
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
                                    <th>Kondisi Buku</th>
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

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModal">Tambah Kondisi Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/data-buku/kondisi-buku') }}" method="POST" class="needs-validation"
                    novalidate="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kondisi Buku</label>
                            <input type="text" placeholder="Masukkan Kondisi Buku " class="form-control" name="nama_kondisi"
                                required="">
                            <div class="invalid-feedback">
                                Masukkan Kondisi Buku
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

    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModal">Update Kondisi Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/data-buku/kondisi-buku/update') }}" method="POST" class="needs-validation"
                    novalidate="">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                       
                        <div class="form-group">
                            <label>Kondisi Buku</label>
                            <input type="text" placeholder="Masukkan Kondisi Buku " class="form-control"
                                name="nama_kondisi" id="nama_kondisi" required="">
                            <div class="invalid-feedback">
                                Masukkan Kondisi Buku
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

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModal">Import Kondisi Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/data-buku/kondisi-buku/import') }}" id="formImport" method="POST"
                    class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label>File Excel</label>
                            <input type="file" name="file" required="" class="form-control" accept=".xlsx">
                            <small class="mt-1">
                                <a href="{{ url('assets/import/kondisi.xlsx') }}">
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
                    url: "{{ url('admin/data-buku/kondisi-buku/all') }}",
                    type: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'nama_kondisi', name: 'nama_kondisi' },
                    { data: 'action', name: 'action' }
                ]
            });

            $('.table').on('click', '.edit[data-id]', function(e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        id: $(this).data('id'),
                        _token: "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ url('admin/data-buku/kondisi-buku/get') }}",
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
                        $('#nama_kondisi').val(data.nama_kondisi);
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
                        title: "Hapus Kondisi Buku ?",
                        text: "Kondisi Buku akan dihapus secara permanen!",
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
                                url: "{{ url('admin/data-buku/kondisi-buku') }}",
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
                    url: "{{ url('admin/data-buku/kondisi-buku/import') }}",
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
