@extends('master')
@section('title', 'Data Produk')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Produk</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Data Produk</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Data Produk</h2>
                <p class="section-lead">Berikut adalah Data Produk.</p>
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
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Terjadi kesalahan!</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4>Data Seluruh Produk</h4>
                        <div class="card-header-form">
                            <div class="dropdown d-inline dropleft">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true"
                                    data-toggle="dropdown" aria-expanded="false">
                                    Tambah
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" data-toggle="modal" data-target="#addModal"
                                            href="#">Input Manual</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped mt-5">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Nama</th>
                                    <th>Merk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Foto</th>
                                    <th width="10px">Action</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/manage-master/products') }}" method="POST" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" placeholder="Masukkan Nama Produk" class="form-control" name="name" required>
                            <div class="invalid-feedback">
                                Masukkan Nama Produk
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Merk</label>
                            <select class="form-control" name="category_id" required>
                                <option value="">Pilih Merk</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Pilih Merk
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="text" placeholder="Masukkan Harga (Rp)" class="form-control rupiah" name="price" required>
                            <input type="hidden" name="raw_price" id="raw_price">
                            <div class="invalid-feedback">
                                Masukkan Harga
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" placeholder="Masukkan Stok" class="form-control" name="stock" required min="0">
                            <div class="invalid-feedback">
                                Masukkan Stok
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Neto <small>(optional)</small></label>
                            <input type="number" placeholder="Masukkan Neto" class="form-control" name="neto" min="0">
                            <div class="invalid-feedback">
                                Masukkan Neto
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Satuan <small>(optional)</small></label>
                            <input type="text" placeholder="Masukkan Satuan" class="form-control text-uppercase" name="pieces">
                            <div class="invalid-feedback">
                                Masukkan Satuan
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="Y">Aktif</option>
                                <option value="N">Non Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Merk
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gambar Produk <small>(optional, multiple)</small></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="foto" name="foto[]" multiple accept="image/*">
                                <label class="custom-file-label" for="foto">Pilih gambar...</label>
                            </div>
                            <div id="image-preview-add" class="mt-3 d-flex flex-wrap"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/manage-master/products/update') }}" method="POST" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="deleted_photos" id="deleted_photos">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" placeholder="Masukkan Nama Produk" class="form-control" name="name" required id="name">
                            <div class="invalid-feedback">
                                Masukkan Nama Produk
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Merk</label>
                            <select class="form-control" name="category_id" required id="category_id">
                                <option value="">Pilih Merk</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Pilih Merk
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="text" placeholder="Masukkan Harga (Rp)" class="form-control rupiah" name="price" required id="price">
                            <input type="hidden" name="raw_price" id="raw_price_update">
                            <div class="invalid-feedback">
                                Masukkan Harga
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" placeholder="Masukkan Stok" class="form-control" name="stock" required id="stock" min="0">
                            <div class="invalid-feedback">
                                Masukkan Stok
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Neto <small>(optional)</small></label>
                            <input type="number" placeholder="Masukkan Neto" class="form-control" name="neto" id="neto" min="0">
                            <div class="invalid-feedback">
                                Masukkan Neto
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Satuan <small>(optional)</small></label>
                            <input type="text" placeholder="Masukkan Satuan" class="form-control text-uppercase" name="pieces" id="pieces">
                            <div class="invalid-feedback">
                                Masukkan Satuan
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value="">Pilih Status</option>
                                <option value="Y">Aktif</option>
                                <option value="N">Non Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Merk
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gambar Produk <small>(optional, multiple)</small></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="foto_update" name="foto[]" multiple accept="image/*">
                                <label class="custom-file-label" for="foto_update">Pilih gambar...</label>
                            </div>
                            <div id="image-preview-update" class="mt-3 d-flex flex-wrap"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Rupiah formatting function
        function formatRupiah(angka) {
            let number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        function getRawNumber(rupiah) {
            return rupiah.replace(/[^0-9,-]/g, '').replace(',', '.');
        }

        $(document).ready(function() {
            // Update file input label with selected file names
            function updateFileLabel(input, label) {
                let fileName = Array.from(input.files).map(file => file.name).join(', ');
                $(label).text(fileName || 'Pilih gambar...');
            }

            $('#foto').on('change', function() {
                updateFileLabel(this, '#foto + .custom-file-label');
                let preview = $('#image-preview-add');
                preview.empty();
                Array.from(this.files).forEach(file => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        preview.append(`
                            <div class="img-preview mr-2 mb-2 position-relative">
                                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px;">
                            </div>
                        `);
                    };
                    reader.readAsDataURL(file);
                });
            });

            $('#foto_update').on('change', function() {
                updateFileLabel(this, '#foto_update + .custom-file-label');
                let preview = $('#image-preview-update');
                Array.from(this.files).forEach(file => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        preview.append(`
                            <div class="img-preview mr-2 mb-2 position-relative">
                                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px;">
                            </div>
                        `);
                    };
                    reader.readAsDataURL(file);
                });
            });

            $('.rupiah').on('input', function() {
                let rawValue = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(formatRupiah(rawValue));
                if ($(this).attr('id') === 'price') {
                    $('#raw_price_update').val(rawValue);
                } else {
                    $('#raw_price').val(rawValue);
                }
            });

            // DataTable initialization
            $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/manage-master/products/all') }}",
                    type: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'category_name', name: 'category_name' },
                    { 
                        data: 'price_display', 
                        name: 'price_display',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'stock', name: 'stock' },
                    { data: 'status', name: 'status' },
                    { data: 'photos_preview', name: 'photos_preview' },
                    { data: 'action', name: 'action' }
                ]
            });

            // Edit button handler
            $('.table').on('click', '.edit[data-id]', function(e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        'id': $(this).data('id'),
                        '_token': "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ url('admin/manage-master/products/get') }}",
                    beforeSend: function() {
                        $.LoadingOverlay("show", {
                            image: "",
                            fontawesome: "fa fa-cog fa-spin"
                        });
                    },
                    complete: function() {
                        $.LoadingOverlay("hide");
                    },
                    success: function(data) {
                        $('#id').val(data.id);
                        $('#name').val(data.name);
                        $('#category_id').val(data.category_id);
                        $('#price').val(formatRupiah(data.price));
                        $('#raw_price_update').val(data.price);
                        $('#stock').val(data.stock);
                        $('#neto').val(data.neto);
                        $('#status').val(data.status);
                        $('#pieces').val(data.pieces);
                        $('#deleted_photos').val('');
                        let preview = $('#image-preview-update');
                        preview.empty();
                        data.photos.forEach(photo => {
                            preview.append(`
                                <div class="img-preview mr-2 mb-2 position-relative" data-id="${photo.id}">
                                    <img src="{{ asset('') }}${photo.foto}" class="img-thumbnail" style="width: 100px; height: 100px;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: 0; right: 0;" onclick="removePhoto(${photo.id})">×</button>
                                </div>
                            `);
                        });
                        $('#foto_update + .custom-file-label').text('Pilih gambar...');
                        $('#updateModal').modal('show');
                    },
                    error: function(err) {
                        alert('Error: ' + err.responseText);
                        console.log(err);
                    }
                });
            });

            // Delete button handler
            $('.table').on('click', '.hapus[data-id]', function(e) {
                e.preventDefault();
                swal({
                    title: "Hapus Produk?",
                    text: "Data Produk ini akan dihapus secara permanen!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            data: {
                                'id': $(this).data('id'),
                                '_token': "{{ csrf_token() }}"
                            },
                            type: 'DELETE',
                            url: "{{ url('admin/manage-master/products') }}",
                            beforeSend: function() {
                                $.LoadingOverlay("show", {
                                    image: "",
                                    fontawesome: "fa fa-cog fa-spin"
                                });
                            },
                            complete: function() {
                                $.LoadingOverlay("hide");
                            },
                            success: function(data) {
                                swal(data.message).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(err) {
                                alert('Error: ' + err.responseText);
                                console.log(err);
                            }
                        });
                    }
                });
            });
        });

        function removePhoto(photoId) {
            let deletedPhotos = $('#deleted_photos').val();
            deletedPhotos = deletedPhotos ? deletedPhotos + ',' + photoId : photoId;
            $('#deleted_photos').val(deletedPhotos);
            $(`#image-preview-update .img-preview[data-id="${photoId}"]`).remove();
        }
    </script>
@endsection