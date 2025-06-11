@extends('master')
@section('title', 'Data Artikel')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Artikel</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Data Artikel</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Data Artikel</h2>
                <p class="section-lead">Berikut adalah data Artikel yang telah dibuat.</p>
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
                        <h4>Data Artikel</h4>
                        <div class="card-header-form">
                            <a href="#" data-toggle="modal" data-target="#addModal" type="button"
                                class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Created By</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModal">Tambah Artikel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/publikasi/artikel') }}" method="POST" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Judul</label>
                            <input type="text" placeholder="Masukkan Judul" class="form-control" name="judul"
                                required="">
                            <div class="invalid-feedback">
                                Masukkan Judul
                            </div>
                        </div>
                        <div class="form-group">
                            <label>File</label>
                            <input type="file" class="form-control" name="file"
                                required="">
                            <div class="invalid-feedback">
                                Pilih File
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required="">
                                <option value="draft">Draft</option>
                                <option value="setuju">Setuju</option>
                                <option value="tolak">Tolak</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status
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

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModal">Update Artikel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/publikasi/artikel/update') }}" method="POST" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Judul</label>
                            <input type="text" placeholder="Masukkan Judul" class="form-control" name="judul"
                                id="judul" required="">
                            <div class="invalid-feedback">
                                Masukkan Judul
                            </div>
                        </div>
                        <div class="form-group">
                            <label>File (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="file" class="form-control" name="file">
                            <div class="invalid-feedback">
                                Pilih File
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" id="status" required="">
                                <option value="draft">Draft</option>
                                <option value="setuju">Setuju</option>
                                <option value="tolak">Tolak</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status
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

    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/publikasi/artikel/all') }}",
                    type: "GET"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                    },
                    {
                        data: 'created_by',
                        name: 'created_by',
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
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
                    url: "{{ url('admin/publikasi/artikel/get') }}",
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
                        $('#judul').val(data.judul);
                        $('#status').val(data.status);
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
                        title: "Hapus Artikel?",
                        text: "Artikel akan dihapus secara permanen!",
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
                                url: "{{ url('admin/publikasi/artikel') }}",
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