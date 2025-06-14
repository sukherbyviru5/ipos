@extends('master')
@section('title', 'Setting Peminjaman')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Setting Peminjaman</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Setting Peminjaman</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Setting Peminjaman</h2>
                <p class="section-lead">Konfigurasi pengaturan peminjaman buku untuk semua siswa.</p>

                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session()->get('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Konfigurasi Peminjaman</h4>
                    </div>
                    <form class="card-body"
                        action="{{ isset($setting) ? route('admin.peminjaman.settings.update', $setting->id ?? '') : route('admin.peminjaman.settings.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($setting))
                            @method('PUT')
                        @endif

                        <!-- Batas Jumlah Buku -->
                        <div class="form-group">
                            <label for="batas_jumlah_buku_status">Batas Jumlah Buku</label> <br>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="batas_jumlah_buku_status" class="custom-control-input"
                                    id="batas_jumlah_buku_status" value="aktif"
                                    {{ old('batas_jumlah_buku_status', isset($setting) && $setting->batas_jumlah_buku_status === 'aktif' ? 'aktif' : '') == 'aktif' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="batas_jumlah_buku_status">Non Aktif / Aktif</label>
                            </div>
                            <div class="input-group mt-2 batas_jumlah_buku_group"
                                style="display: {{ old('batas_jumlah_buku_status', isset($setting) && $setting->batas_jumlah_buku_status === 'aktif' ? 'aktif' : '') == 'aktif' ? 'flex' : 'none' }};">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                </div>
                                <input type="number" name="batas_jumlah_buku" id="batas_jumlah_buku"
                                    class="form-control batas_jumlah_buku"
                                    value="{{ old('batas_jumlah_buku', isset($setting) && $setting->batas_jumlah_buku !== 'non aktif' ? $setting->batas_jumlah_buku : '') }}"
                                    placeholder="Ex: 2" min="1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Buku</span>
                                </div>
                            </div>
                            <div class="invalid-feedback d-block"
                                style="display: {{ $errors->has('batas_jumlah_buku') ? 'flex' : 'none' }};">
                                {{ $errors->first('batas_jumlah_buku') }}
                            </div>
                        </div>

                        <!-- Lama Peminjaman -->
                        <div class="form-group">
                            <label for="lama_peminjaman_status">Lama Peminjaman</label> <br>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="lama_peminjaman_status" class="custom-control-input"
                                    id="lama_peminjaman_status" value="aktif"
                                    {{ old('lama_peminjaman_status', isset($setting) && $setting->lama_peminjaman_status === 'aktif' ? 'aktif' : '') == 'aktif' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="lama_peminjaman_status">Non Aktif / Aktif</label>
                            </div>
                            <div class="input-group mt-2 lama_peminjaman_group"
                                style="display: {{ old('lama_peminjaman_status', isset($setting) && $setting->lama_peminjaman_status === 'aktif' ? 'aktif' : '') == 'aktif' ? 'flex' : 'none' }};">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                                <input type="number" name="lama_peminjaman" id="lama_peminjaman"
                                    class="form-control lama_peminjaman"
                                    value="{{ old('lama_peminjaman', isset($setting) && $setting->lama_peminjaman !== 'non aktif' ? $setting->lama_peminjaman : '') }}"
                                    placeholder="Ex: 14" min="1">
                                <div class="input-group-append">
                                    <span class="input-group-text">Hari</span>
                                </div>
                            </div>
                            <div class="invalid-feedback d-block"
                                style="display: {{ $errors->has('lama_peminjaman') ? 'flex' : 'none' }};">
                                {{ $errors->first('lama_peminjaman') }}
                            </div>
                        </div>

                        <!-- Lama Perpanjangan -->
                        <div class="form-group">
                            <label for="lama_perpanjangan_status">Lama Perpanjangan</label> <br>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="lama_perpanjangan_status" class="custom-control-input"
                                    id="lama_perpanjangan_status" value="aktif"
                                    {{ old('lama_perpanjangan_status', isset($setting) && $setting->lama_perpanjangan_status === 'aktif' ? 'aktif' : '') == 'aktif' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="lama_perpanjangan_status">Non Aktif / Aktif</label>
                            </div>
                            <div class="input-group mt-2 lama_perpanjangan_group"
                                style="display: {{ old('lama_perpanjangan_status', isset($setting) && $setting->lama_perpanjangan_status === 'aktif' ? 'aktif' : '') == 'aktif' ? 'flex' : 'none' }};">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                </div>
                                <input type="number" name="lama_perpanjangan" id="lama_perpanjangan"
                                    class="form-control lama_perpanjangan"
                                    value="{{ old('lama_perpanjangan', isset($setting) && $setting->lama_perpanjangan !== 'non aktif' ? $setting->lama_perpanjangan : '') }}"
                                    placeholder="Ex: 14" min="1">
                                <div class="input-group-append">
                                    <span class="input-group-text">Hari</span>
                                </div>
                            </div>
                            <div class="invalid-feedback d-block"
                                style="display: {{ $errors->has('lama_perpanjangan') ? 'flex' : 'none' }};">
                                {{ $errors->first('lama_perpanjangan') }}
                            </div>
                        </div>

                        <!-- Batas Perpanjangan -->
                        <div class="form-group">
                            <label for="batas_perpanjangan_status">Batas Perpanjangan</label> <br>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="batas_perpanjangan_status" class="custom-control-input"
                                    id="batas_perpanjangan_status" value="aktif"
                                    {{ old('batas_perpanjangan_status', isset($setting) && $setting->batas_perpanjangan_status === 'aktif' ? 'aktif' : '') == 'aktif' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="batas_perpanjangan_status">Non Aktif /
                                    Aktif</label>
                            </div>
                            <div class="input-group mt-2 batas_perpanjangan_group"
                                style="display: {{ old('batas_perpanjangan_status', isset($setting) && $setting->batas_perpanjangan_status === 'aktif' ? 'aktif' : '') == 'aktif' ? 'flex' : 'none' }};">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-redo"></i></span>
                                </div>
                                <input type="number" name="batas_perpanjangan" id="batas_perpanjangan"
                                    class="form-control batas_perpanjangan"
                                    value="{{ old('batas_perpanjangan', isset($setting) && $setting->batas_perpanjangan !== 'non aktif' ? $setting->batas_perpanjangan : '') }}"
                                    placeholder="Ex: 1" min="1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Kali</span>
                                </div>
                            </div>
                            <div class="invalid-feedback d-block"
                                style="display: {{ $errors->has('batas_perpanjangan') ? 'flex' : 'none' }};">
                                {{ $errors->first('batas_perpanjangan') }}
                            </div>
                        </div>

                        <!-- Denda Telat -->
                        <div class="form-group">
                            <label for="denda_telat_status">Denda Telat</label> <br>
                            <div class="custom-control custom-switch"> 
                                <input type="checkbox" name="denda_telat_status" class="custom-control-input"
                                    id="denda_telat_status" value="aktif"
                                    {{ old('denda_telat_status', isset($setting) && $setting->denda_telat_status === 'aktif' ? 'aktif' : '') == 'aktif' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="denda_telat_status">Non Aktif / Aktif</label>
                            </div>
                            <div class="input-group mt-2 denda_telat_group"
                                style="display: {{ old('denda_telat_status', isset($setting) && $setting->denda_telat_status === 'aktif' ? 'aktif' : '') == 'aktif' ? 'flex' : 'none' }};">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="denda_telat" id="denda_telat"
                                    class="form-control denda_telat"
                                    value="{{ old('denda_telat', isset($setting) && $setting->denda_telat !== 'non aktif' ? $setting->denda_telat : '') }}"
                                    placeholder="Ex: 5000" min="0">
                            </div>
                            <div class="invalid-feedback d-block"
                                style="display: {{ $errors->has('denda_telat') ? 'flex' : 'none' }};">
                                {{ $errors->first('denda_telat') }}
                            </div>
                        </div>

                        <!-- Perhitungan Denda -->
                        <div class="form-group">
                            <label for="perhitungan_denda">Perhitungan Denda</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                                </div>
                                <select name="perhitungan_denda" id="perhitungan_denda" class="form-control" required>
                                    <option value="non aktif"
                                        {{ old('perhitungan_denda', isset($setting) ? $setting->perhitungan_denda : '') == 'non aktif' ? 'selected' : '' }}>
                                        Non Aktif</option>
                                    <option value="per hari"
                                        {{ old('perhitungan_denda', isset($setting) ? $setting->perhitungan_denda : '') == 'per hari' ? 'selected' : '' }}>
                                        Per Hari</option>
                                    <option value="per minggu"
                                        {{ old('perhitungan_denda', isset($setting) ? $setting->perhitungan_denda : '') == 'per minggu' ? 'selected' : '' }}>
                                        Per Minggu</option>
                                </select>
                            </div>
                            <div class="invalid-feedback">
                                Pilih Perhitungan Denda
                            </div>
                        </div>

                        <!-- Syarat Peminjaman -->
                        <div class="form-group">
                            <label for="syarat_peminjaman">Syarat Peminjaman</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                </div>
                                <textarea name="syarat_peminjaman" id="syarat_peminjaman" class="form-control" required
                                    placeholder="Ex: Siswa harus memiliki kartu perpustakaan aktif">{{ old('syarat_peminjaman', isset($setting) ? $setting->syarat_peminjaman : '') }}</textarea>
                            </div>
                            <div class="invalid-feedback">
                                Masukkan Syarat Peminjaman
                            </div>
                        </div>

                        <!-- Syarat Perpanjangan -->
                        <div class="form-group">
                            <label for="syarat_perpanjangan">Syarat Perpanjangan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                </div>
                                <textarea name="syarat_perpanjangan" id="syarat_perpanjangan" class="form-control" required
                                    placeholder="Ex: Buku harus dibawa untuk diperpanjang">{{ old('syarat_perpanjangan', isset($setting) ? $setting->syarat_perpanjangan : '') }}</textarea>
                            </div>
                            <div class="invalid-feedback">
                                Masukkan Syarat Perpanjangan
                            </div>
                        </div>

                        <!-- Syarat Pengembalian -->
                        <div class="form-group">
                            <label for="syarat_pengembalian">Syarat Pengembalian</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                </div>
                                <textarea name="syarat_pengembalian" id="syarat_pengembalian" class="form-control" required
                                    placeholder="Ex: Buku harus dikembalikan dalam kondisi baik">{{ old('syarat_pengembalian', isset($setting) ? $setting->syarat_pengembalian : '') }}</textarea>
                            </div>
                            <div class="invalid-feedback">
                                Masukkan Syarat Pengembalian
                            </div>
                        </div>

                        <!-- Sanksi Kerusakan -->
                        <div class="form-group">
                            <label for="sanksi_kerusakan">Sanksi Kerusakan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-exclamation-triangle"></i></span>
                                </div>
                                <textarea name="sanksi_kerusakan" id="sanksi_kerusakan" class="form-control" required
                                    placeholder="Ex: Ganti rugi sesuai harga buku atau denda Rp 50.000">{{ old('sanksi_kerusakan', isset($setting) ? $setting->sanksi_kerusakan : '') }}</textarea>
                            </div>
                            <div class="invalid-feedback">
                                Masukkan Sanksi Kerusakan
                            </div>
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

    <script>
        $(document).ready(function() {
            function toggleNumberInput(statusSelector, inputGroupSelector, inputSelector) {
                $(statusSelector).on('change', function() {
                    if ($(this).is(':checked')) {
                        $(inputGroupSelector).show();
                        $(inputSelector).prop('required', true);
                    } else {
                        $(inputGroupSelector).hide();
                        $(inputSelector).prop('required', false).val('');
                    }
                });
            }

            toggleNumberInput('#batas_jumlah_buku_status', '.batas_jumlah_buku_group', '#batas_jumlah_buku');
            toggleNumberInput('#lama_peminjaman_status', '.lama_peminjaman_group', '#lama_peminjaman');
            toggleNumberInput('#lama_perpanjangan_status', '.lama_perpanjangan_group', '#lama_perpanjangan');
            toggleNumberInput('#batas_perpanjangan_status', '.batas_perpanjangan_group', '#batas_perpanjangan');
            toggleNumberInput('#denda_telat_status', '.denda_telat_group', '#denda_telat');

            $('#batas_jumlah_buku_status').trigger('change');
            $('#lama_peminjaman_status').trigger('change');
            $('#lama_perpanjangan_status').trigger('change');
            $('#batas_perpanjangan_status').trigger('change');
            $('#denda_telat_status').trigger('change');

            $('#denda_telat_status').on('change', function() {
                const $select = $('#perhitungan_denda');
                const $options = $select.find('option');

                if ($(this).is(':checked')) {
                    $options.prop('disabled', false);
                } else {
                    $select.val('non aktif');
                    $options.each(function() {
                        if ($(this).val() !== 'non aktif') {
                            $(this).prop('disabled', true);
                        }
                    });
                }
            }).trigger('change');
        });
    </script>
@endsection