@extends('master')
@section('title', 'Setting Aplikasi')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Setting Aplikasi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Setting Aplikasi</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Setting Aplikasi</h2>
                <p class="section-lead">Konfigurasi pengaturan aplikasi untuk madrasah.</p>

                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session()->get('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session()->get('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Konfigurasi Aplikasi</h4>
                    </div>
                    <form class="card-body"
                        action="{{ isset($setting) ? route('admin.setting.apps.update', $setting->id) : route('admin.setting.apps.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($setting))
                            @method('PUT')
                        @endif

                        <!-- Nama Instansi -->
                        <div class="form-group">
                            <label for="nama_instansi">Nama Instansi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                </div>
                                <input type="text" name="nama_instansi" id="nama_instansi" class="form-control"
                                    value="{{ old('nama_instansi', $setting->nama_instansi ?? '') }}" required>
                            </div>
                            @error('nama_instansi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Sub Instansi -->
                        <div class="form-group">
                            <label for="nama_sub_instansi">Nama Sub Instansi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                </div>
                                <input type="text" name="nama_sub_instansi" id="nama_sub_instansi" class="form-control"
                                    value="{{ old('nama_sub_instansi', $setting->nama_sub_instansi ?? '') }}" required>
                            </div>
                            @error('nama_sub_instansi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Madrasah -->
                        <div class="form-group">
                            <label for="nama_madrasah">Nama Madrasah</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                </div>
                                <input type="text" name="nama_madrasah" id="nama_madrasah" class="form-control"
                                    value="{{ old('nama_madrasah', $setting->nama_madrasah ?? '') }}" required>
                            </div>
                            @error('nama_madrasah')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat Madrasah -->
                        <div class="form-group">
                            <label for="alamat_madrasah">Alamat Madrasah</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                </div>
                                <input type="text" name="alamat_madrasah" id="alamat_madrasah" class="form-control"
                                    value="{{ old('alamat_madrasah', $setting->alamat_madrasah ?? '') }}" required>
                            </div>
                            @error('alamat_madrasah')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Logo -->
                        <div class="form-group">
                            <label for="logo">Logo</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                                </div>
                                <input type="file" name="logo" id="logo" class="form-control">
                            </div>
                            @if (isset($setting) && $setting->logo)
                                <img src="{{ asset($setting->logo) }}" alt="Logo" style="max-width: 200px; margin-top: 10px;">
                            @endif
                            @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Kepala Madrasah -->
                        <div class="form-group">
                            <label for="nama_kepala_madrasah">Nama Kepala Madrasah</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="nama_kepala_madrasah" id="nama_kepala_madrasah" class="form-control"
                                    value="{{ old('nama_kepala_madrasah', $setting->nama_kepala_madrasah ?? '') }}" required>
                            </div>
                            @error('nama_kepala_madrasah')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NIP Kamad -->
                        <div class="form-group">
                            <label for="nip_kamad">NIP Kepala Madrasah</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input type="text" name="nip_kamad" id="nip_kamad" class="form-control"
                                    value="{{ old('nip_kamad', $setting->nip_kamad ?? '') }}" required>
                            </div>
                            @error('nip_kamad')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Kepala Perpustakaan -->
                        <div class="form-group">
                            <label for="nama_kepala_perpustakaan">Nama Kepala Perpustakaan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="nama_kepala_perpustakaan" id="nama_kepala_perpustakaan" class="form-control"
                                    value="{{ old('nama_kepala_perpustakaan', $setting->nama_kepala_perpustakaan ?? '') }}" required>
                            </div>
                            @error('nama_kepala_perpustakaan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NIP Kepala Perpustakaan -->
                        <div class="form-group">
                            <label for="nip_kepala_perpustakaan">NIP Kepala Perpustakaan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input type="text" name="nip_kepala_perpustakaan" id="nip_kepala_perpustakaan" class="form-control"
                                    value="{{ old('nip_kepala_perpustakaan', $setting->nip_kepala_perpustakaan ?? '') }}" required>
                            </div>
                            @error('nip_kepala_perpustakaan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Madrasah -->
                        <div class="form-group">
                            <label for="email_madrasah">Email Madrasah</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" name="email_madrasah" id="email_madrasah" class="form-control"
                                    value="{{ old('email_madrasah', $setting->email_madrasah ?? '') }}">
                            </div>
                            @error('email_madrasah')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- No Telepon -->
                        <div class="form-group">
                            <label for="no_telpon">No Telepon</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" name="no_telpon" id="no_telpon" class="form-control"
                                    value="{{ old('no_telpon', $setting->no_telpon ?? '') }}">
                            </div>
                            @error('no_telpon')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Embed Maps -->
                        <div class="form-group">
                            <label for="embed_maps">Embed Maps</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                </div>
                                <textarea name="embed_maps" id="embed_maps" class="form-control"
                                    placeholder="Masukkan kode embed peta">{{ old('embed_maps', $setting->embed_maps ?? '') }}</textarea>
                            </div>
                            @error('embed_maps')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Facebook -->
                        <div class="form-group">
                            <label for="facebook">Facebook</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                </div>
                                <input type="text" name="facebook" id="facebook" class="form-control"
                                    value="{{ old('facebook', $setting->facebook ?? '') }}"
                                    placeholder="Masukkan URL Facebook">
                            </div>
                            @error('facebook')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Instagram -->
                        <div class="form-group">
                            <label for="instagram">Instagram</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                </div>
                                <input type="text" name="instagram" id="instagram" class="form-control"
                                    value="{{ old('instagram', $setting->instagram ?? '') }}"
                                    placeholder="Masukkan URL Instagram">
                            </div>
                            @error('instagram')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- YouTube -->
                        <div class="form-group">
                            <label for="youtube">YouTube</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                </div>
                                <input type="text" name="youtube" id="youtube" class="form-control"
                                    value="{{ old('youtube', $setting->youtube ?? '') }}"
                                    placeholder="Masukkan URL YouTube">
                            </div>
                            @error('youtube')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group text-right">
                            <button type="submit"
                                class="btn btn-primary">{{ isset($setting) ? 'Update' : 'Simpan' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection