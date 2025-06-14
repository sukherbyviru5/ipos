@extends('master')
@section('title', 'Buku Rusak/Hilang - Peminjaman')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporkan Buku Rusak/Hilang</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ url('admin/peminjaman/buku-rusak-hilang') }}">Buku
                            Rusak/Hilang</a></div>
                    <div class="breadcrumb-item">Buat Laporan</div>
                </div>
            </div>
            <div class="section-body">
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="section-title">Data Siswa</div>
                                </div>
                                <div class="scan-inputs">
                                    <div class="siswa-container">
                                        <div class="form-row mb-3">
                                            <div class="col-6 col-sm-6 col-md-6">
                                                <div class="form-group mesin-scan">
                                                    <label>Scan Kartu Siswa</label>
                                                    <a href="#" class="btn btn-info w-100" data-toggle="modal"
                                                        data-target="#machineModalSiswa">
                                                        <i class="fa-solid fa-keyboard"></i> Buka Scan Mesin
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-6 col-md-6">
                                                <div class="form-group kamera-scan">
                                                    <label>Scan dengan Kamera</label>
                                                    <a href="#" class="btn btn-primary w-100" data-toggle="modal"
                                                        data-target="#cameraModalSiswa">
                                                        <i class="fa-solid fa-camera"></i> Buka Scan Kamera
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="student-details" class="mb-4"></div>
                                    <div id="restart-section" class="form-group" style="display: none; margin-top: 10px;">
                                        <button type="button" class="btn btn-warning" id="restart-student-scan">Ulangi
                                            Scan</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="section-title">Data Buku</div>
                                </div>
                                <div id="book-details" class="list-group"></div>
                                <div class="book-container">
                                    <div id="book-scan-section" class="row mb-3" style="display: none;">
                                        <div class="col-6 col-sm-6 col-md-6">
                                            <div class="form-group mesin-scan">
                                                <label>Scan Kartu Buku</label>
                                                <a href="#" class="btn btn-info w-100" data-toggle="modal"
                                                    data-target="#machineModalBuku">
                                                    <i class="fa-solid fa-keyboard"></i> Buka Scan Mesin/Manual
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-6 col-md-6">
                                            <div class="form-group kamera-scan">
                                                <label>Scan dengan Kamera</label>
                                                <a href="#" class="btn btn-primary w-100" data-toggle="modal"
                                                    data-target="#cameraModalBuku">
                                                    <i class="fa-solid fa-camera"></i> Buka Scan Kamera
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Keadaan Buku</label>
                                        <select id="status_buku" class="form-control" required>
                                            <option value="">Pilih Keadaan Buku</option>
                                            <option value="rusak">Rusak</option>
                                            <option value="hilang">Hilang</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Sanksi</label>
                                        <input type="text" id="sanksi" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Laporan</label>
                                        <input type="date" id="tanggal_laporan" class="form-control"
                                            value="{{ date('Y-m-d') }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary mt-4" id="save-report" disabled>Simpan
                                        Laporan</button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ url('admin/peminjaman/buku-rusak-hilang') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Camera Modal for Siswa -->
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
                    <div class="custom-select-wrapper mb-3">
                        <select id="camera-select-siswa" class="form-control custom-select-styled">
                            <option value="user">Kamera Depan</option>
                            <option value="environment">Kamera Belakang</option>
                        </select>
                    </div>
                    <div id="reader-siswa" style="width: 100%;"></div>
                    <h4 class="fs-4 mb-1 card-title text-center">QR Code: <span id="qr-result-text-siswa"><small
                                class="text-success">Ready Scan..</small></span></h4>
                    <small class="text-center">Tunggu text <span class="text-success">Ready Scan..</span> lalu scan
                        lagi.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Machine Modal for Siswa -->
    <div class="modal fade" id="machineModalSiswa" tabindex="-1" aria-labelledby="machineModalSiswaLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="machineModalSiswaLabel">Scan Menggunakan Mesin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="scanInputSiswa" class="form-label">Masukkan NIK atau QR Code Siswa</label>
                        <input type="text" class="form-control" id="scanInputSiswa">
                        <small class="text-success">*Masukan NIK atau QR Code Siswa</small>
                    </div>
                    <div class="text-center">
                        <p>Sambungkan mesin absensi ke komputer... <br>Lalu Scan menggunakan mesin dan kode akan masuk
                            kedalam inputan diatas.</p>
                        <div class="row">
                            <div class="col-6">
                                <img src="{{ asset('assets/tutor_mesin.gif') }}" alt="" class="rounded">
                            </div>
                            <div class="col-6"><img src="{{ asset('assets/scan.png') }}" alt=""
                                    class="rounded w-50"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Camera Modal for Buku -->
    <div class="modal fade" id="cameraModalBuku" tabindex="-1" aria-labelledby="cameraModalBukuLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalBukuLabel">Scan QR Code Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="custom-select-wrapper mb-3">
                        <select id="camera-select-buku" class="form-control custom-select-styled">
                            <option value="user">Kamera Depan</option>
                            <option value="environment">Kamera Belakang</option>
                        </select>
                    </div>
                    <div id="reader-buku" style="width: 100%;"></div>
                    <h4 class="fs-4 mb-1 card-title text-center">QR Code: <span id="qr-result-text-buku"><small
                                class="text-success">Ready Scan..</small></span></h4>
                    <small class="text-center">Tunggu text <span class="text-success">Ready Scan..</span> lalu scan
                        lagi.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Machine Modal for Buku -->
    <div class="modal fade" id="machineModalBuku" tabindex="-1" aria-labelledby="machineModalBukuLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="machineModalBukuLabel">Scan atau Cari Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 position-relative">
                        <label for="mesinInputBuku" class="form-label">Masukkan QR Code atau Cari Buku</label>
                        <input type="text" class="form-control" id="mesinInputBuku" autocomplete="off">
                        <small class="text-success">*Masukkan QR Code atau ketik judul/kode buku untuk mencari</small>
                        <div id="book-suggestions" class="autocomplete-suggestions" style="display: none;"></div>
                    </div>
                    <div class="text-center">
                        <p>Sambungkan mesin absensi ke komputer untuk scan, atau ketik untuk mencari buku secara manual.</p>
                        <div class="row">
                            <div class="col-6">
                                <img src="{{ asset('assets/tutor_mesin.gif') }}" alt="" class="rounded">
                            </div>
                            <div class="col-6"><img src="{{ asset('assets/scan.png') }}" alt=""
                                    class="rounded w-50"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Audio -->
    <audio id="successSound" src="{{ asset('assets/success.mp3') }}" preload="auto"></audio>
    <audio id="errorSound" src="{{ asset('assets/error.mp3') }}" preload="auto"></audio>

    <style>
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
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #495057;
            pointer-events: none;
        }

        .student-table,
        .list-group-item {
            border-radius: 8px;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #e9ecef;
            background: none;
            transition: all 0.2s ease;
        }

        .student-table table,
        .list-group-item table {
            width: 100%;
            margin-bottom: 0;
            min-width: 300px;
            border-collapse: collapse;
        }

        .student-table th,
        .student-table td,
        .list-group-item th,
        .list-group-item td {
            padding: 8px;
            vertical-align: middle;
            white-space: nowrap;
            border: 1px solid #e9ecef;
            font-size: 13px;
        }

        .list-group-item:hover,
        .student-table:hover {
            background: none;
        }

        .autocomplete-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
            display: none;
        }

        .autocomplete-item {
            padding: 8px 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .autocomplete-item:hover {
            background: #f8f9fa;
        }

        .autocomplete-item span {
            display: block;
            font-size: 12px;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .form-row {
                display: flex;
                flex-wrap: nowrap;
                gap: 10px;
            }

            .form-row .col-6 {
                flex: 1;
                min-width: 0;
            }

            .btn {
                font-size: 14px;
                padding: 8px;
            }

            .student-table,
            .list-group-item {
                font-size: 14px;
                padding: 10px;
            }

            .custom-select-styled {
                font-size: 14px;
                padding: 8px 35px 8px 12px;
            }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let html5QrcodeSiswa = null;
            let html5QrcodeBuku = null;
            let scannedData = {
                siswa: null,
                buku: null
            };
            const studentDetails = $('#student-details');
            const bookDetails = $('#book-details');
            const saveButton = $('#save-report');
            const statusBuku = $('#status_buku');
            const sanksiInput = $('#sanksi');
            const bookInput = $('#mesinInputBuku');
            const suggestionsContainer = $('#book-suggestions');

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            statusBuku.on('change', function() {
                const status = $(this).val();
                if (status === 'rusak') {
                    sanksiInput.val('Fotocopy Buku');
                } else if (status === 'hilang') {
                    sanksiInput.val('Bayar Denda Sesuai Harga Buku');
                } else {
                    sanksiInput.val('');
                }
                checkSaveButton();
            });

            let searchTimeout;
            bookInput.on('input', function() {
                clearTimeout(searchTimeout);
                const query = $(this).val().trim();
                if (query.length < 2) {
                    suggestionsContainer.hide().empty();
                    return;
                }
                searchTimeout = setTimeout(() => {
                    // Show loading text
                    suggestionsContainer.html('<div class="text-center py-2">Mencari data...</div>')
                        .show();
                    $.ajax({
                        url: "{{ url('admin/peminjaman/buku-rusak-hilang/search') }}",
                        type: 'GET',
                        data: {
                            term: query
                        },
                        success: function(data) {
                            suggestionsContainer.empty();
                            if (data.length === 0) {
                                suggestionsContainer.html(
                                    '<div class="text-center py-2">Data tidak ditemukan</div>'
                                    ).show();
                                return;
                            }
                            data.forEach(book => {
                                suggestionsContainer.append(`
                        <div class="autocomplete-item" data-id="${book.id}" data-kode="${book.kode}" data-judul="${book.judul_buku}">
                            ${book.judul_buku}
                            <span>Kode: ${book.kode}</span>
                        </div>
                    `);
                            });
                            suggestionsContainer.show();
                        },
                        error: function(err) {
                            suggestionsContainer.hide().empty();
                            Toast.fire({
                                icon: 'error',
                                title: 'Gagal mencari buku'
                            });
                        }
                    });
                }, 300);
            });

            suggestionsContainer.on('click', '.autocomplete-item', function() {
                if (!scannedData.siswa) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Silakan scan kartu siswa terlebih dahulu.'
                    });
                    return;
                }
                const bookData = {
                    id: $(this).data('id'),
                    kode: $(this).data('kode'),
                    judul_buku: $(this).data('judul')
                };
                if (!scannedData.buku) {
                    scannedData.buku = bookData;
                    document.getElementById('successSound').play();
                    updateBookDetails();
                    Toast.fire({
                        icon: 'success',
                        title: `Buku: ${bookData.judul_buku} berhasil dipilih`
                    });
                    bookInput.val('');
                    suggestionsContainer.hide().empty();
                    $('#machineModalBuku').modal('hide');
                    checkSaveButton();
                } else {
                    document.getElementById('errorSound').play();
                    Toast.fire({
                        icon: 'warning',
                        title: 'Buku sudah dipilih.'
                    });
                }
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#mesinInputBuku, #book-suggestions').length) {
                    suggestionsContainer.hide().empty();
                }
            });

            function initializeCameraSiswa(cameraId) {
                html5QrcodeSiswa = new Html5Qrcode("reader-siswa");
                html5QrcodeSiswa.start({
                    facingMode: cameraId
                }, {
                    fps: 10,
                    qrbox: 250
                }, (decodedText) => {
                    handleScan(decodedText, 'siswa');
                    $('#qr-result-text-siswa').text(decodedText);
                    setTimeout(() => $('#qr-result-text-siswa').html(
                        '<small class="text-success">Ready Scan..</small>'), 1600);
                }).catch(err => console.error("Error starting camera:", err));
            }

            function initializeCameraBuku(cameraId) {
                html5QrcodeBuku = new Html5Qrcode("reader-buku");
                html5QrcodeBuku.start({
                    facingMode: cameraId
                }, {
                    fps: 10,
                    qrbox: 250
                }, (decodedText) => {
                    handleScan(decodedText, 'buku');
                    $('#qr-result-text-buku').text(decodedText);
                    setTimeout(() => $('#qr-result-text-buku').html(
                        '<small class="text-success">Ready Scan..</small>'), 1600);
                }).catch(err => console.error("Error starting book camera:", err));
            }

            function stopCameraSiswa() {
                if (html5QrcodeSiswa) {
                    html5QrcodeSiswa.stop().then(() => {
                        html5QrcodeSiswa = null;
                        console.log("Student camera stopped.");
                    }).catch(err => console.error("Error stopping student camera:", err));
                }
            }

            function stopCameraBuku() {
                if (html5QrcodeBuku) {
                    html5QrcodeBuku.stop().then(() => {
                        html5QrcodeBuku = null;
                        console.log("Book camera stopped.");
                    }).catch(err => console.error("Error stopping book camera:", err));
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

            $('#machineModalSiswa').on('shown.bs.modal', function() {
                $('#scanInputSiswa').focus();
            });

            $('#scanInputSiswa').on('keypress', function(e) {
                if (e.which === 13) {
                    var code = $(this).val();
                    if (code) {
                        handleScan(code, 'siswa');
                        $(this).val('');
                    }
                }
            });

            $('#cameraModalBuku').on('show.bs.modal', function() {
                if (!scannedData.siswa) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Silakan scan kartu siswa terlebih dahulu.'
                    });
                    $('#cameraModalBuku').modal('hide');
                    return;
                }
                let cameraId = $('#camera-select-buku').val();
                initializeCameraBuku(cameraId);
            });

            $('#cameraModalBuku').on('hidden.bs.modal', function() {
                stopCameraBuku();
            });

            $('#camera-select-buku').on('change', function() {
                stopCameraBuku();
                let cameraId = $(this).val();
                initializeCameraBuku(cameraId);
            });

            $('#machineModalBuku').on('shown.bs.modal', function() {
                if (!scannedData.siswa) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Silakan scan kartu siswa terlebih dahulu.'
                    });
                    $('#machineModalBuku').modal('hide');
                    return;
                }
                bookInput.focus();
            });

            $('#mesinInputBuku').on('keypress', function(e) {
                if (e.which === 13) {
                    var code = $(this).val();
                    if (code) {
                        handleScan(code, 'buku');
                        $(this).val('');
                        suggestionsContainer.hide().empty();
                    }
                }
            });

            $('#restart-student-scan').on('click', function() {
                location.reload();
            });

            function handleScan(code, type) {
                let url = type === 'siswa' ? "{{ url('admin/peminjaman/peminjaman-siswa/check-siswa') }}" :
                    "{{ url('admin/peminjaman/peminjaman-siswa/check-buku') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        code: code,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.type === 'siswa' && !scannedData.siswa) {
                            scannedData.siswa = response.data;
                            updateStudentDetails();
                            document.getElementById('successSound').play();
                            $('.siswa-container').hide();
                            $('#book-scan-section').show();
                            $('#machineModalSiswa').modal('hide');
                            $('#cameraModalSiswa').modal('hide');
                            $('#restart-section').show();
                        } else if (response.type === 'buku' && !scannedData.buku) {
                            scannedData.buku = response.data;
                            document.getElementById('successSound').play();
                            updateBookDetails();
                            bookInput.val('').focus();
                            $('#machineModalBuku').modal('hide');
                            $('#cameraModalBuku').modal('hide');
                            stopCameraBuku();
                            Toast.fire({
                                icon: 'success',
                                title: `Buku: ${response.data.judul_buku} berhasil discan`
                            });
                        } else {
                             document.getElementById('successSound').play();
                            Toast.fire({
                                icon: 'warning',
                                title: response.type === 'siswa' ? 'Siswa sudah discan.' :
                                    'Buku sudah dipilih.'
                            });
                        }
                        checkSaveButton();
                    },
                    error: function(err) {
                        document.getElementById('errorSound').play();
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON?.error || 'Unknown error'
                        });
                    }
                });
            }

            function updateStudentDetails() {
                studentDetails.empty();
                if (scannedData.siswa) {
                    studentDetails.append(`
                        <div class="student-table">
                            <table>
                                <tr>
                                    <td><strong>Nama Siswa</strong></td>
                                    <td>${scannedData.siswa.nama_siswa}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIK</strong></td>
                                    <td>${scannedData.siswa.nik}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>${scannedData.siswa.kelas}</td>
                                </tr>
                            </table>
                        </div>
                    `);
                }
            }

            function updateBookDetails() {
                bookDetails.empty();
                if (scannedData.buku) {
                    bookDetails.append(`
                        <div class="list-group-item">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Buku</th>
                                        <th>Kode Buku</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>${scannedData.buku.judul_buku}</td>
                                        <td>${scannedData.buku.kode}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `);
                }
            }

            function checkSaveButton() {
                saveButton.prop('disabled', !scannedData.siswa || !scannedData.buku || !statusBuku.val());
            }

            saveButton.on('click', function() {
                if (!scannedData.siswa || !scannedData.buku || !statusBuku.val()) return;
                saveButton.prop('disabled', true).text('Menyimpan...');
                $.ajax({
                    url: "{{ url('admin/peminjaman/buku-rusak-hilang') }}",
                    type: 'POST',
                    data: {
                        nik_siswa: scannedData.siswa.nik,
                        id_qr: scannedData.buku.id,
                        sanksi: sanksiInput.val(),
                        status_buku: statusBuku.val(),
                        status_sanksi: 'belum_selesai',
                        tanggal_laporan: $('#tanggal_laporan').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message || 'Laporan berhasil disimpan',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href =
                                    "{{ url('admin/peminjaman/buku-rusak-hilang') }}";
                            }
                        });
                    },
                    error: function(err) {
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON?.message || 'Unknown error'
                        });
                        saveButton.prop('disabled', false).text('Simpan Laporan');
                    },
                    complete: function() {
                        saveButton.prop('disabled', false).text('Simpan Laporan');
                    }
                });
            });
        });
    </script>
@endsection
