@extends('guest')
@section('title', 'Kategori')
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

    <!--  Catagories -->
    <div class="product-catagories-wrapper py-3">
        <div class="container">
            <div class="section-heading d-flex align-items-center justify-content-between dir-rtl">
                <h6>Kategori Buku</h6>
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
@endsection
