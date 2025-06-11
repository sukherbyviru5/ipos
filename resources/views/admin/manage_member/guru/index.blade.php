@extends('master')
@section('title', 'Data Guru')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Guru</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Data Guru</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Data Guru</h2>
                <p class="section-lead">Berikut adalah Data Guru.</p>
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
                <div class="card">
                    <div class="card-header">
                        <h4>Data Seluruh Guru</h4>
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
                        <table class="table table-striped mt-5">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>NIK</th>
                                    <th>NIP/NPK</th>
                                    <th>Nama Guru</th>
                                    <th>Mata Pelajaran</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModal">Tambah Guru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/manage-member/guru') }}" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>NIK</label>
                            <input type="text" placeholder="Masukkan NIK" class="form-control" name="nik" required="">
                            <div class="invalid-feedback">
                                Masukkan NIK Guru
                            </div>
                        </div>
                        <div class="form-group">
                            <label>NIP/NPK</label>
                            <input type="text" placeholder="Masukkan NIP/NPK" class="form-control" name="nip" required="">
                            <div class="invalid-feedback">
                                Masukkan NIP/NPK
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nama Guru</label>
                            <input type="text" placeholder="Masukkan Nama Guru" class="form-control" name="nama_guru" required="">
                            <div class="invalid-feedback">
                                Masukkan Nama Guru
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mata Pelajaran</label>
                            <input type="text" placeholder="Masukkan Mata Pelajaran" class="form-control" name="nama_mata_pelajaran">
                            <div class="invalid-feedback">
                                Masukkan Mata Pelajaran
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required="">
                                <option value="aktif">Aktif</option>
                                <option value="non-aktif">Non-Aktif</option>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModal">Update Guru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/manage-member/guru') }}" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>NIK</label>
                            <input type="text" placeholder="Masukkan NIK" class="form-control" name="nik" required="" id="nik">
                            <div class="invalid-feedback">
                                Masukkan NIK Guru
                            </div>
                        </div>
                        <div class="form-group">
                            <label>NIP/NPK</label>
                            <input type="text" placeholder="Masukkan NIP/NPK" class="form-control" name="nip" required="" id="nip">
                            <div class="invalid-feedback">
                                Masukkan NIP/NPK
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nama Guru</label>
                            <input type="text" placeholder="Masukkan Nama Guru" class="form-control" name="nama_guru" required="" id="nama_guru">
                            <div class="invalid-feedback">
                                Masukkan Nama Guru
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mata Pelajaran</label>
                            <input type="text" placeholder="Masukkan Mata Pelajaran" class="form-control" name="nama_mata_pelajaran" id="nama_mata_pelajaran">
                            <div class="invalid-feedback">
                                Masukkan Mata Pelajaran
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required="" id="status">
                                <option value="aktif">Aktif</option>
                                <option value="non-aktif">Non-Aktif</option>
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

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModal">Import Guru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/manage-member/guru/import') }}" id="formImport" method="POST" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label>File Excel</label>
                            <input type="file" name="file" required="" class="form-control" accept=".xlsx">
                            <small class="mt-1">
                                <a href="{{ url('assets/import/guru-data.xlsx') }}">
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
        // DataTable initialization
        $(document).ready(function() {
            $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/manage-member/guru/all') }}",
                    type: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'nik', name: 'nik' },
                    { data: 'nip', name: 'nip' },
                    { data: 'nama_guru', name: 'nama_guru' },
                    { data: 'nama_mata_pelajaran', name: 'nama_mata_pelajaran' },
                    { data: 'status_badge', name: 'status' },
                    { data: 'action', name: 'action' }
                ]
            });

            // Edit button handler
            $('.table').on('click', '.edit[data-id]', function(e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        'id': $(this).data('id'),
                        '_token': "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ url('admin/manage-member/guru/get') }}",
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
                        $('#nik').val(data.nik);
                        $('#nip').val(data.nip);
                        $('#nama_guru').val(data.nama_guru);
                        $('#nama_mata_pelajaran').val(data.nama_mata_pelajaran);
                        $('#status').val(data.status);
                        $('#updateModal').modal('show');
                    },
                    error: function(err) {
                        alert('Error: ' + err.responseText);
                        console.log(err);
                    }
                });
            });

            // Delete button handler
            $('.table').on('click', '.hapus[data-id]', function(e) {
                e.preventDefault();
                swal({
                    title: "Hapus Guru?",
                    text: "Data Guru ini akan dihapus secara permanen!",
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
                            url: "{{ url('admin/manage-member/guru') }}",
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