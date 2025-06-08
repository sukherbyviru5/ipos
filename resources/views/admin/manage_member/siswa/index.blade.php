@extends('master')
@section('title', 'Data Siswa - ')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Siswa</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Data Siswa</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Data Siswa</h2>
                <p class="section-lead">Berikut adalah Data Siswa.</p>
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
                        <h4>Data Seluruh Siswa</h4>
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
                        <div class="card-header">
                            <form action="{{ url('admin/manage-member/siswa') }}" method="GET"
                                style="margin-top:-10px; margin-left:-23px;">
                                <div class="row">
                                    <div class="col-lg-12 col-xl-12 col">
                                        <div class="form-group mb-3 mt-0">
                                            <select name="k" required onchange='if(this.value != "") { this.form.submit(); }' class="form-control kelas" required="">
                                                <option value="">- PILIH KELAS -</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <table class="table table-striped mt-5">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>NISN</th>
                                    <th>NIK</th>
                                    <th>Nama Siswa</th>
                                    <th>Tingkat Kelas</th>
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
                    <h5 class="modal-title" id="addModal">Tambah Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/manage-member/siswa') }}" method="POST" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih Kelas</label>
                            <select name="id_kelas" class="form-control kelas" required="">
                                <option value="">- PILIH KELAS -</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Kelas Dahulu
                            </div>
                        </div>
                        <div class="form-group">
                            <label>NIK</label>
                            <input type="text" placeholder="Masukkan NIK" class="form-control" name="nik" required="">
                            <div class="invalid-feedback">
                                Masukkan NIK Siswa
                            </div>
                        </div>
                        <div class="form-group">
                            <label>NISN</label>
                            <input type="text" placeholder="Masukkan NISN" class="form-control" name="nisn" required="">
                            <div class="invalid-feedback">
                                Masukkan NISN
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" placeholder="Masukkan Nama Siswa" class="form-control" name="nama_siswa" required="">
                            <div class="invalid-feedback">
                                Masukkan Nama Siswa
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" required="" class="form-control">
                                <option value="">- PILIH JENIS KELAMIN -</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Jenis Kelamin
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>Tempat Lahir <small>(Opsional)</small></label>
                            <input type="text" placeholder="Tempat Lahir" class="form-control" name="tempat_lahir">
                            <div class="invalid-feedback">
                                Masukkan Tempat Lahir
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>Tanggal Lahir <small>(Opsional)</small></label>
                            <input type="date" placeholder="Tanggal Lahir" class="form-control" name="tanggal_lahir">
                            <div class="invalid-feedback">
                                Masukkan Tanggal Lahir
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>Alamat <small>(Opsional)</small></label>
                            <input type="text" placeholder="Alamat" class="form-control" name="alamat">
                            <div class="invalid-feedback">
                                Masukkan Alamat
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>No HP <small>(Opsional)</small></label>
                            <input type="number" placeholder="No HP" class="form-control" name="no_hp">
                            <div class="invalid-feedback">
                                Masukkan No HP
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>Foto <small>(Opsional)</small></label>
                            <input type="file" placeholder="Foto" class="form-control" name="foto" id="fotoAdd" accept="image/*">
                            <div class="invalid-feedback">
                                Masukkan Foto
                            </div>
                            <div class="mt-2">
                                <img id="previewAdd" class="img-fluid" style="max-width: 200px; display: none;" alt="Photo Preview">
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
                    <h5 class="modal-title" id="updateModal">Update Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/manage-member/siswa') }}"  method="POST" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih Kelas</label>
                            <select name="id_kelas" id="id_kelas" class="form-control kelas" required="">
                                <option value="">- PILIH KELAS -</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Kelas Dahulu
                            </div>
                        </div>
                        <div class="form-group">
                            <label>NIK</label>
                            <input type="text" placeholder="Masukkan NIK" class="form-control" name="nik" required="" id="nik">
                            <div class="invalid-feedback">
                                Masukkan NIK Siswa
                            </div>
                        </div>
                        <div class="form-group">
                            <label>NISN</label>
                            <input type="text" placeholder="Masukkan NISN" class="form-control" name="nisn" required="" id="nisn">
                            <div class="invalid-feedback">
                                Masukkan NISN
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" placeholder="Masukkan Nama Siswa" class="form-control" name="nama_siswa" required="" id="nama_siswa">
                            <div class="invalid-feedback">
                                Masukkan Nama Siswa
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" required="" class="form-control" id="jenis_kelamin">
                                <option value="">- PILIH JENIS KELAMIN -</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Jenis Kelamin
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tempat Lahir <small>(Opsional)</small></label>
                            <input type="text" placeholder="Tempat Lahir" class="form-control" name="tempat_lahir" id="tempat_lahir">
                            <div class="invalid-feedback">
                                Masukkan Tempat Lahir
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir <small>(Opsional)</small></label>
                            <input type="date" placeholder="Tanggal Lahir" class="form-control" name="tanggal_lahir" id="tanggal_lahir">
                            <div class="invalid-feedback">
                                Masukkan Tanggal Lahir
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat <small>(Opsional)</small></label>
                            <input type="text" placeholder="Alamat" class="form-control" name="alamat" id="alamat">
                            <div class="invalid-feedback">
                                Masukkan Alamat
                            </div>
                        </div>
                        <div class="form-group">
                            <label>No HP <small>(Opsional)</small></label>
                            <input type="number" placeholder="No HP" class="form-control" name="no_hp" id="no_hp">
                            <div class="invalid-feedback">
                                Masukkan No HP
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Foto <small>(Opsional)</small></label>
                            <input type="file" placeholder="Foto" class="form-control" name="foto" id="fotoUpdate" accept="image/*">
                            <div class="invalid-feedback">
                                Masukkan Foto
                            </div>
                            <div class="mt-2">
                                <img id="previewUpdate" class="img-fluid" style="max-width: 200px;" alt="Photo Preview">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" required="" class="form-control" id="status">
                                <option value="aktif">Aktif</option>
                                <option value="non-aktif">Non-Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alumni</label>
                            <select name="is_alumni" required="" class="form-control" id="is_alumni">
                                <option value="0">Bukan Alumni</option>
                                <option value="1">Alumni</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Alumni
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
                    <h5 class="modal-title" id="importModal">Import Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/manage-member/siswa/import') }}" id="formImport" method="POST" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih Kelas</label>
                            <select name="id_kelas" class="form-control kelas" required="">
                                <option value="">- PILIH KELAS -</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Kelas Dahulu
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>File Excel</label>
                            <input type="file" name="file" required="" class="form-control" accept=".xlsx">
                            <small class="mt-1">
                                <a href="{{ url('assets/import/siswa.xlsx') }}">
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
        // Photo Preview for Add Modal
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

        $('.kelas').find('option').remove().end().append(
            '<option value="">- PILIH KELAS -</option>').val('');
        $.ajax({
            data: {
                'id': "{{ request()->get('k') }}",
            },
            type: 'GET',
            url: "{{ url('admin/manage-member/siswa/kelas') }}",
            success: function(data) {
                $.each(data, function(i, data) {
                    let kelompok = data.kelompok ? data.kelompok : '';
                    $('.kelas').append($('<option>', {
                        value: data.id,
                        text: '- Kelas ' + data.tingkat_kelas + ' ' + kelompok + ' ( ' +
                            data.urusan_kelas +
                            ' ) ( Jurusan ' + data.jurusan + ' ) -'
                    }));
                });
                $('.kelas').val("{{ request()->get('k') }}");
            },
            error: function(err) {
                alert('Error loading classes: ' + err.responseText);
                console.log(err);
            }
        });

        // DataTable initialization
        $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('admin/manage-member/siswa/all') }}",
                type: "GET",
                data: {
                    'id': "{{ request()->get('k') }}"
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'nisn', name: 'nisn' },
                { data: 'nik', name: 'nik' },
                { data: 'nama_siswa', name: 'nama_siswa' },
                { data: 'tingkat_kelas', name: 'tingkat_kelas' },
                { data: 'action', name: 'action' }
            ],
        });

        // Form submissions
        $(document).ready(function() {
            $('.table').on('click', '.edit[data-id]', function(e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        'id': $(this).data('id'),
                        '_token': "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ url('admin/manage-member/siswa/get') }}",
                    success: function(data) {
                        $('#id').val(data.id);
                        $('#id_kelas').val(data.id_kelas);
                        $('#nik').val(data.nik);
                        $('#nisn').val(data.nisn);
                        $('#nama_siswa').val(data.nama_siswa);
                        $('#jenis_kelamin').val(data.jenis_kelamin);
                        $('#tempat_lahir').val(data.tempat_lahir);
                        $('#tanggal_lahir').val(data.tanggal_lahir);
                        $('#alamat').val(data.alamat);
                        $('#no_hp').val(data.no_hp);
                        $('#status').val(data.status);
                        $('#is_alumni').val(data.is_alumni ? '1' : '0');
                        $('#previewUpdate').attr('src', data.foto ? '{{ url("/") }}/' + data.foto : '');
                        $('#previewUpdate').css('display', data.foto ? 'block' : 'none');
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
                    title: "Hapus Siswa ?",
                    text: "Data Siswa ini akan dihapus",
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
                            url: "{{ url('admin/manage-member/siswa') }}",
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