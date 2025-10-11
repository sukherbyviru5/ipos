@extends('master')
@section('title', 'Data Voucher')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Voucher</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Data Voucher</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Data Voucher</h2>
                <p class="section-lead">Berikut adalah Data Voucher.</p>
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
                        <h4>Data Seluruh Voucher</h4>
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
                        <table class="table table-striped mt-5" id="voucherTable">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Nama</th>
                                    <th>Produk</th>
                                    <th>Kode</th>
                                    <th>Persentase</th>
                                    <th>Status</th>
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
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModal">Tambah Voucher</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="/admin/manage-master/voucher" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" placeholder="Masukkan Nama Voucher" class="form-control" name="name" required="">
                            <div class="invalid-feedback">
                                Masukkan Nama Voucher
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" placeholder="Masukkan Kode Voucher" class="form-control" name="code" required="">
                            <div class="invalid-feedback">
                                Masukkan Kode Voucher
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Persentase (%)</label>
                            <input type="number" placeholder="Masukkan Persentase Diskon" class="form-control" name="percent" required="" min="0" max="100">
                            <div class="invalid-feedback">
                                Masukkan Persentase Diskon (0-100)
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required="">
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="NON ACTIVE">NON ACTIVE</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Voucher
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Products</label>
                            <select class="form-control select2" name="products[]" multiple="multiple" >
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name ?? $product->title }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Pilih Products
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

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModal">Update Voucher</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="/admin/manage-master/voucher/update" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" placeholder="Masukkan Nama Voucher" class="form-control" name="name" required="" id="name">
                            <div class="invalid-feedback">
                                Masukkan Nama Voucher
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" placeholder="Masukkan Kode Voucher" class="form-control" name="code" required="" id="code">
                            <div class="invalid-feedback">
                                Masukkan Kode Voucher
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Persentase (%)</label>
                            <input type="number" placeholder="Masukkan Persentase Diskon" class="form-control" name="percent" required="" id="percent" min="0" max="100">
                            <div class="invalid-feedback">
                                Masukkan Persentase Diskon (0-100)
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required="" id="status">
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="NON ACTIVE">NON ACTIVE</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Status Voucher
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Product</label>
                            <select class="form-control select2" name="product_id"  id="product_id">
                                <option value="">-- Pilih Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name ?? $product->title }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Pilih Product
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
            // Inisialisasi Select2 untuk multiple select di add modal dan single di update
            $('.select2').select2({
                placeholder: "Pilih Product",
                allowClear: true
            });

            $('#voucherTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/manage-master/voucher/all') }}",
                    type: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'product_name', name: 'product_name' },
                    { data: 'code', name: 'code' },
                    { data: 'percent', name: 'percent' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

          
            $('#voucherTable').on('click', '.edit[data-id]', function(e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        'id': $(this).data('id'),
                        '_token': "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ url('admin/manage-master/voucher/get') }}",
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
                        $('#code').val(data.code);
                        $('#percent').val(data.percent);
                        $('#status').val(data.status);
                        // Set selected product untuk single select
                        $('#product_id').val(data.product_id || '').trigger('change');
                        $('#updateModal').modal('show');
                    },
                    error: function(err) {
                        alert('Error: ' + err.responseText);
                        console.log(err);
                    }
                });
            });

            $('#voucherTable').on('click', '.hapus[data-id]', function(e) {
                e.preventDefault();
                swal({
                    title: "Hapus Voucher?",
                    text: "Data Voucher ini akan dihapus secara permanen!",
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
                            url: "{{ url('admin/manage-master/voucher') }}",
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
    </script>
@endsection