@extends('guest')
@section('title', 'Profil Saya')
@section('content')
    <div class="container">
        <div class="profile-wrapper-area py-3">
            <!-- User Info Card -->
            <div class="card user-info-card">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="user-profile me-3">
                        <img style="border-radius: 5px;" src="{{ asset($siswa->foto ?? 'assets/img/avatar.png') }}"
                            alt="{{ e($siswa->nama_siswa) }}">
                    </div>
                    <div class="user-info">
                        <p class="mb-0 text-white">NIK: {{ e($siswa->nik) }}</p>
                        <h5 class="mb-0 text-white">{{ e($siswa->nama_siswa) }}</h5>
                    </div>
                </div>
            </div>
            <!-- User Meta Data -->
            <div class="card user-data-card">
                <div class="card-body">
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="ti ti-id"></i><span>NIK</span></div>
                        <div class="data-content">{{ e($siswa->nik) }}</div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="ti ti-id"></i><span>NISN</span></div>
                        <div class="data-content">{{ e($siswa->nisn) }}</div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="ti ti-building"></i><span>Kelas</span></div>
                        <div class="data-content">
                            {{ isset($siswa->kelas)
                                ? $siswa->kelas->tingkat_kelas .
                                    ' ' .
                                    $siswa->kelas->kelompok .
                                    ' ( ' .
                                    $siswa->kelas->urusan_kelas .
                                    ' ) ( Jurusan ' .
                                    $siswa->kelas->jurusan .
                                    ' )'
                                : 'Tidak tersedia' }}
                        </div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="ti ti-user"></i><span>Nama Lengkap</span>
                        </div>
                        <div class="data-content">{{ e($siswa->nama_siswa) }}</div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="ti ti-gender-male"></i><span>Jenis
                                Kelamin</span></div>
                        <div class="data-content">{{ $siswa->jenis_kelamin }}</div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="ti ti-home"></i><span>Tempat Lahir</span>
                        </div>
                        <div class="data-content">{{ $siswa->tempat_lahir ?? 'Tidak tersedia' }}</div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="ti ti-calendar"></i><span>Tanggal
                                Lahir</span></div>
                        <div class="data-content">
                            {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') : 'Tidak tersedia' }}
                        </div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="ti ti-map-pin"></i><span>Alamat</span></div>
                        <div class="data-content">{{ $siswa->alamat ?? 'Tidak tersedia' }}</div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="ti ti-phone"></i><span>No. HP</span></div>
                        <div class="data-content">{{ $siswa->no_hp ?? 'Tidak tersedia' }}</div>
                    </div>
                    @if ($siswa->is_alumni)
                        <div class="single-profile-data d-flex align-items-center justify-content-between">
                            <div class="title d-flex align-items-center"><i class="ti ti-school"></i><span>Status
                                    Alumni</span></div>
                            <div class="data-content">{{ $siswa->is_alumni ? 'Alumni' : 'Bukan Alumni' }}</div>
                        </div>
                    @endif
                    @if ($siswa->qr_code)
                        <div class="single-profile-data d-flex align-items-center justify-content-between">
                            <div class="title d-flex align-items-center"><i class="ti ti-qrcode"></i><span>QR Code</span>
                            </div>
                            <div class="data-content">
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm show-qr-code"
                                    data-qr="{{ e(asset($siswa->qr_code)) }}" data-nik="{{ e($siswa->nik) }}">Tampilkan QR
                                    Code</a>
                            </div>
                        </div>
                    @endif
                    <!-- Edit Profile -->
                    <div class="edit-profile-btn mt-3">
                        <a class="btn btn-primary btn-lg w-100" href="{{ url('siswa/profil/edit') }}"><i
                                class="ti ti-pencil me-2"></i>Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal QR Code -->
    <div class="qr-modal" id="qrModal">
        <div class="qr-modal-content">
            <span class="qr-modal-close">×</span>
            <img src="" alt="QR Code" class="qr-modal-image">
            <a href="" download="" class="btn btn-primary btn-sm mt-3 qr-download-btn">Download QR Code</a>
        </div>
    </div>

@endsection

@section('styles')
    <style>
        .qr-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            overflow: auto;
        }

        .qr-modal-content {
            position: relative;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .qr-modal-image {
            width: 300px;
            height: 300px;
            object-fit: contain;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .qr-modal-close {
            position: absolute;
            top: -30px;
            right: -10px;
            color: #fff;
            font-size: 30px;
            cursor: pointer;
            user-select: none;
        }

        .qr-download-btn {
            display: block;
            margin-top: 10px;
        }

        .qr-modal.show {
            display: flex;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrModal = document.getElementById('qrModal');
            const qrImage = qrModal.querySelector('.qr-modal-image');
            const qrDownloadBtn = qrModal.querySelector('.qr-download-btn');
            const qrClose = qrModal.querySelector('.qr-modal-close');
            const showQrButtons = document.querySelectorAll('.show-qr-code');

            showQrButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const qrSrc = this.getAttribute('data-qr');
                    const nik = this.getAttribute('data-nik');

                    if (qrSrc && nik) {
                        qrImage.src = qrSrc;
                        qrDownloadBtn.href = qrSrc;
                        qrDownloadBtn.download = `qr_code_${nik}.png`;
                        qrModal.classList.add('show');
                    } else {
                        console.error('Data QR atau NIK tidak valid:', {
                            qrSrc,
                            nik
                        });
                    }
                });
            });

            qrClose.addEventListener('click', function() {
                qrModal.classList.remove('show');
            });

            qrModal.addEventListener('click', function(e) {
                if (e.target === qrModal) {
                    qrModal.classList.remove('show');
                }
            });
        });
    </script>
@endsection
