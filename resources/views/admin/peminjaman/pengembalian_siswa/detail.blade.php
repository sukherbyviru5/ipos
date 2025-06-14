@extends('master')
@section('title', 'Pengembalian Buku Siswa - Detail')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pengembalian Buku Siswa</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ url('admin/peminjaman/pengembalian-siswa') }}">Pengembalian Siswa</a></div>
                    <div class="breadcrumb-item">Detail</div>
                </div>
            </div>
            <div class="alert alert-primary">
                <strong>Langkah-langkah:</strong><br>
                1. Informasi siswa ditampilkan setelah kartu siswa discan.<br>
                2. Daftar buku yang dipinjam muncul (buku terlambat berwarna merah).<br>
                3. Scan QR kode buku untuk mengembalikan.<br>
                4. Buku yang berhasil dikembalikan akan berubah warna menjadi hijau.<br>
                5. Jika ada denda, konfirmasi pembayaran denda sebelum pengembalian.
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
                        <div class="row mt-sm-4">
                            <div class="col-12 col-md-12 col-lg-5">
                                <div class="student-profile">
                                    <div class="text-center">
                                        <div class="student-photo mb-3">
                                            @if ($siswa->foto)
                                                <img src="{{ asset($siswa->foto) }}" alt="Foto Siswa" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('assets/img/avatar.png') }}" alt="Default Avatar" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                                            @endif
                                        </div>
                                        <h5 class="student-name mb-2">{{ $siswa->nama_siswa }}</h5>
                                    </div>
                                    <table class="table table-striped">
                                        <tr>
                                            <th>NIK</th>
                                            <td>: {{ $siswa->nik }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>: {{ $siswa->nama_siswa }}</td>
                                        </tr>
                                        <tr>
                                            <th>Kelas</th>
                                            <td>: {{ $siswa->kelas->nama_kelas ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>: {{ ucfirst($siswa->status) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-7">
                                <div class="form-group">
                                    <div class="section-title">Daftar Buku Dipinjam</div>
                                </div>
                                <div class="table-responsive">
                                    @if ($peminjaman->isEmpty())
                                        <div class="alert alert-info">Tidak ada buku yang dipinjam atau buku sudah dikembalikan semuanya.</div>
                                    @else
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Judul Buku</th>
                                                    <th>Kode Buku</th>
                                                    <th>Tgl Jatuh Tempo</th>
                                                    <th>Denda</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($peminjaman as $index => $item)
                                                    <tr class="book-row" data-id="{{ $item->id }}" data-book-id="{{ $item->qrBuku->buku->id }}"
                                                        style="background-color: {{ $item->status_peminjaman == 'telat' ? '#ffe6e6' : 'transparent' }};">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $item->qrBuku->buku->judul_buku }}</td>
                                                        <td>{{ $item->qrBuku->kode }}</td>
                                                        <td>{{ $item->tgl_jatuh_tempo }}</td>
                                                        <td>Rp {{ number_format($item->denda_total, 0, ',', '.') }}</td>
                                                        <td class="status-column">{{ ucfirst($item->status_peminjaman) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                                <hr>
                                <div class="section-title">Scan untuk Mengembalikan Pinjaman</div>
                                <div class="book-container">
                                    <div id="book-scan-section" class="form-row mb-3">
                                        <div class="col-6 col-sm-6 col-md-6">
                                            <div class="form-group mesin-scan">
                                                <label>Scan Kartu Buku</label>
                                                <a href="#" class="btn btn-info w-100" data-toggle="modal" data-target="#machineModalBuku">
                                                    <i class="fa-solid fa-keyboard"></i> Buka Scan Mesin
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-6 col-md-6">
                                            <div class="form-group kamera-scan">
                                                <label>Scan dengan Kamera</label>
                                                <a href="#" class="btn btn-primary w-100" data-toggle="modal" data-target="#cameraModalBuku">
                                                    <i class="fa-solid fa-camera"></i> Buka Scan Kamera
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($setting->denda_telat_status === 'aktif' && $peminjaman->sum('denda_total') > 0)
                                    <div class="alert alert-warning mt-3">
                                        <strong>Peringatan:</strong> Total denda Rp {{ number_format($peminjaman->sum('denda_total'), 0, ',', '.') }} harus dibayar sebelum pengembalian selesai.
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ url('admin/peminjaman/pengembalian-siswa') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal for Camera Scan -->
    <div class="modal fade" id="cameraModalBuku" tabindex="-1" aria-labelledby="cameraModalBukuLabel" aria-hidden="true">
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
                    <h4 class="fs-4 mb-1 card-title text-center">QR Code: <span id="qr-result-text-buku"><small class="text-success">Ready Scan..</small></span></h4>
                    <small class="text-center">Tunggu text <span class="text-success">Ready Scan..</span> lalu scan lagi.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Machine Scan -->
    <div class="modal fade" id="machineModalBuku" tabindex="-1" aria-labelledby="machineModalBukuLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                        <p>Sambungkan mesin absensi ke komputer... <br>Lalu Scan menggunakan mesin dan kode akan masuk kedalam inputan diatas.</p>
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

    <!-- Modal for Fine Confirmation -->
    <div class="modal fade" id="fineConfirmationModal" tabindex="-1" aria-labelledby="fineConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog bg-success rounded" style="border: 2px solid rgb(52, 255, 52);">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fineConfirmationModalLabel">Konfirmasi Pembayaran Denda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="fineConfirmationMessage"></p>
                    <p>Apakah denda sudah dibayar?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Belum Lunas</button>
                    <button type="button" class="btn btn-primary" id="confirmFinePaid">Ya, Lunas</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Audio for Feedback -->
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

        .student-profile {
            padding: 10px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .student-photo img {
            border: 3px solid #e9ecef;
            transition: transform 0.3s ease;
        }

        .student-photo img:hover {
            transform: scale(1.05);
        }

        .student-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #343a40;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table-responsive table {
            width: 100%;
            min-width: 600px;
        }

        .table th,
        .table td {
            padding: 10px;
            vertical-align: middle;
            white-space: nowrap;
        }

        .book-row.returned {
            background-color: #e6ffe6 !important;
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

            .student-photo img {
                width: 100px !important;
                height: 100px !important;
            }

            .student-name {
                font-size: 1.1rem;
            }

            .table th,
            .table td {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('body').addClass('sidebar-mini');
            $('.dropdown-menu').hide();

            let html5QrcodeBuku = null;
            let isScanningAllowed = true; 
            const SCAN_DELAY = 3000;

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

            function initializeCameraBuku(cameraId) {
                html5QrcodeBuku = new Html5Qrcode("reader-buku");
                html5QrcodeBuku.start(
                    { facingMode: cameraId },
                    { fps: 10, qrbox: 250 },
                    (decodedText) => {
                        if (isScanningAllowed) {
                            handleScan(decodedText);
                            $('#qr-result-text-buku').text(decodedText);
                            setTimeout(() => $('#qr-result-text-buku').html('<small class="text-success">Ready Scan..</small>'), 1600);
                        }
                    }
                ).catch(err => console.error("Error starting book camera:", err));
            }

            function stopCameraBuku() {
                if (html5QrcodeBuku) {
                    html5QrcodeBuku.stop().then(() => {
                        html5QrcodeBuku = null;
                        console.log("Book camera stopped.");
                    }).catch(err => console.error("Error stopping book camera:", err));
                }
            }

            $('#cameraModalBuku').on('show.bs.modal', function () {
                let cameraId = $('#camera-select-buku').val();
                initializeCameraBuku(cameraId);
            });

            $('#cameraModalBuku').on('hidden.bs.modal', function () {
                stopCameraBuku();
            });

            $('#camera-select-buku').on('change', function () {
                stopCameraBuku();
                let cameraId = $(this).val();
                initializeCameraBuku(cameraId);
            });

            $('#machineModalBuku').on('shown.bs.modal', function () {
                $('#mesinInputBuku').focus();
            });

            $('#mesinInputBuku').on('keypress', function (e) {
                if (e.which === 13 && isScanningAllowed) {
                    var code = $(this).val();
                    if (code) {
                        handleScan(code);
                        $(this).val('');
                    }
                }
            });

            let currentPeminjamanId, currentBookId, currentBookTitle;

            function handleScan(code) {
                if (!isScanningAllowed) return;

                isScanningAllowed = false;

                $.ajax({
                    url: "{{ url('admin/peminjaman/pengembalian-siswa/check-buku') }}",
                    type: 'POST',
                    data: {
                        code: code,
                        nik_siswa: "{{ $siswa->nik }}",
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.success) {
                            currentPeminjamanId = response.peminjaman_id;
                            currentBookId = response.book_id;
                            currentBookTitle = response.book_title;
                            const dendaTotal = response.denda_total;
                            const statusDenda = response.status_denda;

                            if (dendaTotal > 0 && statusDenda === 'belum_lunas') {
                                $('#fineConfirmationMessage').text(`Buku "${currentBookTitle}" memiliki denda Rp ${dendaTotal.toLocaleString('id-ID')}.`);
                                $('#fineConfirmationModal').modal('show');
                            } else {
                                proceedReturn(currentPeminjamanId, currentBookId, currentBookTitle, true);
                            }
                        } else {
                            document.getElementById('errorSound').play();
                            Toast.fire({
                                icon: 'error',
                                title: response.message || 'Buku tidak ditemukan atau tidak dipinjam oleh siswa ini.'
                            });
                        }
                    },
                    error: function (err) {
                        document.getElementById('errorSound').play();
                        Toast.fire({
                            icon: 'info',
                            title: err.responseJSON?.error || 'Buku tidak ada dalam list pinjaman atau sudah discan, refresh halaman.'
                        });
                    },
                    complete: function () {
                        setTimeout(() => {
                            isScanningAllowed = true;
                        }, SCAN_DELAY);
                    }
                });
            }

            function proceedReturn(peminjamanId, bookId, bookTitle, isDendaPaid) {
                $.ajax({
                    url: "{{ url('admin/peminjaman/pengembalian-siswa/update') }}",
                    type: 'POST',
                    data: {
                        peminjaman_id: peminjamanId,
                        tanggal_kembali: "{{ date('Y-m-d') }}",
                        status_peminjaman: 'dikembalikan',
                        is_denda_paid: isDendaPaid,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        document.getElementById('successSound').play();
                        $(`.book-row[data-id="${peminjamanId}"]`).addClass('returned');
                        $(`.book-row[data-id="${peminjamanId}"] .status-column`).text('Dikembalikan');
                        Toast.fire({
                            icon: 'success',
                            title: `Buku: ${bookTitle} berhasil dikembalikan.`
                        });

                        if ($('.book-row:not(.returned)').length === 0) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000); 
                        }
                    },
                    error: function (err) {
                        document.getElementById('errorSound').play();
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON?.error || 'Gagal memperbarui status.'
                        });
                    }
                });
            }

            $('#confirmFinePaid').on('click', function () {
                $('#fineConfirmationModal').modal('hide');
                proceedReturn(currentPeminjamanId, currentBookId, currentBookTitle, true);
            });

            $('#fineConfirmationModal').on('hidden.bs.modal', function () {
                if (!$(`.book-row[data-id="${currentPeminjamanId}"]`).hasClass('returned')) {
                    document.getElementById('errorSound').play();
                    Toast.fire({
                        icon: 'error',
                        title: `Pengembalian buku: ${currentBookTitle} dibatalkan karena denda belum lunas.`
                    });
                }
            });
        });
    </script>
@endsection