@extends('master')
@section('title', 'Setting Peminjaman - ')
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
                <p class="section-lead">Berikut adalah Setting Peminjaman yang telah dibuat.</p>
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
                        <h4>Setting Peminjaman</h4>
                        <div class="card-header-form">
                            <a href="#" data-toggle="modal" data-target="#addModal" type="button"
                                class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Batas Buku</th>
                                    <th>Lama Peminjaman</th>
                                    <th>Lama Perpanjangan</th>
                                    <th>Batas Perpanjangan</th>
                                    <th>Denda Telat</th>
                                    <th>Perhitungan Denda</th>
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

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModal">Tambah Setting Peminjaman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/peminjaman/settings') }}" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Batas Jumlah Buku</label>
                            <select name="batas_jumlah_buku_status" class="form-control batas_jumlah_buku_status" required="">
                                <option value="">Pilih Status</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Batas Jumlah Buku
                            </div>
                            <input type="number" name="batas_jumlah_buku" class="form-control batas_jumlah_buku mt-2" style="display: none;" placeholder="Ex: 2" min="1">
                            <div class="invalid-feedback">
                                Masukkan Jumlah Buku
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Lama Peminjaman</label>
                            <select name="lama_peminjaman_status" class="form-control lama_peminjaman_status" required="">
                                <option value="">Pilih Status</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Lama Peminjaman
                            </div>
                            <div class="input-group mt-2">
                                <input type="number" name="lama_peminjaman" class="form-control lama_peminjaman" style="display: none;" placeholder="Ex: 14" min="1">
                                <div class="input-group-append">
                                    <span class="input-group-text">Hari</span>
                                </div>
                            </div>
                            <div class="invalid-feedback">
                                Masukkan Lama Peminjaman
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Lama Perpanjangan</label>
                            <select name="lama_perpanjangan_status" class="form-control lama_perpanjangan_status" required="">
                                <option value="">Pilih Status</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Lama Perpanjangan
                            </div>
                            <div class="input-group mt-2">
                                <input type="number" name="lama_perpanjangan" class="form-control lama_perpanjangan" style="display: none;" placeholder="Ex: 14" min="1">
                                <div class="input-group-append">
                                    <span class="input-group-text">Hari</span>
                                </div>
                            </div>
                            <div class="invalid-feedback">
                                Masukkan Lama Perpanjangan
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Batas Perpanjangan</label>
                            <select name="batas_perpanjangan_status" class="form-control batas_perpanjangan_status" required="">
                                <option value="">Pilih Status</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Batas Perpanjangan
                            </div>
                            <input type="number" name="batas_perpanjangan" class="form-control batas_perpanjangan mt-2" style="display: none;" placeholder="Ex: 1" min="1">
                            <div class="invalid-feedback">
                                Masukkan Batas Perpanjangan
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Denda Telat</label>
                            <select name="denda_telat_status" class="form-control denda_telat_status" required="">
                                <option value="">Pilih Status</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Denda Telat
                            </div>
                            <input type="number" name="denda_telat" class="form-control denda_telat mt-2" style="display: none;" placeholder="Ex: 5000" min="0">
                            <div class="invalid-feedback">
                                Masukkan Denda Telat
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Perhitungan Denda</label>
                            <select name="perhitungan_denda" class="form-control" required="">
                                <option value="">Pilih Perhitungan Denda</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="per hari">Per Hari</option>
                                <option value="per minggu">Per Minggu</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Perhitungan Denda
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Syarat Peminjaman</label>
                            <textarea name="syarat_peminjaman" class="form-control" required=""
                                      placeholder="Ex: Siswa harus memiliki kartu perpustakaan aktif"></textarea>
                            <div class="invalid-feedback">
                                Masukkan Syarat Peminjaman
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Syarat Perpanjangan</label>
                            <textarea name="syarat_perpanjangan" class="form-control" required=""
                                      placeholder="Ex: Buku harus dibawa untuk diperpanjang"></textarea>
                            <div class="invalid-feedback">
                                Masukkan Syarat Perpanjangan
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Syarat Pengembalian</label>
                            <textarea name="syarat_pengembalian" class="form-control" required=""
                                      placeholder="Ex: Buku harus dikembalikan dalam kondisi baik"></textarea>
                            <div class="invalid-feedback">
                                Masukkan Syarat Pengembalian
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Sanksi Kerusakan</label>
                            <textarea name="sanksi_kerusakan" class="form-control" required=""
                                      placeholder="Ex: Ganti rugi sesuai harga buku atau denda Rp 50.000"></textarea>
                            <div class="invalid-feedback">
                                Masukkan Sanksi Kerusakan
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModal">Update Setting Peminjaman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/peminjaman/settings/update') }}" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Batas Jumlah Buku</label>
                            <select name="batas_jumlah_buku_status" id="batas_jumlah_buku_status" class="form-control batas_jumlah_buku_status" required="">
                                <option value="">Pilih Status</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Batas Jumlah Buku
                            </div>
                            <input type="number" name="batas_jumlah_buku" id="batas_jumlah_buku" class="form-control batas_jumlah_buku mt-2" style="display: none;" placeholder="Ex: 2" min="1">
                            <div class="invalid-feedback">
                                Masukkan Jumlah Buku
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Lama Peminjaman</label>
                            <select name="lama_peminjaman_status" id="lama_peminjaman_status" class="form-control lama_peminjaman_status" required="">
                                <option value="">Pilih Status</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Lama Peminjaman
                            </div>
                            <div class="input-group mt-2">
                                <input type="number"Like name="lama_peminjaman" id="lama_peminjaman" class="form-control lama_peminjaman" style="display: none;" placeholder="Ex: 14" min="1">
                                <div class="input-group-append">
                                    <span class="input-group-text">Hari</span>
                                </div>
                            </div>
                            <div class="invalid-feedback">
                                Masukkan Lama Peminjaman
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Lama Perpanjangan</label>
                            <select name="lama_perpanjangan_status" id="lama_perpanjangan_status" class="form-control lama_perpanjangan_status" required="">
                                <option value="">Pilih Status</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Lama Perpanjangan
                            </div>
                            <div class="input-group mt-2">
                                <input type="number" name="lama_perpanjangan" id="lama_perpanjangan" class="form-control lama_perpanjangan" style="display: none;" placeholder="Ex: 14" min="1">
                                <div class="input-group-append">
                                    <span class="input-group-text">Hari</span>
                                </div>
                            </div>
                            <div class="invalid-feedback">
                                Masukkan Lama Perpanjangan
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Batas Perpanjangan</label>
                            <select name="batas_perpanjangan_status" id="batas_perpanjangan_status" class="form-control batas_perpanjangan_status" required="">
                                <option value="">Pilih Status</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Batas Perpanjangan
                            </div>
                            <input type="number" name="batas_perpanjangan" id="batas_perpanjangan" class="form-control batas_perpanjangan mt-2" style="display: none;" placeholder="Ex: 1" min="1">
                            <div class="invalid-feedback">
                                Masukkan Batas Perpanjangan
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Denda Telat</label>
                            <select name="denda_telat_status" id="denda_telat_status" class="form-control denda_telat_status" required="">
                                <option value="">Pilih Status</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="aktif">Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Denda Telat
                            </div>
                            <input type="number" name="denda_telat" id="denda_telat" class="form-control denda_telat mt-2" style="display: none;" placeholder="Ex: 5000" min="0">
                            <div class="invalid-feedback">
                                Masukkan Denda Telat
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Perhitungan Denda</label>
                            <select name="perhitungan_denda" id="perhitungan_denda" class="form-control" required="">
                                <option value="">Pilih Perhitungan Denda</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="per hari">Per Hari</option>
                                <option value="per minggu">Per Minggu</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Perhitungan Denda
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Syarat Peminjaman</label>
                            <textarea name="syarat_peminjaman" id="syarat_peminjaman" class="form-control" required=""
                                      placeholder="Ex: Siswa harus memiliki kartu perpustakaan aktif"></textarea>
                            <div class="invalid-feedback">
                                Masukkan Syarat Peminjaman
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Syarat Perpanjangan</label>
                            <textarea name="syarat_perpanjangan" id="syarat_perpanjangan" class="form-control" required=""
                                      placeholder="Ex: Buku harus dibawa untuk diperpanjang"></textarea>
                            <div class="invalid-feedback">
                                Masukkan Syarat Perpanjangan
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Syarat Pengembalian</label>
                            <textarea name="syarat_pengembalian" id="syarat_pengembalian" class="form-control" required=""
                                      placeholder="Ex: Buku harus dikembalikan dalam kondisi baik"></textarea>
                            <div class="invalid-feedback">
                                Masukkan Syarat Pengembalian
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Sanksi Kerusakan</label>
                            <textarea name="sanksi_kerusakan" id="sanksi_kerusakan" class="form-control" required=""
                                      placeholder="Ex: Ganti rugi sesuai harga buku atau denda Rp 50.000"></textarea>
                            <div class="invalid-feedback">
                                Masukkan Sanksi Kerusakan
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function toggleNumberInput(statusSelector, inputSelector) {
                $(statusSelector).on('change', function() {
                    if ($(this).val() === 'aktif') {
                        $(inputSelector).show().prop('required', true);
                    } else {
                        $(inputSelector).hide().prop('required', false).val('');
                    }
                });
            }

            toggleNumberInput('.batas_jumlah_buku_status', '.batas_jumlah_buku');
            toggleNumberInput('.lama_peminjaman_status', '.lama_peminjaman');
            toggleNumberInput('.lama_perpanjangan_status', '.lama_perpanjangan');
            toggleNumberInput('.batas_perpanjangan_status', '.batas_perpanjangan');
            toggleNumberInput('.denda_telat_status', '.denda_telat');

            $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/peminjaman/settings/all') }}",
                    type: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'batas_jumlah_buku', name: 'batas_jumlah_buku' },
                    { data: 'lama_peminjaman', name: 'lama_peminjaman' },
                    { data: 'lama_perpanjangan', name: 'lama_perpanjangan' },
                    { data: 'batas_perpanjangan', name: 'batas_perpanjangan' },
                    { data: 'denda_telat', name: 'denda_telat' },
                    { data: 'perhitungan_denda', name: 'perhitungan_denda' },
                    { data: 'action', name: 'action' }
                ]
            });

            $('.table').on('click', '.edit[data-id]', function(e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        id: $(this).data('id'),
                        _token: "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ url('admin/peminjaman/settings/get') }}",
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

                        // Batas Jumlah Buku
                        $('#batas_jumlah_buku_status').val(data.batas_jumlah_buku === 'non aktif' ? 'non aktif' : 'aktif');
                        $('#batas_jumlah_buku').val(data.batas_jumlah_buku === 'non aktif' ? '' : data.batas_jumlah_buku).toggle(data.batas_jumlah_buku !== 'non aktif');

                        // Lama Peminjaman
                        $('#lama_peminjaman_status').val(data.lama_peminjaman === 'non aktif' ? 'non aktif' : 'aktif');
                        $('#lama_peminjaman').val(data.lama_peminjaman === 'non aktif' ? '' : data.lama_peminjaman).toggle(data.lama_peminjaman !== 'non aktif');

                        // Lama Perpanjangan
                        $('#lama_perpanjangan_status').val(data.lama_perpanjangan === 'non aktif' ? 'non aktif' : 'aktif');
                        $('#lama_perpanjangan').val(data.lama_perpanjangan === 'non aktif' ? '' : data.lama_perpanjangan).toggle(data.lama_perpanjangan !== 'non aktif');

                        // Batas Perpanjangan
                        $('#batas_perpanjangan_status').val(data.batas_perpanjangan === 'non aktif' ? 'non aktif' : 'aktif');
                        $('#batas_perpanjangan').val(data.batas_perpanjangan === 'non aktif' ? '' : data.batas_perpanjangan).toggle(data.batas_perpanjangan !== 'non aktif');

                        // Denda Telat
                        $('#denda_telat_status').val(data.denda_telat === 'non aktif' ? 'non aktif' : 'aktif');
                        $('#denda_telat').val(data.denda_telat === 'non aktif' ? '' : data.denda_telat).toggle(data.denda_telat !== 'non aktif');

                        // Perhitungan Denda
                        $('#perhitungan_denda').val(data.perhitungan_denda);

                        // Text fields
                        $('#syarat_peminjaman').val(data.syarat_peminjaman);
                        $('#syarat_perpanjangan').val(data.syarat_perpanjangan);
                        $('#syarat_pengembalian').val(data.syarat_pengembalian);
                        $('#sanksi_kerusakan').val(data.sanksi_kerusakan);

                        $('#updateModal').modal('show');
                    },
                    error: function(err) {
                        alert('Error: ' + err.responseText);
                        console.log(err);
                    }
                });
            });

            $('.table').on('click', '.hapus[data-id]', function(e) {
                e.preventDefault();
                swal({
                    title: "Hapus Setting Peminjaman?",
                    text: "Setting Peminjaman akan dihapus, dan mungkin akan berpengaruh pada data yang lain.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            data: {
                                id: $(this).data('id'),
                                _token: "{{ csrf_token() }}"
                            },
                            type: 'DELETE',
                            url: "{{ url('admin/peminjaman/settings') }}",
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
                                swal(data.message)
                                    .then((result) => {
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
    </script>
@endsection