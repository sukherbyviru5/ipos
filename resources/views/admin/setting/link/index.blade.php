@extends('master')
@section('title', 'Link - Pengaturan')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Link</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Link</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Daftar Link</h2>
                <p class="section-lead">Berikut adalah daftar link yang tersedia.</p>
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
                        <h4>Data Link</h4>
                        <div class="card-header-form">
                            <a href="#" data-toggle="modal" data-target="#addModal" type="button"
                                class="btn btn-primary btn-sm">Tambah Link</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped mt-5" id="linkTable">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Logo</th>
                                    <th>Link</th>
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
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/setting/link') }}" method="POST" class="needs-validation" novalidate=""
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label>Logo <span class="text-danger">*</span></label>
                            <input type="file" placeholder="Logo" class="form-control" name="logo" id="fotoAdd"
                                accept="image/*" required>
                            <div class="invalid-feedback">
                                Masukkan Logo
                            </div>
                            <div class="mt-2">
                                <img id="previewAdd" class="img-fluid" style="max-width: 200px; display: none;"
                                    alt="Photo Preview">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>Link/Alamat</label>
                            <input type="text" placeholder="link" class="form-control" name="link">
                            <div class="invalid-feedback">
                                Masukkan link
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/setting/link/update') }}" method="POST" class="needs-validation" novalidate=""
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label>Logo <small>(Opsional)</small></label>
                            <input type="file" placeholder="Logo" class="form-control" name="logo" id="fotoUpdate"
                                accept="image/*">
                            <div class="invalid-feedback">
                                Masukkan Logo
                            </div>
                            <div class="mt-2">
                                <img id="previewUpdate" class="img-fluid" style="max-width: 200px; display: none;"
                                    alt="Photo Preview">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>link <small>(Opsional)</small></label>
                            <input type="text" placeholder="link" class="form-control" name="link"
                                id="link">
                            <div class="invalid-feedback">
                                Masukkan link
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('fotoAdd').addEventListener('change', function(event) {
            const preview = document.getElementById('previewAdd');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
                preview.src = '';
            }
        });

        document.getElementById('fotoUpdate').addEventListener('change', function(event) {
            const preview = document.getElementById('previewUpdate');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            } else {
                preview.style.display = 'block'; 
            }
        });

        $('#linkTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('admin/setting/link/all') }}",
                type: "GET"
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                {
                    data: 'logo',
                    name: 'logo',
                    render: function(data) {
                        return `<img src="{{ url('/') }}/${data}" alt="Link" style="max-width: 100px;">`;
                    }
                },
                { 
                    data: 'link', 
                    name: 'link', 
                    defaultContent: '-',
                    render: function(data) {
                        return data ? `<a href="${data}" target="_blank">${data.length > 30 ? data.slice(0,30) + '..' : data}</a>` : '-';
                    }
                },
                { data: 'action', name: 'action' }
            ],
        });

        $(document).ready(function() {
            $('#linkTable').on('click', '.edit[data-id]', function(e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        'id': $(this).data('id'),
                        '_token': "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ url('admin/setting/link/get') }}",
                    success: function(data) {
                        $('#id').val(data.id);
                        $('#link').val(data.link || '');
                        $('#previewUpdate').attr('src', data.logo ? '{{ url("/") }}/' + data.logo : '');
                        $('#previewUpdate').css('display', data.logo ? 'block' : 'none');
                        $('#updateModal').modal('show');
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error: ' + (err.responseJSON?.message || 'Unknown error'),
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            $('#linkTable').on('click', '.hapus[data-id]', function(e) {
                e.preventDefault();
                swal({
                    title: "Hapus Link ?",
                    text: "Link akan dihapus secara permanent.",
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
                            url: "{{ url('admin/setting/link') }}",
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