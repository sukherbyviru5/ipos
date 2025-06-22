@extends('guest')
@section('title',  $kategori->nama_kategori)
@section('content')
    <!-- Search Form-->
    <div class="container">
        <div class="search-form pt-3 rtl-flex-d-row-r">
            <form id="searchForm" action="" method="GET">
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

    <div class="container py-3">
        <div class="section-heading d-flex align-items-center justify-content-between dir-rtl">
            <h6>{{ $kategori->nama_kategori }}</h6>
        </div>
        <div class="row g-2">
            @forelse ($buku as $item)
                <div class="col-6 col-md-3">
                    <div class="card product-card">
                        <div class="card-body">
                            <a class="product-thumbnail d-block" href="{{ route('buku.show', $item->singkatan_buku) }}">
                                <img class="mb-2" src="{{ asset($item->cover_buku ?? 'assets/img/default-book.webp') }}"
                                    alt="">
                            </a>
                            <a class="product-title" style="font-size: 13px;"
                                href="{{ route('buku.show', $item->singkatan_buku) }}">{{ $item->judul_buku }}</a>
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
                            <p class="text-white mb-0">Tidak ada buku di kategori ini.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="pagination-wrapper rounded-full"
            style="display: flex; justify-content: center; margin-top: 20px; margin-bottom: 20px;">
            {{ $buku->links('pagination::simple-tailwind') }}
        </div>
    </div>

@endsection
