@extends('master')
@section('title', 'Daftar Peminjaman Buku')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar Peminjaman Buku</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Daftar Peminjaman Buku</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Daftar Peminjaman Buku</h2>
                <p class="section-lead">Berikut adalah daftar peminjaman buku guru terbaru.</p>
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
                        <h4>Daftar Peminjaman Buku</h4>
                        <div class="card-header-form">
                            <div class="dropdown d-inline dropleft">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true"
                                    data-toggle="dropdown" aria-expanded="false">
                                    Scan Kartu Guru
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" data-toggle="modal" data-target="#cameraModalGuru"
                                            href="#">Kamera</a></li>
                                    <li><a class="dropdown-item" data-toggle="modal" data-target="#machineModalSiswa"
                                            href="#">Mesin/Manual</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="SiswaTable">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Nama Guru</th>
                                    <th>Kode Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
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

    <!-- Modal Scan Kamera -->
    <div class="modal fade" id="cameraModalGuru" tabindex="-1" aria-labelledby="cameraModalGuruLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalGuruLabel">Scan QR Code Guru</h5>
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
                    <h4 class="fs-4 mb-1 card-title text-center">QR Code: <span id="qr-result-text-guru"><small
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

    <!-- Modal Scan Mesin/Manual -->
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
                        <label for="scanInputGuru" class="form-label">Masukkan NIK atau QR Code Guru</label>
                        <input type="text" class="form-control" id="scanInputGuru">
                        <small class="text-success">*Masukkan NIK atau QR Code Guru</small>
                    </div>
                    <div class="text-center">
                        <p>Sambungkan mesin absensi ke komputer... <br>Lalu scan menggunakan mesin dan kode akan masuk
                            ke dalam inputan di atas.</p>
                        <div class="row">
                            <div class="col-6">
                                <img src="{{ asset('assets/tutor_mesin.gif') }}" alt="" class="rounded">
                            </div>
                            <div class="col-6">
                                <img src="{{ asset('assets/scan.png') }}" alt="" class="rounded w-50">
                            </div>
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
            // Inisialisasi DataTables
            $('#SiswaTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/peminjaman/pengembalian-guru/all') }}",
                    type: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'guru.nama_guru', name: 'guru.nama_guru' },
                    { data: 'buku', name: 'buku' },
                    { data: 'tgl_pinjam', name: 'tgl_pinjam' },
                    { data: 'status_peminjaman', name: 'status_peminjaman' }
                ]
            });

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

            let html5QrcodeGuru = null;

            function initializeCameraGuru(cameraId) {
                html5QrcodeGuru = new Html5Qrcode("reader-guru");
                html5QrcodeGuru.start(
                    { facingMode: cameraId },
                    { fps: 10, qrbox: 250 },
                    (decodedText) => {
                        handleScan(decodedText, 'guru');
                        $('#qr-result-text-guru').text(decodedText);
                        setTimeout(() => $('#qr-result-text-guru').html(
                            '<small class="text-success">Ready Scan..</small>'), 1600);
                    },
                    (error) => console.warn("Error scanning QR code:", error)
                ).catch(err => console.error("Error starting camera:", err));
            }


            function stopCameraSiswa() {
                if (html5QrcodeGuru) {
                    html5QrcodeGuru.stop().then(() => {
                        html5QrcodeGuru = null;
                        console.log("Student camera stopped.");
                    }).catch(err => console.error("Error stopping student camera:", err));
                }
            }

            $('#cameraModalGuru').on('show.bs.modal', function() {
                let cameraId = $('#camera-select-guru').val();
                initializeCameraGuru(cameraId);
            });

            $('#cameraModalGuru').on('hidden.bs.modal', function() {
                stopCameraSiswa();
            });

            $('#camera-select-guru').on('change', function() {
                stopCameraSiswa();
                let cameraId = $(this).val();
                initializeCameraGuru(cameraId);
            });

            $('#machineModalSiswa').on('shown.bs.modal', function() {
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

            function handleScan(code, type) {
                if (type === 'guru') {
                    $.ajax({
                        url: "{{ url('admin/peminjaman/peminjaman-guru/check-guru') }}",
                        type: 'POST',
                        data: {
                            code: code,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.type === 'guru') {
                                document.getElementById('successSound').play();
                                $('#cameraModalGuru').modal('hide');
                                $('#machineModalSiswa').modal('hide');
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Guru ditemukan: ' + response.data.nama_guru
                                });
                                window.location.href = "{{ url('admin/peminjaman/pengembalian-guru/detail') }}/" + response.data.nik;
                            } else {
                                document.getElementById('errorSound').play();
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Kode tidak valid'
                                });
                            }
                        },
                        error: function() {
                            document.getElementById('errorSound').play();
                            Toast.fire({
                                icon: 'error',
                                title: 'Kode tidak ditemukan'
                            });
                        }
                    });
                }
            }
        });
    </script>
@endsection