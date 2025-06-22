@extends('layout.pages')
@section('title', 'Cek Bebas Perpustakaan')
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

        .row>.col-md-3,
        .row>.col-lg-6 {
            padding-right: 5px;
            padding-left: 5px;
        }

        #print-iframe {
            display: none;
        }
    </style>

    <nav class="navbar navbar-secondary navbar-expand-lg">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="{{ url('/buku-tamu') }}" class="nav-link"><i class="fa-solid fa-users"></i><span>Buku Tamu</span></a>
                </li>
                <li class="nav-item active">
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
                        <div class="col-md-3">
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

                        <div class="col-md-3">
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

                        <div class="col-lg-6 col-12">
                            <div class="card">
                                <div class="header">
                                    Informasi
                                </div>
                                <div class="card-body">
                                    <div id="loan-status" style="display: none;">
                                        <h6 class="text-center" id="loan-message"></h6>
                                        <div id="loan-list" style="display: none;"></div>
                                        <button class="btn btn-primary mt-3" id="print-receipt">Cetak</button>
                                    </div>
                                    <div id="no-scan-message">
                                        <h5 class="text-center">Silakan scan NIK untuk memeriksa status peminjaman.</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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

    <!-- Hidden Iframe for Printing -->
    <iframe id="print-iframe" name="print-iframe"></iframe>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let html5QrcodeSiswa = null;
            let lastScannedNik = null;
            let userData = null;
            let loanData = null;

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
                $('#loan-status').hide();
                $('#no-scan-message').show();
                $('#loan-message').text('');
                $('#loan-list').empty().hide();
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
                        is_check_absensi: "N",
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        userData = response.data;
                        $('#row-name, #row-nik, #row-nisn, #row-class, #row-subject').hide();

                        if (response.type === 'siswa') {
                            $('#member-photo').attr('src', userData.foto);
                            $('#row-name').show();
                            $('#member-name').text(': ' + userData.nama_siswa);
                            $('#row-nik').show();
                            $('#member-nik').text(': ' + userData.nik);
                            $('#row-class').show();
                            $('#member-class').text(': ' + userData.kelas);
                        } else if (response.type === 'guru') {
                            $('#member-photo').attr('src', '{{ asset('assets/img/avatar.png') }}');
                            $('#row-name').show();
                            $('#member-name').text(': ' + userData.nama_guru);
                            $('#row-nik').show();
                            $('#member-nik').text(': ' + userData.nik);
                            $('#row-subject').show();
                            $('#member-subject').text(': ' + userData.nama_mata_pelajaran);
                        }

                        // Check loan status
                        $.ajax({
                            url: "{{ url('/check-pinjaman') }}",
                            type: 'POST',
                            data: {
                                nik: lastScannedNik,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(loanResponse) {
                                $('#no-scan-message').hide();
                                $('#loan-status').show();
                                loanData = loanResponse.loans;

                                if (loanResponse.hasLoans) {
                                    $('#loan-message').html('MOHON MAAF, ANDA MASIH MEMILIKI BUKU PINJAMAN YANG HARUS DIKEMBALIKAN. SILAHKAN MENYELESAIKAN TERLEBIH DAHULU.<br>KLIK TOMBOL CETAK UNTUK MELIHAT DAFTAR BUKU YANG HARUS DIKEMBALIKAN.');
                                    $('#loan-list').show();
                                    $('#loan-list').empty();
                                    let tableHtml = `<table class="table table-bordered table-sm mt-2">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul Buku</th>
                                                <th>Kode Buku</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                    loanData.forEach((loan, index) => {
                                        tableHtml += `<tr>
                                            <td>${index + 1}</td>
                                            <td>${loan.buku_judul}</td>
                                            <td>${loan.kode_buku}</td>
                                        </tr>`;
                                    });
                                    tableHtml += `</tbody></table>`;

                                    $('#loan-list').html(tableHtml);

                                  
                                } else {
                                    $('#loan-message').text('SELAMAT, ANDA TIDAK MEMILIKI BUKU PINJAMAN YANG HARUS DIKEMBALIKAN ATAU SANKSI DARI PERPUSTAKAAN. KLIK TOMBOL CETAK UNTUK MENCETAK KETERANGAN BEBAS PERPUSTAKAAN.');
                                    $('#loan-list').hide();
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Data ditemukan.',
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
                                    text: err.responseJSON?.message || 'Terjadi kesalahan saat memeriksa peminjaman.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    $('#cameraModalSiswa').modal('hide');
                                    stopCameraSiswa();
                                });
                            }
                        });
                    },
                    error: function(err) {
                        resetIdentityFields();
                        Swal.fire({
                            icon: 'info',
                            title: 'Perhatian',
                            text: err.responseJSON?.message || 'Terjadi kesalahan.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#cameraModalSiswa').modal('hide');
                            stopCameraSiswa();
                        });
                    }
                });
            }

            $('#print-receipt').on('click', function() {
                if (!lastScannedNik) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: 'Silakan scan NIK terlebih dahulu.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }

                $.ajax({
                    url: "{{ url('/print-receipt') }}",
                    type: 'POST',
                    data: {
                        nik: lastScannedNik,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        const iframe = document.getElementById('print-iframe');
                        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                        iframeDoc.open();
                        iframeDoc.write(response);
                        iframeDoc.close();

                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Mencetak keterangan.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: err.responseJSON?.message || 'Terjadi kesalahan saat mencetak.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            });
        });
    </script>
@endsection