@extends('master')
@section('title', 'Data Akun Admin')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Akun Admin</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Data Akun Admin</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Data Akun Admin</h2>
                <p class="section-lead">Berikut adalah data akun admin yang telah dibuat.</p>
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
                        <h4>Data Akun Admin</h4>
                        <div class="card-header-form">
                            <a href="#" data-toggle="modal" data-target="#addModal" type="button"
                                class="btn btn-primary btn-sm">Tambah Admin</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Username</th>
                                    <th>Nama</th>
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
                    <h5 class="modal-title" id="addModal">Tambah Akun Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/setting/admin') }}" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="number" placeholder="Masukkan Username" class="form-control" name="nip_nik_nisn"
                                required="">
                            <div class="invalid-feedback">
                                Masukkan Username
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" placeholder="Masukkan Nama" class="form-control" name="nama"
                                required="">
                            <div class="invalid-feedback">
                                Masukkan Nama
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" placeholder="Masukkan Password" class="form-control" name="password"
                                required="">
                            <div class="invalid-feedback">
                                Masukkan Password
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password" placeholder="Konfirmasi Password" class="form-control" name="password_confirmation"
                                required="">
                            <div class="invalid-feedback">
                                Konfirmasi Password
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
                    <h5 class="modal-title" id="updateModal">Update Akun Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/setting/admin/update') }}" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="number" placeholder="Masukkan Username" class="form-control" name="nip_nik_nisn"
                                id="nip_nik_nisn" required="">
                            <div class="invalid-feedback">
                                Masukkan Username
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" placeholder="Masukkan Nama" class="form-control" name="nama"
                                id="nama" required="">
                            <div class="invalid-feedback">
                                Masukkan Nama
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" placeholder="Masukkan Password Baru" class="form-control" name="password">
                            <div class="invalid-feedback">
                                Masukkan Password
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password" placeholder="Konfirmasi Password Baru" class="form-control" name="password_confirmation">
                            <div class="invalid-feedback">
                                Konfirmasi Password
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
                    url: "{{ url('admin/setting/admin/all') }}",
                    type: "GET"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nip_nik_nisn',
                        name: 'nip_nik_nisn',
                    },
                    {
                        data: 'nama',
                        name: 'nama',
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
                    url: "{{ url('admin/setting/admin/get') }}",
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
                        $('#nip_nik_nisn').val(data.nip_nik_nisn);
                        $('#nama').val(data.nama);
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
                        title: "Hapus Akun Admin?",
                        text: "Akun admin akan dihapus secara permanen!",
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
                                url: "{{ url('admin/setting/admin') }}",
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