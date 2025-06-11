@extends('master')
@section('title', 'Profil Perpustakaan')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Profil Perpustakaan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Profil Perpustakaan</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Profil Perpustakaan</h2>
                <p class="section-lead">Konfigurasi pengaturan perpustakaan.</p>

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
                        <h4>Konfigurasi Profil</h4>
                    </div>
                    <form class="card-body"
                        action="{{ isset($setting) ? route('admin.setting.profil_perpustakaan.update', $setting->id) : route('admin.setting.profil_perpustakaan.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($setting))
                            @method('PUT')
                        @endif

                        <!-- Sejarah -->
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Sejarah</label>
                            <div class="col-sm-9">
                                <textarea name="sejarah" id="sejarah" class="form-control bg-white summernote" cols="30" rows="10">{{ old('sejarah', $setting->sejarah ?? '') }}</textarea>
                                @error('sejarah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Struktur Organisasi</label>
                            <div class="col-sm-9">
                                <input type="file" name="struktur_organisasi" id="struktur_organisasi"
                                    class="form-control" accept="image/*">
                                @if (isset($setting) && $setting->struktur_organisasi)
                                    <div class="mt-2">
                                        <img id="image_preview" src="{{ asset($setting->struktur_organisasi) }}"
                                            alt="Struktur Organisasi" style="max-width: 300px; max-height: 300px;">
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <img id="image_preview" src="" alt="Image Preview"
                                            style="max-width: 300px; max-height: 300px; display: none;">
                                    </div>
                                @endif
                                @error('struktur_organisasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Visi Misi</label>
                            <div class="col-sm-9">
                                <textarea name="visi_misi" id="visi_misi" class="form-control bg-white summernote" cols="30" rows="10">{{ old('visi_misi', $setting->visi_misi ?? '') }}</textarea>
                                @error('visi_misi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit"
                                class="btn btn-primary">{{ isset($setting) ? 'Update' : 'Simpan' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                toolbar: [],
                height: 200
            });

            $('#struktur_organisasi').on('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image_preview').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#image_preview').hide();
                }
            });
        });
    </script>
@endsection
