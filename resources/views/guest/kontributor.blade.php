@extends('guest')
@section('title', $kontributor->nama)
@section('content')
    <div class="page-content-wrapper">
        <div class="blog-details-post-thumbnail" style="background-image: url('{{ asset('assets/img/bg-book.jpg') }}')"></div>
        <!-- Contributor Profile Header -->
        <div class="product-description pb-3">
            <div class="product-title-meta-data bg-white mb-3 py-3 dir-rtl">
                <div class="container">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset($kontributor->foto) }}" alt="{{ $kontributor->nama }}"
                            style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                        <div>
                            <h5 class="post-title mb-1">{{ $kontributor->nama }}</h5>
                            <p class="mb-0">{{ $kontributor->type }} | NIK: {{ $kontributor->nik }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contributor Articles -->
            <div class="rating-and-review-wrapper bg-white py-3 mb-3 dir-rtl">
                <div class="container">
                    <h6>Artikel oleh {{ $kontributor->nama }}</h6>
                    <div class="rating-review-content">
                        <ul class="ps-0">
                            @forelse ($artikel as $item)
                                <li class="single-user-review d-flex">
                                    <div class="user-thumbnail mt-0">
                                        <img style="border-radius: 2px !important"
                                            src="{{ asset('assets/img/news.jpg') }}" alt="">
                                    </div>
                                    <div class="rating-comment">
                                        <a href="{{ route('artikel.show', $item->slug) }}"
                                            class="comment mb-0">{{ $item->judul }}</a>
                                        <span class="name-date">
                                            <svg class="bi bi-clock me-1" xmlns="http://www.w3.org/2000/svg" width="16"
                                                height="16" fill="currentColor" viewBox="0 0 16 16">
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
                                <div class="container">
                                    <div class="cta-text dir-rtl p-4 p-lg-5">
                                        <div class="row">
                                            <div class="col-9">
                                                <h5 class="text-white">Belum ada artikel.</h5>
                                            </div>
                                        </div>
                                        <img src="{{ asset('assets/img/book.png') }}" alt="" />
                                    </div>
                                </div>
                            @endforelse
                        </ul>
                        @if ($artikel->hasPages())
                            <div class="mt-3">
                                {{ $artikel->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection