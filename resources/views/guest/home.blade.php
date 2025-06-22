@extends('guest')
@section('title', 'Home')
@section('meta_description', 'Aplikasi perpustakaan digital parepare')
@section('meta_keywords', 'buku digital, perpustakaan online, parepare, koleksi buku')
@section('content')

    @if(session('success'))
        <div class="toast pwa-install-alert shadow bg-white" id="installWrap" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000" data-bs-autohide="true">
            <div class="toast-body">
                <div class="content d-flex align-items-center mb-2">
                <img src="{{ asset('assets/img/kemenag.png') }}" alt="">
                <h6 class="mb-0">Selamat Datang</h6>
                <button class="btn-close ms-auto" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <span class="mb-2 d-block">{{ session('success') }}</span>
                <a href="/buku" class="btn btn-sm btn-primary" >Explore Perpustakaan</a>
            </div>
        </div>
    @endif

    <!-- Search Form-->
    <div class="container">
        <div class="search-form pt-3 rtl-flex-d-row-r">
            <form id="searchForm" action="/buku" method="GET">
                <input class="form-control" value="{{ request('q') }}" type="search" placeholder="Search" name="q">
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
    </div>


    <!-- Hero banner -->
    <div class="hero-wrapper">
        <div class="container">
            <div class="pt-3">
                <!-- Hero Slides-->
                <div class="hero-slides owl-carousel">
                    @forelse ($banner as $item)
                        <div class="single-hero-slide" style="background-image: url('{{ asset($item->foto) }}')">
                            <div class="slide-content h-100 d-flex align-items-center">
                                <div class="slide-text">
                                    <p class="text-white" data-animation="fadeInUp" data-delay="400ms"
                                        data-duration="1000ms">{{ $item->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="single-hero-slide"
                            style="background-image: url('{{ asset('mobile/dist/img/bg-img/1.jpg') }}')">
                            <div class="slide-content h-100 d-flex align-items-center">
                                <div class="slide-text">

                                    <p class="text-white" data-animation="fadeInUp" data-delay="200ms"
                                        data-duration="900ms">
                                        Hallo, Saya Kuli IT Tecno</p>
                                </div>
                            </div>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

    <!--  Catagories -->
    <div class="product-catagories-wrapper py-3">
        <div class="container">
            <div class="section-heading d-flex align-items-center justify-content-between dir-rtl">
                <h6>Kategori Buku</h6><a class="btn btn-sm btn-light" href="/kategori">View all<i
                        class="ms-1 ti ti-arrow-right"></i></a>
            </div>
            <div class="row g-2 rtl-flex-d-row-r">
                @forelse ($kategori as $item)
                    <div class="col-3">
                        <div class="card catagory-card">
                            <div class="card-body px-2">
                                <a href="/kategori/{{ $item->id }}">
                                    <img src="{{ asset($item->image ?? 'mobile/dist/img/core-img/price-tag.png') }}"
                                        alt="">
                                    <span>
                                        @php
                                            $words = Str::of($item->nama_kategori)->explode(' ');
                                        @endphp
                                        {{ $words->first() }}@if ($words->count() > 1)
                                            ..
                                        @endif
                                    </span>
                                </a>
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
    </div>


    <!-- terbaru -->
    <div class="flash-sale-wrapper">
        <div class="container">
            <div class="section-heading d-flex align-items-center justify-content-between dir-rtl">
                <h6>Buku Terbaru</h6><a class="btn btn-sm btn-light" href="/buku?kategori=terbaru">View all<i
                        class="ms-1 ti ti-arrow-right"></i></a>
            </div>
            <div class="row g-1 align-items-center rtl-flex-d-row-r mb-2">
                <div class="col-12">
                    <div class="product-catagories owl-carousel catagory-slides">
                        <a class="shadow-sm category-filter" href="#" data-category="all">Semua</a>
                        @forelse ($kategori as $item)
                            <a class="shadow-sm category-filter" href="#{{ $item->nama_kategori }}"
                                data-category="{{ $item->id }}">
                                <img src="{{ asset($item->image ?? 'mobile/dist/img/core-img/price-tag.png') }}"
                                    alt="">{{ $item->nama_kategori }}</a>
                        @empty
                            <a class="shadow-sm" href="#">
                                <img src="img/product/5.png" alt="">Furniture</a>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="flash-sale-slide owl-carousel">
                @forelse ($buku_terbaru as $item)
                    <div class="card flash-sale-card book-item" data-category-id="{{ $item->id_kategori }}">
                        <div class="card-body">
                            <a href="/buku/{{ $item->singkatan_buku }}">
                                <img src="{{ asset($item->cover_buku ?? 'assets/img/default-book.webp') }}"
                                    alt=""><span class="product-title">{{ $item->judul_buku }}</span>
                            </a>
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
    </div>

    <!-- Dark Mode -->
    <div class="container">
        <div class="dark-mode-wrapper mt-3 bg-img p-4 p-lg-5">
            <p class="text-white">Nikmati pengalaman membacamu dengan mode gelap</p>
            <div class="form-check form-switch mb-0">
                <label class="form-check-label text-white h6 mb-0" for="darkSwitch">Beralih Ke Dark Mode</label>
                <input class="form-check-input" id="darkSwitch" type="checkbox" role="switch">
            </div>
        </div>
    </div>

    <!-- Top views -->
    <div class="top-products-area py-3">
        <div class="container">
            <div class="section-heading d-flex align-items-center justify-content-between dir-rtl">
                <h6>Buku Paling Banyak Dibaca</h6>
                <a class="btn btn-sm btn-light" href="/buku?kategori=top_view">View all<i
                        class="ms-1 ti ti-arrow-right"></i></a>
            </div>
            <div class="row g-1 align-items-center rtl-flex-d-row-r mb-2">
                <div class="col-12">
                    <div class="product-catagories owl-carousel catagory-slides">
                        <a class="shadow-sm category-filter" href="#" data-category="all">Semua</a>
                        @forelse ($kategori as $item)
                            <a class="shadow-sm category-filter" href="#" data-category="{{ $item->id }}">
                                <img src="{{ asset($item->image ?? 'mobile/dist/img/core-img/price-tag.png') }}"
                                    alt="{{ $item->nama_kategori }}">
                                {{ $item->nama_kategori }}
                            </a>
                        @empty
                            <a class="shadow-sm category-filter" href="#" data-category="furniture">
                                <img src="{{ asset('img/product/5.png') }}" alt="Furniture">Furniture
                            </a>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="row g-2" id="book-list">
                @forelse ($top_view as $item)
                    <div class="col-6 col-md-3 book-item" data-category-id="{{ $item->id_kategori }}">
                        <div class="card product-card">
                            <div class="card-body">
                                <a class="product-thumbnail d-block"
                                    href="{{ route('buku.show', $item->singkatan_buku) }}">
                                    <img class="mb-2"
                                        src="{{ asset($item->cover_buku ?? 'assets/img/default-book.webp') }}"
                                        alt="{{ $item->judul_buku }}">
                                </a>
                                <a class="product-title" style="font-size: 13px;"
                                    href="{{ route('buku.show', $item->singkatan_buku) }}">{{ $item->judul_buku }}</a>
                                <p style="font-size: 13px;" class="sale-price"><i class="ms-1 ti ti-eye"></i>
                                    {{ $item->view_count }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="discount-coupon-card p-4 p-lg-5 dir-rtl">
                        <div class="d-flex align-items-center">
                            <div class="discountIcon">
                                <img class="w-100" src="{{ asset('assets/img/404.png') }}" alt="No books found">
                            </div>
                            <div class="text-content">
                                <h5 class="text-white mb-2">Uppss..</h5>
                                <p class="text-white mb-0">Data yang anda cari tidak ada atau belum ada kontennya.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Banner -->
    <div class="container">
        <div class="cta-text dir-rtl p-4 p-lg-5">
            <div class="row">
                <div class="col-9">
                    <h5 class="text-white">Buku membuka wawasan dan memperluas pengetahuan.</h5>
                </div>
            </div><img src="{{ asset('assets/img/book.png') }}" alt="">
        </div>
    </div>

    <!-- Weekly Best Sellers-->
    <div class="weekly-best-seller-area py-3">
        <div class="container">
            <div class="section-heading d-flex align-items-center justify-content-between dir-rtl">
                <h6>Publikasi Terbaru</h6><a class="btn btn-sm btn-light" href="/artikel?q=terbaru">View
                    all<i class="ms-1 ti ti-arrow-right"></i></a>
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
    </div>

    @if ($top_membaca->count() >= 4)
        <div class="pb-3">
            <div class="container">
                <div class="section-heading d-flex align-items-center justify-content-between dir-rtl">
                    <h6>5 Top Pembaca</h6>
                </div>
                <div class="collection-slide owl-carousel">
                    @foreach ($top_membaca as $item)
                        <div class="card collection-card">
                            <a><img src="{{ asset($item->siswa->foto ?? 'assets/img/avatar.png') }}" alt=""></a>
                            <div class="collection-title">
                                <span>{{ $item->siswa->nama_siswa }}</span>
                                <span class="badge bg-danger">{{ $item->total_books }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Top Kontributor Wrapper -->
    @if ($top_kontributor->count() != 0)
        <div class="featured-products-wrapper py-3">
            <div class="container">
                <div class="section-heading d-flex align-items-center justify-content-between dir-rtl">
                    <h6>Top 5 Kontributor</h6>
                </div>
                <div class="row g-2">
                    @foreach ($top_kontributor as $item)
                        <div class="col-4">
                            <div class="card featured-product-card">
                                <div class="card-body">
                                    <span class="badge badge-warning custom-badge"><i
                                            class="ti ti-star-filled"></i></span>
                                    <div class="product-thumbnail-side">
                                        <a class="product-thumbnail d-block" href="/kontributor/{{ $item->nik }}">
                                            <img src="{{ asset($item->foto) }}" alt="{{ $item->nama }}">
                                        </a>
                                    </div>
                                    <div class="product-description">
                                        <a class="product-title d-block text-center"
                                            href="/kontributor/{{ $item->nik }}">
                                            {{ $item->nama }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!--  Catagories -->
    <div class="product-catagories-wrapper py-3">
        <div class="container">
            <div class="section-heading d-flex align-items-center justify-content-between dir-rtl">
                <h6>Link Terkait</h6>
            </div>
            <div class="row g-2 rtl-flex-d-row-r">
                @forelse ($link as $item)
                    <div class="col-3">
                        <div class="card catagory-card">
                            <div class="card-body">
                                <a href="{{ url($item->link) }}">
                                    <img src="{{ asset($item->logo ?? 'mobile/dist/img/core-img/price-tag.png') }}"
                                        alt="">
                                </a>
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
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryLinks = document.querySelectorAll('.category-filter');
            const bookItems = document.querySelectorAll('.book-item');

            categoryLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedCategory = this.getAttribute('data-category');

                    bookItems.forEach(item => {
                        const itemCategory = item.getAttribute('data-category-id');
                        if (selectedCategory === 'all' || itemCategory ===
                            selectedCategory) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
@endsection
