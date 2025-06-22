@extends('guest')
@section('title', 'Publikasi')
@section('meta_description', 'Publikasi artikel yang ditulis oleh siswa dan guru')
@section('meta_keywords', 'buku digital, perpustakaan online, parepare, koleksi buku')
@section('content')
    <!-- Weekly Best Sellers-->
    <div class="weekly-best-seller-area py-3">
        <div class="container">
            <div class="section-heading d-flex align-items-center justify-content-between dir-rtl">
                <h6>Publikasi Artikel</h6>
            </div>
            <!-- Search Form-->
            <div class="search-form mb-2 rtl-flex-d-row-r">
                <form id="searchForm" action="/artikel" method="GET">
                    <input class="form-control" value="{{ request('q') }}" type="search" placeholder="Search Artikel"
                        name="q">
                    <button type="submit"><i class="ti ti-search"></i></button>
                </form>
                <div class="alternative-search-options">
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" id="altSearchOption"
                            onclick="document.getElementById('searchForm').submit();">
                            <i class="ti ti-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Banner -->
            <div class="">
                <div class="cta-text dir-rtl p-4 p-lg-5">
                    <div class="row">
                        <div class="col-9">
                            <h6 class="text-white">Buku membuka wawasan dan memperluas pengetahuan.</h6>
                        </div>
                    </div><img width="60" style="border-radius: 20px 0 0 0" src="{{ asset('assets/img/news.jpg') }}" alt="">
                </div>
            </div>

            <div class="row g-2 pt-2">
                @forelse ($artikel as $item)
                    <div class="col-12">
                        <div class="card horizontal-product-card">
                            <div class="d-flex align-items-center">
                                <div class="product-thumbnail-side">
                                    <a class="product-thumbnail d-block " href="/artikel/{{ $item->slug }}"><img
                                            src="{{ asset('assets/img/news.jpg') }}" alt=""></a>
                                </div>
                                <div class="product-description">
                                    <a class="product-title d-block"
                                        href="/artikel/{{ $item->slug }}">{{ $item->judul }}</a>
                                    <div class="product-rating">
                                        <i class="ti ti-calendar-event"></i>
                                        {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->diffForHumans(null, null, false, 1) }}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="discount-coupon-card p-4 p-lg-5 dir-rtl">
                        <div class="d-flex align-items-center">
                            <div class="discountIcon"><img class="w-100" src="{{ asset('assets/img/404.png') }}"
                                    alt=""></div>
                            <div class="text-content">
                                <h5 class="text-white mb-2">Uppss..</h5>
                                <p class="text-white mb-0">Data yang anda cari tidak ada atau belum ada kontennya.</p>
                            </div>
                        </div>
                    </div>
                @endforelse

            </div>
        </div>
        <div class="pagination-wrapper rounded-full"
            style="display: flex; justify-content: center; margin-top: 20px; margin-bottom: 20px;">
            {{ $artikel->links('pagination::simple-tailwind') }}
        </div>
    </div>


@endsection
