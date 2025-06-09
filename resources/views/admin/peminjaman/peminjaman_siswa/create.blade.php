@extends('master')
@section('title', 'Peminjaman Siswa - Peminjaman')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Buat Peminjaman Baru</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ url('admin/peminjaman/peminjaman-siswa') }}">Peminjaman Siswa</a>
                    </div>
                    <div class="breadcrumb-item">Buat Pinjaman</div>
                </div>
            </div>
            <div class="alert alert-primary">
                <strong>Langkah-langkah:</strong><br>
                1. Pilih metode scan (Mesin atau Kamera).<br>
                2. Scan kartu siswa untuk memulai.<br>
                3. Scan buku yang akan dipinjam (bisa lebih dari satu).<br>
                4. Klik "Simpan Pinjaman" setelah semua data lengkap untuk mencetak struk.
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
                                    <h6>Data Siswa</h6>
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
                                    <div id="restart-section" class="form-group" style="display : none; margin-top: 10px;">
                                        <button type="button" class="btn btn-warning" id="restart-student-scan">Ulangi
                                            Scan</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h6>Data Buku</h6>
                                </div>
                                <div class="book-container">
                                    <div id="book-scan-section" class="form-row mb-3" style="display: none;">
                                        <div class="col-6 col-sm-6 col-md-6">
                                            <div class="form-group mesin-scan">
                                                <label>Scan Kartu Buku</label>
                                                <a href="#" class="btn btn-info w-100" data-toggle="modal"
                                                    data-target="#machineModalBuku">
                                                    <i class="fa-solid fa-keyboard"></i> Buka Scan Mesin
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
                                </div>
                                <div id="book-details" class="list-group"></div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary mt-4" id="save-loan" disabled>Simpan
                                        Pinjaman</button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ url('admin/peminjaman/peminjaman-siswa') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
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
                </div>
                <h4 class="fs-4 mb-1 card-title text-center">QR Code: <span id="qr-result-text-siswa"><small
                            class="text-success">Ready Scan..</small></span></h4>
                <small class="text-center">Tunggu text <span class="text-success">Ready Scan..</span> lalu scan
                    lagi.</small>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
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
                </div>
                <h4 class="fs-4 mb-1 card-title text-center">QR Code: <span id="qr-result-text-buku"><small
                            class="text-success">Ready Scan..</small></span></h4>
                <small class="text-center">Tunggu text <span class="text-success">Ready Scan..</span> lalu scan
                    lagi.</small>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="machineModalBuku" tabindex="-1" aria-labelledby="machineModalBukuLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="machineModalBukuLabel">Scan Menggunakan Mesin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="absensiInputBuku" class="form-label">Masukkan QR Code Buku</label>
                        <input type="text" class="form-control" id="absensiInputBuku">
                        <small class="text-success">*Masukan QR Code Buku</small>
                    </div>
                    <div class="text-center">
                        <p>Sambungkan mesin absensi ke komputer... <br>Lalu Scan menggunakan mesin dan kode akan masuk
                            kedalam inputan diatas.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
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

        .list-group-item,
        .student-table {
            border-radius: 8px;
            margin-bottom: 10px;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .list-group-item:hover,
        .student-table:hover {
            background: #e9ecef;
        }

        .delete-book {
            cursor: pointer;
            color: #dc3545;
            margin-left: 10px;
            transition: color 0.2s ease;
        }

        .delete-book:hover {
            color: #bd2130;
        }

        .student-table {
            overflow-x: auto;
        }

        .student-table table {
            width: 100%;
            margin-bottom: 0;
            min-width: 300px;
        }

        .student-table th,
        .student-table td {
            padding: 8px;
            vertical-align: middle;
            white-space: nowrap;
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

            .list-group-item,
            .student-table {
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
                buku: []
            };
            const studentDetails = $('#student-details');
            const bookDetails = $('#book-details');
            const saveButton = $('#save-loan');

            function initializeCameraSiswa(cameraId) {
                html5QrcodeSiswa = new Html5Qrcode("reader-siswa");
                html5QrcodeSiswa.start({
                        facingMode: cameraId
                    }, {
                        fps: 10,
                        qrbox: 250
                    },
                    (decodedText) => {
                        handleScan(decodedText, 'siswa');
                        $('#qr-result-text-siswa').text(decodedText);
                        setTimeout(() => $('#qr-result-text-siswa').html(
                            '<small class="text-success">Ready Scan..</small>'), 1600);
                    },
                    (error) => console.warn(error)
                ).catch(err => console.error("Error starting camera:", err));
            }

            function initializeCameraBuku(cameraId) {
                html5QrcodeBuku = new Html5Qrcode("reader-buku");
                html5QrcodeBuku.start({
                        facingMode: cameraId
                    }, {
                        fps: 10,
                        qrbox: 250
                    },
                    (decodedText) => {
                        handleScan(decodedText, 'buku');
                        $('#qr-result-text-buku').text(decodedText);
                        setTimeout(() => $('#qr-result-text-buku').html(
                            '<small class="text-success">Ready Scan..</small>'), 1600);
                    },
                    (error) => console.warn(error)
                ).catch(err => console.error("Error starting book camera:", err));
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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan scan kartu siswa terlebih dahulu.',
                        confirmButtonText: 'OK'
                    }).then(() => $('#cameraModalBuku').modal('hide'));
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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan scan kartu siswa terlebih dahulu.',
                        confirmButtonText: 'OK'
                    }).then(() => $('#machineModalBuku').modal('hide'));
                    return;
                }
                $('#absensiInputBuku').focus();
            });
            $('#absensiInputBuku').on('keypress', function(e) {
                if (e.which === 13) {
                    var code = $(this).val();
                    if (code) {
                        handleScan(code, 'buku');
                        $(this).val('');
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
                        console.log('Response:', response);
                        if (response.type === 'siswa' && !scannedData.siswa) {
                            scannedData.siswa = response.data;
                            updateStudentDetails();
                            $('.siswa-container').hide();
                            $('#book-scan-section').show();
                            $('#machineModalSiswa').modal('hide');
                            $('#cameraModalSiswa').modal('hide');
                            $('#restart-section').show();
                        } else if (response.type === 'buku' && !scannedData.buku.some(b => b.id ===
                                response.data.id)) {
                            scannedData.buku.push(response.data);
                            updateBookDetails();
                            $('#absensiInputBuku').val('').focus();
                            Swal.fire({
                                icon: 'success',
                                title: 'Buku Berhasil Discan',
                                text: `Buku: ${response.data.judul_buku}`,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan',
                                text: response.type === 'siswa' ? 'Siswa sudah discan.' :
                                    'Buku ini sudah ditambahkan.',
                                confirmButtonText: 'OK'
                            });
                        }
                        checkSaveButton();
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Kode tidak valid: ' + (err.responseJSON?.error ||
                                'Unknown error'),
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }

            function updateStudentDetails() {
                studentDetails.empty();
                if (scannedData.siswa) {
                    studentDetails.append(`
                        <div class="student-table">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nama Siswa</th>
                                    <td>${scannedData.siswa.nama_siswa}</td>
                                </tr>
                                <tr>
                                    <th>NIK</th>
                                    <td>${scannedData.siswa.nik}</td>
                                </tr>
                                <tr>
                                    <th>Kelas</th>
                                    <td>${scannedData.siswa.kelas}</td>
                                </tr>
                            </table>
                        </div>
                    `);
                }
            }

            function updateBookDetails() {
                bookDetails.empty();
                scannedData.buku.forEach((buku, index) => {
                    bookDetails.append(`
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${index + 1}. Buku:</strong> ${buku.judul_buku}<br>
                                <small>Kode Buku: ${buku.kode}</small>
                            </div>
                            <i class="fa-solid fa-trash delete-book" data-id="${buku.id}"></i>
                        </div>
                    `);
                });
                $('.delete-book').on('click', function() {
                    const id = $(this).data('id');
                    scannedData.buku = scannedData.buku.filter(b => b.id !== id);
                    updateBookDetails();
                    checkSaveButton();
                });
            }

            function checkSaveButton() {
                saveButton.prop('disabled', !scannedData.siswa || scannedData.buku.length === 0);
            }
            saveButton.on('click', function() {
                if (!scannedData.siswa || scannedData.buku.length === 0) return;
                saveButton.prop('disabled', true).text('Menyimpan...');
                $.ajax({
                    url: "{{ url('admin/peminjaman/peminjaman-siswa/store') }}",
                    type: 'POST',
                    data: {
                        nik_siswa: scannedData.siswa.nik,
                        buku: scannedData.buku.map(b => b.id),
                        tanggal_pinjam: "{{ date('Y-m-d') }}",
                        tanggal_jatuh_tempo: calculateDueDate(),
                        status_peminjaman: 'dipinjam',
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href =
                                    "{{ url('admin/peminjaman/peminjaman-siswa') }}";
                            }
                        });
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error: ' + (err.responseJSON?.error ||
                                'Unknown error'),
                            confirmButtonText: 'OK'
                        });
                        saveButton.prop('disabled', false).text('Simpan Pinjaman');
                    },
                    complete: function() {
                        if (window.location.pathname.includes('peminjaman-siswa')) {
                            saveButton.prop('disabled', false).text('Simpan Pinjaman');
                        }
                    }
                });
            });

            function calculateDueDate() {
                return "{{ date('Y-m-d', strtotime('+7 days')) }}";
            }
        });
    </script>
@endsection
