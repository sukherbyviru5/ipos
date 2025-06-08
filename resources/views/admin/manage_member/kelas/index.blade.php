@extends('master')
@section('title', 'Data Kelas - ')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Kelas</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">Data Kelas</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Data Kelas</h2>
                <p class="section-lead">Berikut adalah Data Kelas yang telah dibuat.</p>
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
                        <h4>Data Kelas</h4>
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
                                    <th>Tingkat Kelas</th>
                                    <th>Jurusan</th>
                                    <th>Urusan Kelas</th>
                                    <th>Kelompok</th>
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
                    <h5 class="modal-title" id="addModal">Tambah Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/manage-member/kelas') }}" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tingkat Kelas</label>
                            <select name="tingkat_kelas" class="form-control" required="">
                                <option value="">- PILIH TINGKAT KELAS -</option>
                                @foreach ($tingkat_kelas as $t)
                                    <option value="{{ $t }}">- {{ strtoupper($t) }} -</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Pilih Tingkat Kelas
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Pilih Jurusan</label>
                            <select name="jurusan" class="form-control" required="">
                                <option value="">- PILIH JURUSAN -</option>
                                @foreach ($jurusan as $j)
                                    <option value="{{ $j }}">- {{ strtoupper($j) }} -</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Pilih Jurusan
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Kelompok Kelas <small class="text-success">*Optional</small></label>
                            <select name="kelompok" class="form-control kelompok">
                                <option value="">- PILIH KELOMPOK KELAS -</option>
                                @foreach (range('A', 'J') as $kelompok)
                                    <option value="{{ $kelompok }}">- {{ $kelompok }} -</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>Urusan Kelas</label>
                            <select name="urusan_kelas" class="form-control urusan_kelas" required="">
                                <option value="">- PILIH URUSAN KELAS -</option>
                                @foreach (range('A', 'Z') as $urusan)
                                    <option value="{{ $urusan }}">- {{ $urusan }} -</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Pilih Urusan Kelas Dahulu
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
                    <h5 class="modal-title" id="updateModal">Update Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('admin/manage-member/kelas/update') }}" method="POST" class="needs-validation" novalidate="">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tingkat Kelas</label>
                            <select name="tingkat_kelas" id="tingkat_kelas" class="form-control" required="">
                                <option value="">- PILIH TINGKAT KELAS -</option>
                                @foreach ($tingkat_kelas as $t)
                                    <option value="{{ $t }}">- {{ strtoupper($t) }} -</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Pilih Tingkat Kelas
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Pilih Jurusan</label>
                            <select name="jurusan" id="jurusan" class="form-control" required="">
                                <option value="">- PILIH JURUSAN -</option>
                                @foreach ($jurusan as $j)
                                    <option value="{{ $j }}">- {{ strtoupper($j) }} -</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Pilih Jurusan
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Kelompok Kelas <small class="text-success">*Optional</small></label>
                            <select name="kelompok" id="kelompok" class="form-control kelompok">
                                <option value="">- PILIH KELOMPOK KELAS -</option>
                                @foreach (range('A', 'J') as $kelompok)
                                    <option value="{{ $kelompok }}">- {{ $kelompok }} -</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>Urusan Kelas</label>
                            <select name="urusan_kelas" id="urusan_kelas" class="form-control urusan_kelas" required="">
                                <option value="">- PILIH URUSAN KELAS -</option>
                                @foreach (range('A', 'Z') as $urusan)
                                    <option value="{{ $urusan }}">- {{ $urusan }} -</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Pilih Urusan Kelas Dahulu
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
            $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/manage-member/kelas/all') }}",
                    type: "GET"
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'tingkat_kelas',
                        name: 'tingkat_kelas'
                    },
                    {
                        data: 'jurusan',
                        name: 'jurusan'
                    },
                    {
                        data: 'urusan_kelas',
                        name: 'urusan_kelas'
                    },
                    {
                        data: 'kelompok',
                        name: 'kelompok',
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
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
                    url: "{{ url('admin/manage-member/kelas/get') }}",
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
                        $('#tingkat_kelas').val(data.tingkat_kelas);
                        $('#jurusan').val(data.jurusan);
                        $('#kelompok').val(data.kelompok);
                        $('#urusan_kelas').val(data.urusan_kelas);
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
                    title: "Hapus Kelas ?",
                    text: "Kelas akan dihapus, dan mungkin akan berpengaruh pada data yang lain.",
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
                            url: "{{ url('admin/manage-member/kelas') }}",
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