@extends('master')
@section('title', 'Video - Pengaturan')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Video</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Video</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Daftar Video</h2>
                <p class="section-lead">Berikut adalah daftar video YouTube yang tersedia.</p>
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session()->get('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4>Data Video</h4>
                        <div class="card-header-form">
                            <a href="#" data-toggle="modal" data-target="#addModal" type="button"
                                class="btn btn-primary btn-sm">Tambah Video</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped mt-5" id="videoTable">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Judul</th>
                                    <th>Video</th>
                                    <th>No Urut</th>
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
                    <h5 class="modal-title" id="addModalLabel">Tambah Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/setting/video') }}" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label>Judul <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Judul Video" class="form-control" name="judul" required>
                            <div class="invalid-feedback">
                                Masukkan Judul Video
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>Link YouTube <span class="text-danger">*</span></label>
                            <input type="url"
                                placeholder="Masukkan Link YouTube (e.g., https://www.youtube.com/watch?v=dkZuAHtLL6Y)"
                                class="form-control" name="video_url" id="video_url" required>
                            <div class="invalid-feedback">
                                Masukkan Link YouTube yang valid
                            </div>
                            <div class="mt-2">
                                <iframe id="previewAddYoutube" style="max-width: 200px; display: none;" frameborder="0"
                                    allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>No Urut <span class="text-danger">*</span></label>
                            <input type="text" placeholder="No Urut" class="form-control" name="no_urut" required>
                            <div class="invalid-feedback">
                                Masukkan No Urut
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
                    <h5 class="modal-title" id="updateModalLabel">Update Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/setting/video/update') }}" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label>Judul <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Judul Video" class="form-control" name="judul"
                                id="judul" required>
                            <div class="invalid-feedback">
                                Masukkan Judul Video
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>Link YouTube <span class="text-danger">*</span></label>
                            <input type="url"
                                placeholder="Masukkan Link YouTube (e.g., https://www.youtube.com/watch?v=dkZuAHtLL6Y)"
                                class="form-control" name="video_url" id="video_url_update" required>
                            <div class="invalid-feedback">
                                Masukkan Link YouTube yang valid
                            </div>
                            <div class="mt-2">
                                <iframe id="previewUpdateYoutube" style="max-width: 200px; display: none;"
                                    frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>No Urut <span class="text-danger">*</span></label>
                            <input type="text" placeholder="No Urut" class="form-control" name="no_urut"
                                id="no_urut" required>
                            <div class="invalid-feedback">
                                Masukkan No Urut
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
        $(document).ready(function() {
            $('#videoTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/setting/video/all') }}",
                    type: "GET"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'video_url',
                        name: 'video_url',
                        render: function(data) {
                            const videoId = data.split('v=')[1]?.split('&')[0];
                            return `<iframe width="200" height="100" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>`;
                        }
                    },
                    {
                        data: 'no_urut',
                        name: 'no_urut'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

            $('#video_url').on('input', function() {
                const url = $(this).val();
                const videoId = url.split('v=')[1]?.split('&')[0];
                const preview = $('#previewAddYoutube');
                if (videoId) {
                    preview.attr('src', `https://www.youtube.com/embed/${videoId}`);
                    preview.css('display', 'block');
                } else {
                    preview.attr('src', '');
                    preview.css('display', 'none');
                }
            });

            $('#video_url_update').on('input', function() {
                const url = $(this).val();
                const videoId = url.split('v=')[1]?.split('&')[0];
                const preview = $('#previewUpdateYoutube');
                if (videoId) {
                    preview.attr('src', `https://www.youtube.com/embed/${videoId}`);
                    preview.css('display', 'block');
                } else {
                    preview.attr('src', '');
                    preview.css('display', 'none');
                }
            });

            $('#videoTable').on('click', '.edit[data-id]', function(e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        id: $(this).data('id'),
                        _token: "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ url('admin/setting/video/get') }}",
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
                        $('#no_urut').val(data.no_urut);
                        $('#video_url_update').val(data.video_url);
                        const videoId = data.video_url.split('v=')[1]?.split('&')[0];
                        const preview = $('#previewUpdateYoutube');
                        if (videoId) {
                            preview.attr('src', `https://www.youtube.com/embed/${videoId}`);
                            preview.css('display', 'block');
                        }
                        $('#updateModal').modal('show');
                    },
                    error: function(err) {
                        swal({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error: ' + (err.responseJSON?.message ||
                                'Unknown error'),
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            $('#videoTable').on('click', '.hapus[data-id]', function(e) {
              
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
                            url: "{{ url('admin/setting/video') }}",
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
                                swal({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: data.message,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    $('#videoTable').DataTable().ajax.reload();
                                });
                            },
                            error: function(err) {
                                swal({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error: ' + (err.responseJSON
                                        ?.message || 'Unknown error'),
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
