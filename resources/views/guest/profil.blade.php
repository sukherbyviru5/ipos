@extends('guest')
@section('title', 'Profil')
@section('meta_description',  "$profil->nama_madrasah yang beralamat di $profil->alamat_madrasah")
@section('meta_keywords', 'buku digital, perpustakaan online, parepare, koleksi buku')
@section('content')
    <!-- Google Maps-->
    <div class="google-maps-wrap">
        {!! $profil->embed_maps  !!}
    </div>
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <div class="rtl-text-right">
                    <h5 class="mb-1">Profil</h5>
                    <p class="mb-4">{{ $profil->nama_madrasah }} yang beralamat di {{ $profil->alamat_madrasah }}</p>
                </div>
                <div class="contact-info">
                    <p><i class="ti ti-mail me-2"></i><strong>Email:</strong> <a href="mailto:{{ $profil->email_madrasah }}">{{ $profil->email_madrasah }}</a></p>
                    <p><i class="ti ti-phone me-2"></i><strong>No. Telepon:</strong> {{ $profil->no_telpon ?? 'Tidak tersedia' }}</p>
                    <p><i class="ti ti-brand-facebook me-2"></i><strong>Facebook:</strong> <a href="{{ $profil->facebook ?? 'https://facebook.com/' }}" target="_blank">Lihat Facebook</a></p>
                    <p><i class="ti ti-brand-instagram me-2"></i><strong>Instagram:</strong> <a href="{{ $profil->instagram ?? 'https://www.instagram.com/' }}" target="_blank">Lihat Instagram</a></p>
                    <p><i class="ti ti-brand-youtube me-2"></i><strong>YouTube:</strong> <a href="{{ $profil->youtube ?? 'https://www.youtube.com/' }}" target="_blank">Lihat YouTube</a></p>
                </div>
            </div>
        </div>
        <div class="pb-3"></div>
    </div>
@endsection