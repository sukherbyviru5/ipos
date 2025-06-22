@extends('layout.pages')
@section('title', 'Buku Tamu - Absensi Anggota Perpustakaan')
@section('content')
    <style>
        body {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }

        .container {
            max-width: 100% !important;
            position: relative;
            z-index: 1;
        }

        .header {
            background-color: #0091ab;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 5px 5px 0 0;
        }

        .profile-img {
            max-width: 100px;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 50%;
        }

        .custom-select-wrapper {
            position: relative;
        }

        .custom-select-styled {
            appearance: none;
            background: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 10px 40px 10px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .custom-select-styled:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .custom-select-wrapper::after {
            content: '\f078';
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #495057;
            pointer-events: none;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        .card-body {
            padding: 1px;
        }

        table {
            font-size: 13px;
        }

        table td {
            padding: 5px;
            vertical-align: middle;
        }

        table td:first-child {
            font-weight: 700;
            width: 40%;
        }

        .row {
            margin-right: -5px;
            margin-left: -5px;
        }

        .row>.col-md-4,
        .row>.col-lg-4 {
            padding-right: 5px;
            padding-left: 5px;
        }
    </style>

    <nav class="navbar navbar-secondary navbar-expand-lg">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a href="{{ url('/buku-tamu') }}" class="nav-link"><i class="fa-solid fa-users"></i><span>Buku Tamu</span></a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/bebas-perpus') }}" class="nav-link"><i class="fa-solid fa-id-card"></i><span>Cek Bebas Perpus</span></a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/informasi') }}" class="nav-link"><i class="fa-solid fa-info-circle"></i><span>Informasi</span></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="overlay">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session()->get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="header">
                                    INFORMASI ID ANGGOTA
                                </div>
                                <div class="card-body p-3" style="background-color: #f8f9fa;">
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('assets/img/avatar.png') }}" alt="Foto Profil" id="member-photo"
                                            class="profile-img" style="width: 120px; height: 108px; object-fit: cover;">
                                    </div>
                                    <table class="w-100">
                                        <tr id="row-name">
                                            <td>Nama</td>
                                            <td id="member-name">: ....</td>
                                        </tr>
                                        <tr id="row-nik">
                                            <td>NIK</td>
                                            <td id="member-nik">: ....</td>
                                        </tr>
                                        <tr id="row-nisn" style="display: none;">
                                            <td>NISN</td>
                                            <td id="member-nisn">: ....</td>
                                        </tr>
                                        <tr id="row-class" style="display: none;">
                                            <td>Kelas</td>
                                            <td id="member-class">: ....</td>
                                        </tr>
                                        <tr id="row-subject" style="display: none;">
                                            <td>Mata Pelajaran</td>
                                            <td id="member-subject">: ....</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="header">
                                    Tempelkan Kartu Anda
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="scanInputSiswa" class="form-label">Masukan NIK atau QR Code</label>
                                        <input type="text" class="form-control" id="scanInputSiswa" autofocus autocomplete="off">
                                    </div>
                                    <div class="form-group kamera-scan">
                                        <label>Scan dengan Kamera</label>
                                        <a href="#" class="btn btn-primary w-100" data-toggle="modal" data-target="#cameraModalSiswa">
                                            <i class="fa-solid fa-camera"></i> Buka Scan Kamera
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-clock"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>
                                            Hari {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->isoFormat('dddd, D MMMM YYYY') }}
                                        </h4>
                                    </div>
                                    <div class="card-body" id="current-time">
                                        {{ $currentTime }}
                                    </div>
                                </div>
                            </div>
                            <div class="card card-statistic-1" style="margin: -30px 0 0 0">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-solid fa-chart-line"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Jumlah Pengunjung Hari Ini</h4>
                                    </div>
                                    <div class="card-body" id="visitor-count">
                                        {{ $visitors->count() }} Pengunjung
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="classVisitCheckbox">
                                            <label class="custom-control-label" for="classVisitCheckbox"><b>Apakah Ini Kunjungan Kelas ?</b></label>
                                        </div>
                                    </div>
                                    <div class="row class-visit-section" id="classVisitSection" style="display: none;">
                                        <div class="col-md-3">
                                            <select class="form-control select2" id="selectGuru">
                                                <option value="">Pilih Guru</option>
                                                @foreach ($gurus as $guru)
                                                    <option value="{{ $guru->id }}">{{ $guru->nama_guru }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control select2" id="selectKelas">
                                                <option value="">Pilih Kelas</option>
                                                @foreach ($kelas as $k)
                                                    <option value="{{ $k->id }}">- Kelas {{ $k->tingkat_kelas }} {{ $k->kelompok }} ({{ $k->urusan_kelas }}) (Jurusan {{ $k->jurusan }}) -</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" id="inputMateri" placeholder="Input Materi Mata Pelajaran">
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-lg mt-3" id="saveVisit">Simpan Kunjungan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Warning Modal -->
    <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="warningModalLabel">Peringatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="warningMessage">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Camera Modal -->
    <div class="modal fade" id="cameraModalSiswa" tabindex="-1" aria-labelledby="cameraModalSiswaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalSiswaLabel">Scan QR Code Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="custom-select-wrapper">
                        <select id="camera-select-siswa" class="form-control custom-select-styled">
                            <option value="user">Kamera Depan</option>
                            <option value="environment">Kamera Belakang</option>
                        </select>
                    </div>
                    <div id="reader-siswa" style="width: 100%;"></div>
                </div>
                <h4 class="fs-4 mb-1 card-title text-center">QR Code: <span id="qr-result-text-siswa"><small class="text-success">Ready Scan..</small></span></h4>
                <small class="text-center">Tunggu text <span class="text-success">Ready Scan..</span> lalu scan lagi.</small>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let html5QrcodeSiswa = null;
            let lastScannedNik = null;

            function updateClock() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                $('#current-time').text(`${hours}:${minutes}:${seconds}`);
            }
            setInterval(updateClock, 1000);

            function updateVisitorCount() {
                $.ajax({
                    url: "{{ url('/get-visitor-count') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#visitor-count').text(`${response.count} Pengunjung`);
                    },
                    error: function(err) {
                        console.error('Error fetching visitor count:', err);
                    }
                });
            }
            setInterval(updateVisitorCount, 5000);

            function resetIdentityFields() {
                $('#row-name, #row-nik, #row-nisn, #row-class, #row-subject').hide();
                $('#member-photo').attr('src', '{{ asset('assets/img/avatar.png') }}');
                $('#row-name').show();
                $('#row-nik').show();
                $('#member-name').text(': ....');
                $('#member-nik').text(': ....');
                $('#member-nisn').text(': ....');
                $('#member-class').text(': ....');
                $('#member-subject').text(': ....');
            }

            function initializeCameraSiswa(cameraId) {
                html5QrcodeSiswa = new Html5Qrcode("reader-siswa");
                html5QrcodeSiswa.start({
                        facingMode: cameraId
                    }, {
                        fps: 10,
                        qrbox: 250
                    },
                    (decodedText) => {
                        handleScan(decodedText);
                        $('#qr-result-text-siswa').text(decodedText);
                        setTimeout(() => $('#qr-result-text-siswa').html('<small class="text-success">Ready Scan..</small>'), 1600);
                    },
                    (error) => console.warn(error)
                ).catch(err => console.error("Error starting camera:", err));
            }

            function stopCameraSiswa() {
                if (html5QrcodeSiswa) {
                    html5QrcodeSiswa.stop().then(() => {
                        html5QrcodeSiswa = null;
                        console.log("Camera stopped.");
                    }).catch(err => console.error("Error stopping camera:", err));
                }
            }

            $('#cameraModalSiswa').on('show.bs.modal', function() {
                let cameraId = $('#camera-select-siswa').val();
                initializeCameraSiswa(cameraId);
            });

            $('#cameraModalSiswa').on('hidden.bs.modal', function() {
                stopCameraSiswa();
            });

            $('#camera-select-siswa').on('change', function() {
                stopCameraSiswa();
                let cameraId = $(this).val();
                initializeCameraSiswa(cameraId);
            });

            $('#scanInputSiswa').on('keypress', function(e) {
                if (e.which === 13) {
                    var code = $(this).val();
                    if (code) {
                        handleScan(code);
                        $(this).val('');
                    }
                }
            });

            function handleScan(code) {
                lastScannedNik = code;
                $.ajax({
                    url: "{{ url('/check-member') }}",
                    type: 'POST',
                    data: {
                        code: code,
                        is_check_absensi: "Y",
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#row-name, #row-nik, #row-nisn, #row-class, #row-subject').hide();

                        if (response.type === 'siswa') {
                            let data = response.data;
                            $('#member-photo').attr('src', data.foto);
                            $('#row-name').show();
                            $('#member-name').text(': ' + data.nama_siswa);
                            $('#row-nik').show();
                            $('#member-nik').text(': ' + data.nik);
                            $('#row-class').show();
                            $('#member-class').text(': ' + data.kelas);
                        } else if (response.type === 'guru') {
                            let data = response.data;
                            $('#member-photo').attr('src', '{{ asset('assets/img/avatar.png') }}');
                            $('#row-name').show();
                            $('#member-name').text(': ' + data.nama_guru);
                            $('#row-nik').show();
                            $('#member-nik').text(': ' + data.nik);
                            $('#row-subject').show();
                            $('#member-subject').text(': ' + data.nama_mata_pelajaran);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Data ditemukan. Silakan simpan kunjungan.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#cameraModalSiswa').modal('hide');
                            stopCameraSiswa();
                        });
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Perhatian',
                            text: err.responseJSON?.message || 'Terjadi kesalahan',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#cameraModalSiswa').modal('hide');
                            stopCameraSiswa();
                        });
                    }
                });
            }

            $('#classVisitCheckbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#classVisitSection').show();
                } else {
                    $('#classVisitSection').hide();
                }
            });

            $('#saveVisit').on('click', function() {
                let guruId = $('#selectGuru').val();
                let kelasId = $('#selectKelas').val();
                let materi = $('#inputMateri').val();

                if ($('#classVisitCheckbox').is(':checked')) {
                    if (guruId && kelasId && materi) {
                        $.ajax({
                            url: '{{ url('/save-visit') }}',
                            type: 'POST',
                            data: {
                                guru_id: guruId,
                                kelas_id: kelasId,
                                materi: materi,
                                nik: lastScannedNik,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Kunjungan Kelas berhasil disimpan',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                $('#classVisitCheckbox').prop('checked', false);
                                $('#classVisitSection').hide();
                                $('#selectGuru').val('');
                                $('#selectKelas').val('');
                                $('#inputMateri').val('');
                                lastScannedNik = null;
                                resetIdentityFields();
                                updateVisitorCount();
                            },
                            error: function(err) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Perhatian',
                                    text: err.responseJSON?.message || 'Terjadi kesalahan',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: 'Semua field harus diisi',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } else {
                    if (lastScannedNik) {
                        $.ajax({
                            url: '{{ url('/save-visit') }}',
                            type: 'POST',
                            data: {
                                nik: lastScannedNik,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Absensi berhasil disimpan',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                lastScannedNik = null;
                                resetIdentityFields();
                                updateVisitorCount();
                            },
                            error: function(err) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Perhatian',
                                    text: err.responseJSON?.message || 'Terjadi kesalahan',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: 'Silakan scan NIK terlebih dahulu',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        });
    </script>
@endsection