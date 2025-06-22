@extends('master')
@section('title', '')
@section('content')
    <section class="section">
        <div class="d-flex flex-wrap align-items-stretch">
            <div class="col-lg-4 col-md-12 col-12 order-lg-1 min-vh-100 order-2 bg-white">
                <div class="p-4 m-3">
                    <img src="{{ asset('assets/img/kemenag.png') }}" alt="logo" width="100"
                        class="mb-5 mt-2">
                    <h4 class="text-dark font-weight-normal">Selamat Datang di <span class="font-weight-bold">Aplikasi
                            Perpustakaan Digital</span></h4>
                    <p class="text-muted">Sebelum login pastikan anda telah punya akun.</p>
                    @error('message')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{$message}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @enderror
                    <form method="POST" action="{{ url('login') }}" class="needs-validation" novalidate="">
                        @csrf
                        <div class="form-group">
                            <label for="nip_nik_nisn">NISN/NIP/NIK</label>
                            <input id="nip_nik_nisn" type="number" class="form-control" name="nip_nik_nisn" tabindex="1"
                                required autofocus value="{{ old('nip_nik_nisn') }}" placeholder="Masukkan NISN/NIP/NIK">
                            <div class="invalid-feedback">
                                Masukkan NISN/NIP/NIK anda
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="d-block">
                                <label for="password" class="control-label">Password</label>
                            </div>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control" name="password" tabindex="2"
                                required placeholder="Masukkan Password">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="icon_box">
                                        <i class="fa-sharp fa-solid fa-eye-slash lg mt-1" id="show_hide_password"></i>
                                    </span>
                                </div>
                                <div class="invalid-feedback">
                                    Masukkan Password anda
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <a href="/" class="btn btn-danger btn-lg btn-icon icon-right">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                                Masuk
                                <i class="fas fa-sign-in-alt"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-8 bg-white col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom d-none d-lg-block d-xl-block"
                data-background="{{ asset('assets/img/5834.jpg') }}">
                <div class="absolute-bottom-left index-2">
                    <div class="text-light p-5 pb-2">
                        <div class="mb-5 pb-3">
                            <h1 class="mb-2 display-4 font-weight-bold">Selamat Datang</h1>
                            <h5 class="font-weight-normal text-muted-transparent">Buku adalah jendela dunia, perpustakaan adalah pintunya. Mari kita buka bersama.</h5>
                        </div>
                       Develop By<a class="text-light bb" target="_blank" href="#">MAN Parepare</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $('#icon_box').click(function(){
            if ($('#password').attr('type') == 'text'){
                $('#password').attr('type', 'password');
                $('#show_hide_password').removeClass('fa-eye');
                $('#show_hide_password').addClass('fa-eye-slash');
            }else if($('#password').attr('type') == 'password'){
                $('#password').attr('type', 'text');
                $('#show_hide_password').removeClass('fa-eye-slash');
                $('#show_hide_password').addClass('fa-eye');
            }
        });
    </script>
@endsection
