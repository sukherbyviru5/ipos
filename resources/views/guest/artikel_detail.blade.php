@extends('guest')
@section('title', $artikel->judul)
@section('meta_description', $artikel->judul)
@section('meta_keywords', 'buku digital, perpustakaan online, parepare, koleksi buku', $artikel->judul)
@section('content')
    <div class="page-content-wrapper">
        <div class="blog-details-post-thumbnail" style="background-image: url('{{ asset('assets/img/bg-book.jpg') }}')"></div>
        <div class="product-description pb-3">
            <div class="product-title-meta-data bg-white mb-3 py-3 dir-rtl">
                <div class="container">
                    <h5 class="post-title">{{ $artikel->judul }}</h5>
                    <div class="post-meta-data d-flex align-items-center justify-content-between">
                        <a class="d-flex align-items-center" href="{{ route('kontributor.show', $creator->nik) }}">
                            <img src="{{ asset($creator->foto) }}" alt="{{ $creator->nama }}" style="width: 30px; height: 30px; border-radius: 50%;">
                            {{ $creator->nama }}
                        </a>
                        <span class="d-flex align-items-center">
                            <svg class="bi bi-clock me-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"></path>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($artikel->created_at)->locale('id')->diffForHumans(null, null, false, 1) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="post-content bg-white py-3 mb-3 dir-rtl">
                <div class="container">
                    <iframe src="{{ asset($artikel->file) }}" frameborder="0" style="width:100%; height:600px;"></iframe>
                </div>
            </div>
            <!-- All Comments-->
            <div class="rating-and-review-wrapper bg-white py-3 mb-3 dir-rtl">
                <div class="container">
                    <h6>Terkait</h6>
                    <div class="rating-review-content">
                        <ul class="ps-0">
                            @forelse ($artikels as $item)
                                <li class="single-user-review d-flex">
                                    <div class="user-thumbnail mt-0">
                                        <img style="border-radius: 2px !important" src="{{ asset('assets/img/news.jpg') }}" alt="">
                                    </div>
                                    <div class="rating-comment">
                                        <a href="{{ route('artikel.show', $item->slug) }}" class="comment mb-0">{{ $item->judul }}</a>
                                        <span class="name-date">
                                            <svg class="bi bi-clock me-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"></path>
                                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->diffForHumans(null, null, false, 1) }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <div class="container">
                                    <div class="cta-text dir-rtl p-4 p-lg-5">
                                        <div class="row">
                                            <div class="col-9">
                                                <h5 class="text-white">Buku membuka wawasan dan memperluas pengetahuan.</h5>
                                            </div>
                                        </div>
                                        <img src="{{ asset('assets/img/book.png') }}" alt="" />
                                    </div>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection