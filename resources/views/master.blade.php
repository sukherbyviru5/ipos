<!DOCTYPE html>
<html lang="en" class="notranslate">

<head>
    @include('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Assets File --}}
    <link rel="stylesheet" href="{{ asset('dist/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/mix/app.css') }}">
    <link rel="stylesheet" href="{{asset('dist/select2/css/select2.min.css')}}">

    <script src="{{ asset('assets/mix/app.js') }}"></script>
    <script src="{{asset('dist/select2/js/select2.min.js')}}"></script>
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
    {{-- End Assets File --}}
</head>
<body>
    <style>
        .custom-radio[type="radio"] {
            transform: scale(1.5); 
        }
        .custom-control-label {
            cursor: pointer;
        }
        .modal-backdrop {
            width: 0 !important;
        }
    </style>
    <div id="app">
        <div class="main-wrapper">
            @if (session()->get('is_admin'))
                @include('layout.sb_admin')
            @endif
            @yield('content')
            @if (session()->get('is_admin') || session()->get('is_guru'))
                @include('layout.footer')
            @endif
        </div>
    </div>
    {{-- Profile Modal --}}
    @if (session()->get('is_admin'))
        <div class="modal fade" id="updateModalProfie" tabindex="-1" aria-labelledby="updateModalProfie"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalProfie">Update Data Admin</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="#" method="POST" class="needs-validation" novalidate="" id="formUpdateProfile">
                        @csrf
                        <input type="hidden" name="id" value="{{ request()->session()->get('id') }}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nip/Nik Admin</label>
                                <input type="text" placeholder="NIP/NIK Admin" class="form-control"
                                    name="nip_nik_nisn" required=""
                                    value="{{ request()->session()->get('nip_nik_nisn') }}" id="">
                                <div class="invalid-feedback">
                                    Masukkan NIP/NIK Admin
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nama Admin</label>
                                <input type="text" placeholder="Masukkan Nama Admin" class="form-control"
                                    name="nama" required="" value="{{ request()->session()->get('nama') }}"
                                    id="">
                                <div class="invalid-feedback">
                                    Masukkan Nama Admin
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label>Password Baru</label>
                                <input type="text" placeholder="Masukkan Password Baru Admin" class="form-control"
                                    name="password" id="">
                                <div class="valid-feedback">
                                    Optional
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
            $('.edit-profile').click(function() {
                $('#updateModalProfie').modal('show');
            });
            $('#formUpdateProfile').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: $(this).serialize(),
                    type: 'POST',
                    url: "{{ url('admin/profile') }}",
                    beforeSend: function() {
                        $.LoadingOverlay("show", {
                            image: "",
                            fontawesome: "fa fa-cog fa-spin"
                        });
                    },
                    complete: function() {
                        $.LoadingOverlay("hide", {
                            image: "",
                            fontawesome: "fa fa-cog fa-spin"
                        });
                    },
                    success: function(data) {
                        swal(data.message)
                            .then((result) => {
                                window.location.href = "{{url('/')}}"
                            });
                    },
                    error: function(err) {
                        swal(err.responseJSON.message);
                    }
                });
            })
        </script>
    @endif
    
    <script>
    $('.summernote').summernote({
        placeholder: 'Jika anda copas soal dari MS word, klik CTRL + A lalu klik menu remove font style agar meringankan kinerja aplikasi. atau anda juga bisa menekan klik kanan lalu paste as plain text',
        tabsize: 2,
        height: 220,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear', 'fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video', 'math']],
          ['view', ['fullscreen', 'codeview']],
        ]
      });

    </script>
</body>

</html>
