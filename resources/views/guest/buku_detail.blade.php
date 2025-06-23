@extends('guest')
@section('title', $buku->singkatan_buku)
@section('meta_description', $buku->judul_buku)
@section('meta_keywords', 'buku digital, perpustakaan online, parepare, koleksi buku, ', $buku->judul_buku)
@section('content')
    <div class="page-content-wrapper">
        <div class="blog-details-post-thumbnail" style="background-image: url('{{ asset($buku->cover_buku) }}')"></div>
        <div class="product-description pb-3">
            <!-- Product Title & Meta Data-->
            <div class="product-title-meta-data bg-white mb-3 py-3 dir-rtl">
                <div class="container">
                    <h5 class="post-title">{{ $buku->judul_buku }}</h5>
                    <a class="post-catagory mb-3 d-block" href="#">
                        <i class="ti ti-eye"></i> {{ $buku->view_count }}x
                    </a>
                    <div class="post-meta-data d-flex align-items-center justify-content-between">
                        <a class="d-flex align-items-center" href="{{ route('kategori.show', $buku->kategori_buku->id) }}">
                            <img src="{{ asset($buku->kategori_buku->image) }}"
                                alt="">{{ $buku->kategori_buku->nama_kategori }}
                        </a>
                        <span class="d-flex align-items-center">
                            <svg class="bi bi-clock me-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z">
                                </path>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($buku->created_at)->locale('id')->diffForHumans(null, null, false, 1) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="post-content bg-white py-3 mb-3 dir-rtl">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-hover table-striped table-responsive">
                                @if ($buku->singkatan_buku)
                                    <tr>
                                        <td><code>Singkatan</code></td>
                                        <td style="font-size: 12px;">{{ $buku->singkatan_buku }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><code>Judul Buku</code></td>
                                    <td style="font-size: 12px;">{{ $buku->judul_buku }}</td>
                                </tr>
                                @if ($buku->penulis_buku)
                                    <tr>
                                        <td><code>Penulis</code></td>
                                        <td style="font-size: 12px;">{{ $buku->penulis_buku }}</td>
                                    </tr>
                                @endif
                                @if ($buku->penerbit_buku)
                                    <tr>
                                        <td><code>Penerbit</code></td>
                                        <td style="font-size: 12px;">{{ $buku->penerbit_buku }}</td>
                                    </tr>
                                @endif
                                @if ($buku->tahun_terbit)
                                    <tr>
                                        <td><code>Tahun Terbit</code></td>
                                        <td style="font-size: 12px;">{{ $buku->tahun_terbit }}</td>
                                    </tr>
                                @endif
                                @if ($buku->lokasi_lemari || $buku->lokasi_rak)
                                    <tr>
                                        <td><code>Lokasi Buku</code></td>
                                        <td style="font-size: 12px;">
                                            Lemari: {{ $buku->lokasi_lemari ?? '-' }}<br>
                                            Rak: {{ $buku->lokasi_rak ?? '-' }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><code>Ebook</code></td>
                                    <td style="font-size: 12px;">
                                        @if ($buku->ebook_tersedia)
                                            ✅ Tersedia
                                            @if ($buku->ebook_file)
                                                - <a href="{{ asset($buku->ebook_file) }}" target="_blank">Unduh</a>
                                            @endif
                                        @else
                                            ❌ Tidak tersedia
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <p>
                        {!! $buku->sinopsis !!}
                    </p>
                     @if ($buku->ebook_tersedia && $buku->ebook_file)
                        <iframe src="{{ asset($buku->ebook_file) }}" frameborder="0"
                            style="width:100%; height:600px;"></iframe>
                    @endif
                </div>
            </div>
            <!-- All Comments-->
            <div class="rating-and-review-wrapper bg-white py-3 mb-3 dir-rtl">
                <div class="container">
                    <h6>Rekomendasi Buku</h6>
                    <div class="rating-review-content">
                        <ul class="ps-0">
                            @forelse ($bukus as $item)
                                <li class="single-user-review d-flex">
                                    <div class="user-thumbnail mt-0">
                                        <img style="border-radius: 2px !important"
                                            src="{{ asset($item->cover_buku ?? 'assets/img/default-book.webp') }}"
                                            alt="">
                                    </div>
                                    <div class="rating-comment">
                                        <a href="{{ route('buku.show', $item->singkatan_buku) }}"
                                            class="comment mb-0">{{ $item->judul_buku }}
                                        </a>
                                        <span class="name-date">
                                            <svg class="bi bi-clock me-1" xmlns="http://www.w3.org/2000/svg" width="13"
                                                height="13" fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z">
                                                </path>
                                                <path
                                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z">
                                                </path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->diffForHumans(null, null, false, 1) }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <!-- Banner -->
                                <div class="container">
                                    <div class="cta-text dir-rtl p-4 p-lg-5">
                                        <div class="row">
                                            <div class="col-9">
                                                <h5 class="text-white">Buku membuka wawasan dan memperluas pengetahuan.</h5>
                                            </div>
                                        </div><img src="{{ asset('assets/img/book.png') }}" alt="" />
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