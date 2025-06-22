@extends('guest')
@section('title', 'Update Profil Saya')
@section('content')
    <div class="container">
        <div class="profile-wrapper-area py-3">
            <!-- User Info Card -->
            <div class="card user-info-card">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="user-profile me-3">
                        <img id="profileImage" style="border-radius: 5px; width: 100px; height: 100px; object-fit: cover;"
                            src="{{ asset($siswa->foto ?? 'assets/img/avatar.png') }}"
                            alt="{{ e($siswa->nama_siswa) }}">
                        <div class="change-user-thumb mt-2">
                            <input class="form-control-file" type="file" id="fotoInput" name="foto" accept="image/*">
                        </div>
                    </div>
                    <div class="user-info">
                        <p class="mb-0 text-white">NIK: {{ e($siswa->nik) }}</p>
                        <h5 class="mb-0 text-white">{{ e($siswa->nama_siswa) }}</h5>
                    </div>
                </div>
            </div>
            <div class="card user-data-card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ url('siswa/profil') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <div class="title mb-2"><i class="ti ti-camera"></i><span>Foto Profil</span></div>
                            <input class="form-control" type="file" id="fotoFormInput" name="foto" accept="image/*">
                            @error('foto')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="title mb-2"><i class="ti ti-phone"></i><span>Nomor HP</span></div>
                            <input class="form-control" type="text" name="no_hp" value="{{ old('no_hp', $siswa->no_hp) }}"
                                placeholder="Masukkan nomor HP">
                            @error('no_hp')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="title mb-2"><i class="ti ti-map-pin"></i><span>Alamat</span></div>
                            <input class="form-control" type="text" name="alamat" value="{{ old('alamat', $siswa->alamat) }}"
                                placeholder="Masukkan alamat">
                            @error('alamat')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button class="btn btn-primary btn-lg w-100" type="submit">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection