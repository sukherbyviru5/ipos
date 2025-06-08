@extends('master')
@section('title', 'Data Buku - Perbarui')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Perbarui Data Buku</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ url('admin/data-buku') }}">Data Buku</a></div>
                    <div class="breadcrumb-item">Perbarui Data Buku</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Perbarui Data Buku</h2>
                <p class="section-lead">Isi formulir di bawah untuk menambahkan data buku.</p>
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <form class="needs-validation" id="form" method="POST" action="{{ url('admin/data-buku/update/'.  $buku->id) }}"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="card-header">
                            <h4>Form Perbarui Data Buku</h4>
                        </div>
                        <div class="card-body">
                            <!-- DDC -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">DDC</label>
                                <div class="col-sm-9">
                                    <select name="id_ddc" class="form-control select2" required>
                                        <option value="">- - Pilih DDC - -</option>
                                        @foreach ($ddcs as $ddc)
                                            <option value="{{ $ddc->id }}" {{ $buku->id_ddc == $ddc->id ? 'selected' : '' }}>- {{ $ddc->nama_klasifikasi }} -</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">- Pilih DDC</div>
                                    @error('id_ddc')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <!-- Kategori Buku -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Kategori Buku</label>
                                <div class="col-sm-9">
                                    <select name="id_kategori" class="form-control select2" required>
                                        <option value="">- Pilih Kategori -</option>
                                        @foreach ($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" {{ $buku->id_kategori == $kategori->id ? 'selected' : '' }}>- {{$kategori->no_urut. ' - ' . $kategori->nama_kategori }} -</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">- Pilih Kategori Buku</div>
                                    @error('id_kategori')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kondisi Buku -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Kondisi Buku</label>
                                <div class="col-sm-9">
                                    <select name="id_kondisi" class="form-control select2" required>
                                        <option value="">- Pilih Kondisi -</option>
                                        @foreach ($kondisis as $kondisi)
                                            <option value="{{ $kondisi->id }}" {{ $buku->id_kondisi == $kondisi->id ? 'selected' : '' }}>- {{ $kondisi->nama_kondisi ?? $kondisi->id }} -</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">- Pilih Kondisi Buku</div>
                                    @error('id_kondisi')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Jenis Buku -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Jenis Buku</label>
                                <div class="col-sm-9">
                                    <select name="id_jenis" class="form-control select2" required>
                                        <option value="">- Pilih Jenis Buku -</option>
                                        @foreach ($jeniss as $jenis)
                                            <option value="{{ $jenis->id }}" {{ $buku->id_jenis == $jenis->id ? 'selected' : '' }}>- {{ $jenis->nama_jenis ?? $jenis->id }} -</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">- Pilih Jenis Buku</div>
                                    @error('id_jenis')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Judul Buku -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Judul Buku</label>
                                <div class="col-sm-9">
                                    <input type="text" name="judul_buku" class="form-control" value="{{ $buku->judul_buku }}" placeholder="Masukkan Judul Buku" required>
                                    <div class="invalid-feedback">Masukkan Judul Buku</div>
                                    @error('judul_buku')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Singkatan Buku -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Singkatan Buku</label>
                                <div class="col-sm-9">
                                    <input type="text" name="singkatan_buku" class="form-control" value="{{ $buku->singkatan_buku }}" placeholder="Masukkan Singkatan Buku">
                                    @error('singkatan_buku')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- ISBN -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ISBN</label>
                                <div class="col-sm-9">
                                    <input type="text" name="isbn" class="form-control" value="{{ $buku->isbn }}" placeholder="Masukkan ISBN">
                                    @error('isbn')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Penulis Buku -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Penulis Buku</label>
                                <div class="col-sm-9">
                                    <input type="text" name="penulis_buku" class="form-control" value="{{ $buku->penulis_buku }}" placeholder="Masukkan Penulis Buku">
                                    @error('penulis_buku')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Penerbit Buku -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Penerbit Buku</label>
                                <div class="col-sm-9">
                                    <input type="text" name="penerbit_buku" class="form-control" value="{{ $buku->penerbit_buku }}" placeholder="Masukkan Penerbit Buku">
                                    @error('penerbit_buku')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tempat Terbit -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Tempat Terbit</label>
                                <div class="col-sm-9">
                                    <input type="text" name="tempat_terbit" class="form-control" value="{{ $buku->tempat_terbit }}" placeholder="Masukkan Tempat Terbit">
                                    @error('tempat_terbit')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tahun Terbit -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Tahun Terbit</label>
                                <div class="col-sm-9">
                                    <input type="number" name="tahun_terbit" class="form-control" value="{{ $buku->tahun_terbit }}" placeholder="Masukkan Tahun Terbit">
                                    @error('tahun_terbit')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Asal Buku -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Asal Buku</label>
                                <div class="col-sm-9">
                                    <input type="text" name="asal_buku" class="form-control" value="{{ $buku->asal_buku }}" placeholder="Masukkan Asal Buku (Contoh: Indonesia, Luar Negeri)">
                                    @error('asal_buku')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sinopsis -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Sinopsis</label>
                                <div class="col-sm-9">
                                    <textarea name="sinopsis" class="form-control"  placeholder="Masukkan Sinopsis Buku">{{ $buku->sinopsis }}</textarea>
                                    @error('sinopsis')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Harga Buku -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Harga Buku</label>
                                <div class="col-sm-9">
                                    <input type="number" name="harga_buku" class="form-control" value="{{ $buku->harga_buku }}" placeholder="Masukkan Harga Buku" step="0.01">
                                    @error('harga_buku')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Stok Buku -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Stok Buku</label>
                                <div class="col-sm-9">
                                    <input type="number" name="stok_buku" class="form-control" value="{{ $buku->stok_buku }}" placeholder="Masukkan Stok Buku" required>
                                    <div class="invalid-feedback">Masukkan Stok Buku</div>
                                    @error('stok_buku')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Lokasi Lemari -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Lokasi Lemari</label>
                                <div class="col-sm-9">
                                    <input type="text" name="lokasi_lemari" class="form-control" value="{{ $buku->lokasi_lemari }}" placeholder="Masukkan Lokasi Lemari">
                                    @error('lokasi_lemari')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Lokasi Rak -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Lokasi Rak</label>
                                <div class="col-sm-9">
                                    <input type="text" name="lokasi_rak" class="form-control" value="{{ $buku->lokasi_rak }}" placeholder="Masukkan Lokasi Rak">
                                    @error('lokasi_rak')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cover Buku -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Cover Buku</label>
                                <div class="col-sm-9">
                                    <input type="file" name="cover_buku" id="cover_buku" class="form-control" accept="image/*">
                                    <div id="image-preview" class="mt-2" style="max-width: 200px;">
                                        @if($buku->cover_buku)
                                            <img src="{{ url($buku->cover_buku) }}" alt="" class="img-fluid" style="max-width: 100%; height: auto;">
                                        @endif
                                    </div>
                                    @error('cover_buku')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ebook Tersedia -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Ebook Tersedia</label>
                                <div class="col-sm-9">
                                    <select name="ebook_tersedia" class="form-control" id="ebook_tersedia">
                                        <option {{ $buku->ebook_tersedia == '0' ? 'selected' : '' }} value="0">Tidak -</option>
                                        <option {{ $buku->ebook_tersedia == '1' ? 'selected' : '' }} value="1">Ya -</option>
                                    </select>
                                    @error('ebook_tersedia')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ebook File (Initially Hidden) -->
                            <div class="form-group row" id="ebook_file_container" style="display: none;">
                                <label class="col-sm-3 col-form-label">File Ebook</label>
                                <div class="col-sm-9">
                                    <input type="file" name="ebook_file" class="form-control" accept=".pdf">
                                    @if($buku->ebook_file)
                                        <a href="{{ url($buku->ebook_file) }}" class="badge badge-danger mt-2" target="_blank" rel="noopener noreferrer">
                                            <i class="fas fa-file-pdf"></i> Lihat File
                                        </a>
                                    @endif
                                    @error('ebook_file')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" id="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    
     <script>
         $(document).ready(function() {
            toggleEbookFileField();

            $('#ebook_tersedia').change(function() {
                toggleEbookFileField();
            });

            function toggleEbookFileField() {
                if ($('#ebook_tersedia').val() == '1') {
                    $('#ebook_file_container').show();
                } else {
                    $('#ebook_file_container').hide();
                }
            }

            $('#cover_buku').on('change', function(event) {
                const preview = $('#image-preview');
                preview.html(''); 
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = $('<img>', { 
                            src: e.target.result, 
                            style: 'max-width: 100%; height: auto;' 
                        });
                        preview.append(img);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('form.needs-validation').on('submit', function(e) {
                if (this.checkValidity()) {
                    $.LoadingOverlay("show", {
                        image: "",
                        fontawesome: "fa fa-cog fa-spin"
                    });
                } 
            });
        });
    </script>
@endsection
