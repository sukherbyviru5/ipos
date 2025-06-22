@extends('master')
@section('title', 'Peminjaman Guru - Peminjaman')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Buat Peminjaman Baru</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ url('admin/peminjaman/peminjaman-guru') }}">Peminjaman Guru</a>
                    </div>
                    <div class="breadcrumb-item">Buat Pinjaman</div>
                </div>
            </div>
            <div class="alert alert-primary">
                <strong>Langkah-langkah:</strong><br>
                1. Pilih metode scan (Mesin atau Kamera).<br>
                2. Scan kartu guru untuk memulai.<br>
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
                                     <div class="section-title">Data Guru</div>
                                </div>
                                <div class="scan-inputs">
                                    <div class="guru-container">
                                        <div class="form-row mb-3">
                                            <div class="col-6 col-sm-6 col-md-6">
                                                <div class="form-group mesin-scan">
                                                    <label>Scan Kartu Guru</label>
                                                    <a href="#" class="btn btn-info w-100" data-toggle="modal"
                                                        data-target="#machineModalGuru">
                                                        <i class="fa-solid fa-keyboard"></i> Buka Scan Mesin
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-6 col-md-6">
                                                <div class="form-group kamera-scan">
                                                    <label>Scan dengan Kamera</label>
                                                    <a href="#" class="btn btn-primary w-100" data-toggle="modal"
                                                        data-target="#cameraModalGuru">
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
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary mt-4" id="save-loan" disabled>Simpan
                                        Pinjaman</button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ url('admin/peminjaman/peminjaman-guru') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="cameraModalGuru" tabindex="-1" aria-labelledby="cameraModalSiswaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalSiswaLabel">Scan QR Code Guru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="custom-select-wrapper mb-3">
                        <select id="camera-select-guru" class="form-control custom-select-styled">
                            <option value="user">Kamera Depan</option>
                            <option value="environment">Kamera Belakang</option>
                        </select>
                    </div>
                    <div id="reader-guru" style="width: 100%;"></div>
                </div>
                <h4 class="fs-4 mb-1 card-title text-center">QR Code: <span id="qr-result-text-guru"><small
                            class="text-success">Ready Scan..</small></span></h4>
                <small class="text-center">Tunggu text <span class="text-success">Ready Scan..</span> lalu scan
                    lagi.</small>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="machineModalGuru" tabindex="-1" aria-labelledby="machineModalSiswaLabel"
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
                        <label for="scanInputGuru" class="form-label">Masukkan NIK atau QR Code Guru</label>
                        <input type="text" class="form-control" id="scanInputGuru">
                        <small class="text-success">*Masukan NIK atau QR Code Guru</small>
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
                        <label for="mesinInputBuku" class="form-label">Masukkan QR Code Buku</label>
                        <input type="text" class="form-control" id="mesinInputBuku">
                        <small class="text-success">*Masukan QR Code Buku</small>
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
    {{-- sound --}}
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

        .list-group-item,
        .teacher-table {
            border-radius: 8px;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #e9ecef;
            background: none;
            transition: all 0.2s ease;
        }

        .list-group-item:hover,
        .teacher-table:hover {
            background: none;
        }

        .teacher-table table,
        .list-group-item table {
            width: 100%;
            margin-bottom: 0;
            min-width: 300px;
            border-collapse: collapse;
        }

        .teacher-table th,
        .teacher-table td,
        .list-group-item th,
        .list-group-item td {
            padding: 8px;
            vertical-align: middle;
            white-space: nowrap;
            border: 1px solid #e9ecef;
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
            .teacher-table {
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
            $('body').addClass('sidebar-mini');
            $('.dropdown-menu').hide();

            let html5QrcodeSiswa = null;
            let html5QrcodeBuku = null;
            let scannedData = {
                guru: null,
                buku: []
            };
            const studentDetails = $('#student-details');
            const bookDetails = $('#book-details');
            const saveButton = $('#save-loan');

            // Initialize Toast mixin
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

            function initializeCameraSiswa(cameraId) {
                html5QrcodeSiswa = new Html5Qrcode("reader-guru");
                html5QrcodeSiswa.start({
                        facingMode: cameraId
                    }, {
                        fps: 10,
                        qrbox: 250
                    },
                    (decodedText) => {
                        handleScan(decodedText, 'guru');
                        $('#qr-result-text-guru').text(decodedText);
                        setTimeout(() => $('#qr-result-text-guru').html(
                            '<small class="text-success">Ready Scan..</small>'), 1600);
                    },
                    // (error) => console.warn(error)
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
                    // (error) => console.warn(error)
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

            $('#cameraModalGuru').on('show.bs.modal', function() {
                let cameraId = $('#camera-select-guru').val();
                initializeCameraSiswa(cameraId);
            });

            $('#cameraModalGuru').on('hidden.bs.modal', function() {
                stopCameraSiswa();
            });

            $('#camera-select-guru').on('change', function() {
                stopCameraSiswa();
                let cameraId = $(this).val();
                initializeCameraSiswa(cameraId);
            });

            $('#machineModalGuru').on('shown.bs.modal', function() {
                $('#scanInputGuru').focus();
            });

            $('#scanInputGuru').on('keypress', function(e) {
                if (e.which === 13) {
                    var code = $(this).val();
                    if (code) {
                        handleScan(code, 'guru');
                        $(this).val('');
                    }
                }
            });

            $('#cameraModalBuku').on('show.bs.modal', function() {
                if (!scannedData.guru) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Silakan scan kartu guru terlebih dahulu.'
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
                if (!scannedData.guru) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Silakan scan kartu guru terlebih dahulu.'
                    });
                    $('#machineModalBuku').modal('hide');
                    return;
                }
                $('#mesinInputBuku').focus();
            });

            $('#mesinInputBuku').on('keypress', function(e) {
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
                let url = type === 'guru' ? "{{ url('admin/peminjaman/peminjaman-guru/check-guru') }}" :
                    "{{ url('admin/peminjaman/peminjaman-guru/check-buku') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        code: code,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.type === 'guru' && !scannedData.guru) {
                            scannedData.guru = response.data;
                            updateStudentDetails();
                            document.getElementById('successSound').play();
                            $('.guru-container').hide();
                            $('#book-scan-section').show();
                            $('#machineModalGuru').modal('hide');
                            $('#cameraModalGuru').modal('hide');
                            $('#restart-section').show();
                        } else if (response.type === 'buku' && !scannedData.buku.some(b => b.id === response.data.id)) {
                            scannedData.buku.push(response.data);
                            document.getElementById('successSound').play();
                            updateBookDetails();
                            $('#mesinInputBuku').val('').focus();
                            Toast.fire({
                                icon: 'success',
                                title: `Buku: ${response.data.judul_buku} berhasil discan`
                            });
                        } else {
                            document.getElementById('successSound').play();
                            Toast.fire({
                                icon: 'warning',
                                title: response.type === 'guru' ? 'Guru sudah discan.' :
                                    'Buku ini sudah ditambahkan.'
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
                if (scannedData.guru) {
                    studentDetails.append(`
                        <div class="teacher-table">
                            <table>
                                <tr>
                                    <td><strong>Nama Guru</strong></td>
                                    <td>${scannedData.guru.nama_guru}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIK</strong></td>
                                    <td>${scannedData.guru.nik}</td>
                                </tr>
                                <tr>
                                    <td><strong>Mapel</strong></td>
                                    <td>${scannedData.guru.nama_mata_pelajaran}</td>
                                </tr>
                            </table>
                        </div>
                    `);
                }
            }

            function updateBookDetails() {
                bookDetails.empty();
                if (scannedData.buku.length > 0) {
                    let tableContent = `
                        <div class="list-group-item">
                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Buku</th>
                                        <th>Kode Buku</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    scannedData.buku.forEach((buku, index) => {
                        tableContent += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${buku.judul_buku}</td>
                                <td>${buku.kode}</td>
                                <td><i class="fa-solid fa-trash delete-book" data-id="${buku.id}"></i></td>
                            </tr>
                        `;
                    });
                    tableContent += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    bookDetails.append(tableContent);
                }
                $('.delete-book').on('click', function() {
                    const id = $(this).data('id');
                    scannedData.buku = scannedData.buku.filter(b => b.id !== id);
                    updateBookDetails();
                    checkSaveButton();
                });
            }

            function checkSaveButton() {
                saveButton.prop('disabled', !scannedData.guru || scannedData.buku.length === 0);
            }

            saveButton.on('click', function() {
                if (!scannedData.guru || scannedData.buku.length === 0) return;
                saveButton.prop('disabled', true).text('Menyimpan...');
                $.ajax({
                    url: "{{ url('admin/peminjaman/peminjaman-guru/store') }}",
                    type: 'POST',
                    data: {
                        nik_guru: scannedData.guru.nik,
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
                                window.location.href = "{{ url('admin/peminjaman/peminjaman-guru/result') }}" + "?grup=" + response.grup;
                            }
                        });
                    },
                    error: function(err) {
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON?.error || 'Unknown error'
                        });
                        saveButton.prop('disabled', false).text('Simpan Pinjaman');
                    },
                    complete: function() {
                        if (window.location.pathname.includes('peminjaman-guru')) {
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