@extends('master')
@section('title', 'Kenaikan Kelas - ')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kenaikan Kelas</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Kenaikan Kelas</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Kenaikan Kelas</h2>
                <p class="section-lead">Pilih Data Siswa yang akan di migrasikan atau dijadikan alumni.</p>
            </div>
             <div class="alert alert-primary">
                Pilih siswa yang akan dimigrasikan ke kelas tujuan atau ditandai sebagai <b>ALUMNI</b>. <br>
                Siswa yang ditandai sebagai alumni dan status menjadi <b>non-aktif</b> untuk menandakan bahwa mereka telah lulus atau tidak lagi aktif di sekolah.
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Pilih Data Siswa yang akan dimigrasikan atau dijadikan alumni</h4>
                </div>
                <div class="card-body">
                    <form action="#" method="GET">
                        <div class="row">
                            <div class="col-sm-6 mt-1">
                                <label>Pilih Kelas Asal</label>
                                <select required onchange='if(this.value != "") { this.form.submit(); }' name="kelas_id"
                                    class="form-control" id="">
                                    <option value="">- PILIH KELAS -</option>
                                    @foreach (@$kelas as $k)
                                        <option value="{{ $k->id }}"
                                            {{ @$_GET['kelas_id'] == $k->id ? 'selected' : '' }}>
                                            - Kelas {{ $k->tingkat_kelas }} {{ $k->kelompok }}
                                            ({{ $k->urusan_kelas }})
                                            ( Jurusan {{ $k->jurusan }} ) -
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    @if (isset($_GET['kelas_id']))
                        <form id="formSimpan" method="post" class="mt-4">
                            @csrf
                            <label>Pilih Data Siswa yang akan dimigrasikan atau dijadikan alumni</label>
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
                                                        <label for="{{ $s->id }}"
                                                            class="custom-control-label"> </label>
                                                    </div>
                                                </td>
                                                <td>{{ $s->nik }}</td>
                                                <td>{{ $s->nisn }}</td>
                                                <td>{{ $s->nama_siswa }}</td>
                                                <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                                                <td>{{ $s->tanggal_lahir }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Aksi</label>
                                    <select name="action" class="form-control" required id="actionSelect">
                                        <option value="">- PILIH AKSI -</option>
                                        <option value="migrate">Migrasi ke Kelas Tujuan</option>
                                        <option value="alumni">Jadikan Alumni</option>
                                    </select>
                                </div>
                                <div class="col-sm-6" id="kelasTujuanDiv" style="display: none;">
                                    <label>Kelas Tujuan Migrasi</label>
                                    <select name="kelas_tujuan_id" class="form-control" id="kelasTujuanSelect">
                                        <option value="">- PILIH KELAS TUJUAN -</option>
                                        @foreach (@$kelas as $k)
                                            @if (@$_GET['kelas_id'] != $k->id)
                                                <option value="{{ $k->id }}">
                                                    - Kelas {{ $k->tingkat_kelas }} {{ $k->kelompok }}
                                                    ({{ $k->urusan_kelas }})
                                                    ( Jurusan {{ $k->jurusan }} ) -
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button class="mt-4 btn btn-primary" type="submit">
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

            $('#actionSelect').on('change', function() {
                if ($(this).val() === 'migrate') {
                    $('#kelasTujuanDiv').show();
                    $('#kelasTujuanSelect').prop('required', true);
                } else {
                    $('#kelasTujuanDiv').hide();
                    $('#kelasTujuanSelect').prop('required', false);
                }
            });

            // Form submission
            $('#formSimpan').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: $(this).serialize(),
                    type: 'POST',
                    url: "{{ url('admin/manage-member/kenaikan-kelas/migrasi-siswa') }}",
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
                            .then((result) => {
                                location.reload();
                            });
                    },
                    error: function(err) {
                        swal(err.responseJSON.message);
                    }
                });
            });

          
        });
    </script>
@endsection