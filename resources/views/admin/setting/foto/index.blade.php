@extends('master')
@section('title', 'Foto - Pengaturan')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Foto</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Foto</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Daftar Foto</h2>
                <p class="section-lead">Berikut adalah daftar foto yang tersedia.</p>
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
                        <h4>Data Foto</h4>
                        <div class="card-header-form">
                            <a href="#" data-toggle="modal" data-target="#addModal" type="button"
                                class="btn btn-primary btn-sm">Tambah Foto</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped mt-5" id="fotoTable">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Foto</th>
                                    <th>Keterangan</th>
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
                    <h5 class="modal-title" id="addModalLabel">Tambah Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/setting/foto') }}" method="POST" class="needs-validation" novalidate=""
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label>Foto <span class="text-danger">*</span></label>
                            <input type="file" placeholder="Foto" class="form-control" name="foto" id="fotoAdd"
                                accept="image/*" required>
                            <div class="invalid-feedback">
                                Masukkan Foto
                            </div>
                            <div class="mt-2">
                                <img id="previewAdd" class="img-fluid" style="max-width: 200px; display: none;"
                                    alt="Photo Preview">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>Keterangan <small>(Opsional)</small></label>
                            <input type="text" placeholder="Keterangan" class="form-control" name="keterangan">
                            <div class="invalid-feedback">
                                Masukkan Keterangan
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
                    <h5 class="modal-title" id="updateModalLabel">Update Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/setting/foto/update') }}" method="POST" class="needs-validation" novalidate=""
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label>Foto <small>(Opsional)</small></label>
                            <input type="file" placeholder="Foto" class="form-control" name="foto" id="fotoUpdate"
                                accept="image/*">
                            <div class="invalid-feedback">
                                Masukkan Foto
                            </div>
                            <div class="mt-2">
                                <img id="previewUpdate" class="img-fluid" style="max-width: 200px; display: none;"
                                    alt="Photo Preview">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>Keterangan <small>(Opsional)</small></label>
                            <input type="text" placeholder="Keterangan" class="form-control" name="keterangan"
                                id="keterangan">
                            <div class="invalid-feedback">
                                Masukkan Keterangan
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

        $('#fotoTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('admin/setting/foto/all') }}",
                type: "GET"
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                {
                    data: 'foto',
                    name: 'foto',
                    render: function(data) {
                        return `<img src="{{ url('/') }}/${data}" alt="Foto" style="max-width: 100px;">`;
                    }
                },
                { data: 'keterangan', name: 'keterangan', defaultContent: '-' },
                { data: 'action', name: 'action' }
            ],
        });

        // Form submissions
        $(document).ready(function() {
            $('#fotoTable').on('click', '.edit[data-id]', function(e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        'id': $(this).data('id'),
                        '_token': "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ url('admin/setting/foto/get') }}",
                    success: function(data) {
                        $('#id').val(data.id);
                        $('#keterangan').val(data.keterangan || '');
                        $('#previewUpdate').attr('src', data.foto ? '{{ url("/") }}/' + data.foto : '');
                        $('#previewUpdate').css('display', data.foto ? 'block' : 'none');
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

            $('#fotoTable').on('click', '.hapus[data-id]', function(e) {
                e.preventDefault();
                swal({
                    title: "Hapus Foto ?",
                    text: "Foto akan dihapus secara permanent.",
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
                            url: "{{ url('admin/setting/foto') }}",
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