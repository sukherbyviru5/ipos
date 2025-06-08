@extends('master')
@section('title', 'Status Siswa - ')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Status Siswa</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Status Siswa</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Kelola Status Siswa</h2>
                <p class="section-lead">Pilih aksi terlebih dahulu, kemudian kelas (jika diperlukan), untuk mengelola status siswa.</p>
            </div>
           
            <div class="card">
                <div class="card-header">
                    <h4>Pilih Aksi dan Siswa untuk Mengubah Status</h4>
                </div>
                <div class="card-body">
                    <form action="#" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-sm-4 mt-1">
                                <label>Pilih Aksi</label>
                                <select name="action" class="form-control" id="actionSelect" onchange="this.form.submit()" required>
                                    <option value="">- PILIH AKSI -</option>
                                    <option value="aktif" {{ $action == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="non-aktif" {{ $action == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                    <option value="alumni" {{ $action == 'alumni' ? 'selected' : '' }}>Alumni</option>
                                </select>
                            </div>
                            <div class="col-sm-4 mt-1" id="kelasSelectDiv" style="{{ $action == 'alumni' ? 'display: none;' : '' }}">
                                <label>Pilih Kelas</label>
                                <select name="kelas_id" class="form-control" onchange="this.form.submit()" {{ $action == 'alumni' ? '' : 'required' }}>
                                    <option value="">- PILIH KELAS -</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id }}"
                                            {{ $kelas_id == $k->id ? 'selected' : '' }}>
                                            - Kelas {{ $k->tingkat_kelas }} {{ $k->kelompok }}
                                            ({{ $k->urusan_kelas }})
                                            ( Jurusan {{ $k->jurusan }} ) -
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($action == 'alumni')
                                <div class="col-sm-4 mt-1">
                                    <label>Pilih Tahun Kelulusan</label>
                                    <select name="tahun_kelulusan" class="form-control" onchange="this.form.submit()">
                                        <option value="">- Semua Tahun -</option>
                                        @foreach ($tahun_list as $tahun)
                                            <option value="{{ $tahun }}" {{ $tahun_kelulusan == $tahun ? 'selected' : '' }}>
                                                {{ $tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </form>

                    @if ($action && ($action == 'alumni' || $kelas_id))
                        <form id="formSimpan" method="post" class="mt-4">
                            @csrf
                            <label>Pilih Siswa untuk Mengubah Status</label>
                            <div class="overflow-auto">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="10">
                                                <div class="custom-checkbox custom-control">
                                                    <input type="checkbox" class="custom-control-input" id="parent">
                                                    <label for="parent" class="custom-control-label"> </label>
                                                </div>
                                            </th>
                                            <th>NIK</th>
                                            <th>NISN</th>
                                            <th>Nama Siswa</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Tanggal Lahir</th>
                                            @if ($action == 'alumni')
                                                <th>Tahun Kelulusan</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($siswa as $s)
                                            <tr>
                                                <td class="p-0 text-center">
                                                    <div class="custom-checkbox custom-control">
                                                        <input type="checkbox" name="siswa_id[]"
                                                            class="custom-control-input child" id="{{ $s->id }}"
                                                            value="{{ $s->id }}">
                                                        <label for="{{ $s->id }}" class="custom-control-label"> </label>
                                                    </div>
                                                </td>
                                                <td>{{ $s->nik }}</td>
                                                <td>{{ $s->nisn }}</td>
                                                <td>{{ $s->nama_siswa }}</td>
                                                <td>{{ $s->jenis_kelamin }}</td>
                                                <td>{{ $s->tanggal_lahir }}</td>
                                                @if ($action == 'alumni')
                                                    <td>{{ $s->tanggal_kelulusan ? date('Y', strtotime($s->tanggal_kelulusan)) : '-' }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Aksi</label>
                                    <select name="action" class="form-control" id="actionSelectSubmit" required>
                                        <option value="">- PILIH AKSI -</option>
                                        <option value="aktif" {{ $action == 'aktif' ? 'selected' : '' }}>Aktifkan</option>
                                        <option value="non-aktif" {{ $action == 'non-aktif' ? 'selected' : '' }}>Non-Aktifkan</option>
                                        <option value="alumni" {{ $action == 'alumni' ? 'selected' : '' }}>Jadikan Alumni</option>
                                    </select>
                                </div>
                                <div class="col-sm-4" id="kelasSelectDivSubmit" style="{{ $action == 'alumni' ? 'display: none;' : '' }}">
                                    <label>Pilih Kelas</label>
                                    <select name="kelas_id" class="form-control" {{ $action == 'alumni' ? '' : 'required' }}>
                                        <option value="">- PILIH KELAS -</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}"
                                                {{ $kelas_id == $k->id ? 'selected' : '' }}>
                                                - Kelas {{ $k->tingkat_kelas }} {{ $k->kelompok }}
                                                ({{ $k->urusan_kelas }})
                                                ( Jurusan {{ $k->jurusan }} ) -
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4" id="tahunKelulusanDiv" style="{{ $action == 'alumni' ? '' : 'display: none;' }}">
                                    <label>Tahun Kelulusan</label>
                                    <input type="number" name="tahun_kelulusan" class="form-control" placeholder="YYYY" min="1900" max="{{ date('Y') + 1 }}" {{ $action == 'alumni' ? 'required' : '' }}>
                                </div>
                            </div>

                            <button class="mt-4 btn btn-primary" type="submit" id="submitButton">
                                Proses
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </section>
    </div>
    <script>
        $(document).ready(function() {
            // Checkbox logic
            $('#parent').click(function() {
                $('.child').prop('checked', this.checked);
            });

            $('.child').click(function() {
                if ($('.child:checked').length == $('.child').length) {
                    $('#parent').prop('checked', true);
                } else {
                    $('#parent').prop('checked', false);
                }
            });

            // Show/hide kelas and tahun kelulusan based on action in filter form
            $('#actionSelect').on('change', function() {
                if ($(this).val() === 'alumni') {
                    $('#kelasSelectDiv').hide();
                    $('select[name="kelas_id"]').prop('required', false);
                } else {
                    $('#kelasSelectDiv').show();
                    $('select[name="kelas_id"]').prop('required', true);
                }
            });

            // Show/hide kelas and tahun kelulusan based on action in submit form
            $('#actionSelectSubmit').on('change', function() {
                if ($(this).val() === 'alumni') {
                    $('#tahunKelulusanDiv').show();
                    $('input[name="tahun_kelulusan"]').prop('required', true);
                    $('#kelasSelectDivSubmit').hide();
                    $('select[name="kelas_id"]').prop('required', false);
                } else {
                    $('#tahunKelulusanDiv').hide();
                    $('input[name="tahun_kelulusan"]').prop('required', false);
                    $('#kelasSelectDivSubmit').show();
                    $('select[name="kelas_id"]').prop('required', true);
                }
            });

            // Validate tahun kelulusan
            $('input[name="tahun_kelulusan"]').on('input', function() {
                const currentYear = new Date().getFullYear() + 1;
                if ($(this).val() > currentYear) {
                    $(this).val(currentYear);
                }
            });

            // Form submission with confirmation
            $('#formSimpan').submit(function(e) {
                e.preventDefault();
                if ($('.child:checked').length === 0) {
                    swal('Pilih setidaknya satu siswa!');
                    return;
                }

                const actionText = $('#actionSelectSubmit option:selected').text();
                swal({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin ${actionText} siswa yang dipilih?`,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((willProcess) => {
                    if (willProcess) {
                        $.ajax({
                            data: $(this).serialize(),
                            type: 'POST',
                            url: "{{ url('admin/manage-member/status-siswa/update') }}",
                            beforeSend: function() {
                                $.LoadingOverlay("show", {
                                    image: "",
                                    fontawesome: "fa fa-cog fa-spin"
                                });
                            },
                            complete: function() {
                                $.LoadingOverlay("hide", {
                                    image: "",
                                    fontawesome: "fa fa-cog fa-spin"
                                });
                            },
                            success: function(data) {
                                swal(data.message)
                                    .then(() => {
                                        location.reload();
                                    });
                            },
                            error: function(err) {
                                swal(err.responseJSON.message);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection