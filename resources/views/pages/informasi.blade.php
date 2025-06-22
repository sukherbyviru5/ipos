@extends('layout.pages')
@section('title', 'Informasi Perpustakaan')
@section('content')
    <style>
        body {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }

        .container {
            max-width: 100% !important;
            position: relative;
            z-index: 1;
        }

        .header {
            background-color: #0091ab;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 5px 5px 0 0;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            min-height: 400px;
            display: flex;
            flex-direction: column;
        }

        .card-body {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .row {
            margin-right: -5px;
            margin-left: -5px;
        }

        .row>.col-md-3,
        .row>.col-md-4,
        .row>.col-md-5,
        .row>.col-lg-6 {
            padding-right: 5px;
            padding-left: 5px;
        }

        .profile-img {
            max-width: 180px;
            border: 1px solid #ddd;
            padding: 5px;
            object-fit: cover;
        }

        .book-img {
            max-width: 180px;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
            margin: 0 auto;
        }

        .video-img {
            max-width: 100px;
            height: auto;
            cursor: pointer;
            margin: 0 auto;
        }

        .stats-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }

        .stats-card {
            background-color: #fff;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 0 0 calc(50% - 10px);
            max-width: calc(50% - 10px);
            margin: 0;
        }

        .stats-card i {
            font-size: 24px;
            color: #0091ab;
            margin-bottom: 5px;
        }

        .stats-card h6 {
            font-size: 14px;
            margin: 5px 0;
            color: #333;
        }

        .stats-card span {
            font-size: 18px;
            font-weight: bold;
            color: #0091ab;
        }

        @media (max-width: 768px) {
            .stats-container {
                flex-direction: column;
                gap: 10px;
            }

            .stats-card {
                flex: 1 1 100%;
                max-width: 100%;
            }
        }

        .book-list,
        .video-list {
            list-style: none;
            padding: 0;
        }

        .carousel-container {
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-slide {
            flex: 0 0 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 10px;
        }

        .video-carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .video-carousel-slide {
            flex: 0 0 25%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 5px;
        }

        .product-item {
            text-align: center;
        }

        .product-name {
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }

        .carousel-pagination {
            text-align: center;
            margin-top: 10px;
        }

        .carousel-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            background-color: #bbb;
            border-radius: 50%;
            margin: 0 5px;
            cursor: pointer;
        }

        .carousel-dot.active {
            background-color: #0091ab;
        }

        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 145, 171, 0.7);
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            z-index: 10;
        }

        .carousel-prev {
            left: 10px;
        }

        .carousel-next {
            right: 10px;
        }

        iframe {
            width: 100%;
            height: 360px;
            border: none;
        }

        @media (max-width: 768px) {

            .row>.col-md-3,
            .row>.col-md-4,
            .row>.col-md-5,
            .row>.col-lg-6 {
                margin-bottom: 10px;
            }

            .card {
                min-height: auto;
            }

            .video-carousel-slide {
                flex: 0 0 50%;
            }

            iframe {
                height: 200px;
            }
        }
    </style>

    <nav class="navbar navbar-secondary navbar-expand-lg">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="{{ url('/buku-tamu') }}" class="nav-link"><i class="fa-solid fa-users"></i><span>Buku
                            Tamu</span></a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/bebas-perpus') }}" class="nav-link"><i class="fa-solid fa-id-card"></i><span>Cek Bebas
                            Perpus</span></a>
                </li>
                <li class="nav-item active">
                    <a href="{{ url('/informasi') }}" class="nav-link"><i
                            class="fa-solid fa-info-circle"></i><span>Informasi</span></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="overlay">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session()->get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Top Visitor -->
                        <div class="col-md-3">
                            <div class="card">
                                <div class="header">
                                    PENGUNJUNG FAVORIT BULAN INI
                                </div>
                                <div class="card-body text-center">
                                    @if ($topVisitor)
                                        <img src="{{ $topVisitor->foto ? asset($topVisitor->foto) : asset('assets/img/avatar.png') }}"
                                            alt="Foto Pengunjung" class="">
                                        <h6 class="mt-2">{{ $topVisitor->nama }}</h6>
                                    @else
                                        <p>Belum ada data pengunjung bulan ini.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Latest Books -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="header">
                                    10 BUKU TERBARU
                                </div>
                                <div class="card-body">
                                    <div class="carousel-container" id="latest-books-carousel">
                                        <div class="carousel-track">
                                            @foreach ($latestBooks as $book)
                                                <div class="carousel-slide">
                                                    <div class="product-item">
                                                        <div class="product-image" style="width: 100%; height: 245px;">
                                                            <img alt="image"
                                                                src="{{ $book->cover_buku ? asset($book->cover_buku) : asset('assets/img/default-book.webp') }}"
                                                                class="book-img">
                                                        </div>
                                                        <div class="product-name">{{ $book->judul_buku }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="carousel-pagination" id="latest-books-pagination"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="col-lg-5">
                            <div class="card">
                                <div class="header">
                                    STATISTIK HARI INI
                                </div>
                                <div class="card-body">
                                    <div class="stats-container">
                                        <div class="stats-card" style="border: 1px solid rgb(234, 234, 234);">
                                            <i class="fa-solid fa-users mt-2"></i>
                                            <h6>Pengunjung Hari Ini</h6>
                                            <span>{{ $todayVisitors }}</span>
                                        </div>
                                        <div class="stats-card" style="border: 1px solid rgb(234, 234, 234);">
                                            <i class="fa-solid fa-book mt-2"></i>
                                            <h6>Peminjam Hari Ini</h6>
                                            <span>{{ $todayBorrowers }}</span>
                                        </div>
                                        <div class="stats-card" style="border: 1px solid rgb(234, 234, 234);">
                                            <i class="fa-solid fa-book-open mt-2"></i>
                                            <h6>Pembaca Hari Ini</h6>
                                            <span>{{ $todayReaders }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Videos -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="header">VIDEO PUTAR</div>
                                <div class="card-body">
                                    <div id="youtube-player"></div>
                                    <div class="carousel-container d-none" id="video-thumbnails">
                                        @foreach ($videos as $index => $video)
                                            @php
                                                preg_match('/v=([^\&]+)/', $video->video_url, $matches);
                                                $videoId = $matches[1] ?? '';
                                            @endphp
                                            <div class="video-carousel-slide" data-video-id="{{ $videoId }}"
                                                data-index="{{ $index }}">
                                                <img src="https://img.youtube.com/vi/{{ $videoId }}/default.jpg"
                                                    alt="{{ $video->judul }}" class="video-img" width="120">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Most Read Books -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="header">
                                    10 BUKU TERBANYAK DIBACA BULAN INI
                                </div>
                                <div class="card-body">
                                    <div class="carousel-container" id="most-read-books-carousel">
                                        <div class="carousel-track">
                                            @foreach ($mostReadBooks as $book)
                                                <div class="carousel-slide">
                                                    <div class="product-item row">
                                                        <div class="product-image col-4" style="width: 100%; height: auto;">
                                                            <img alt="image"
                                                                src="{{ $book->cover_buku ? asset($book->cover_buku) : asset('assets/img/default-book.png') }}"
                                                                class="book-img">
                                                        </div>
                                                        <div class="col-8">
                                                            <dl class="dl-horizontal text-start"
                                                                style="text-align: start !important;">
                                                                <dt>Judul Buku : </dt>
                                                                <dd>{{ $book->judul_buku }}.</dd>
                                                                <dt>Jumlah Dibaca : </dt>
                                                                <dd>{{ $book->read_count }} kali</dd>
                                                                <dt>Sinopsis :</dt>
                                                                <dd>
                                                                    {{ \Illuminate\Support\Str::words(strip_tags($book->sinopsis), 25, '...') }}
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="carousel-pagination" id="most-read-books-pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
        const videoIds = @json(
            $videos->pluck('video_url')->map(function ($url) {
                    preg_match('/v=([^\&]+)/', $url, $matches);
                    return $matches[1] ?? '';
                })->toArray());
                
        let currentVideoIndex = 0;
        let player;
        let playerReady = false;

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('youtube-player', {
                height: '100%',
                width: '100%',
                videoId: videoIds[currentVideoIndex],
                playerVars: {
                    autoplay: 1,
                    controls: 1,
                    rel: 0,
                    playsinline: 1
                },
                events: {
                    onReady: onPlayerReady,
                    onStateChange: onPlayerStateChange
                }
            });
        }

        function onPlayerReady(event) {
            playerReady = true;
            event.target.playVideo();
        }

        function onPlayerStateChange(event) {
            if (event.data === YT.PlayerState.ENDED) {
                currentVideoIndex++;
                if (currentVideoIndex < videoIds.length) {
                    player.loadVideoById(videoIds[currentVideoIndex]);
                } else {
                    currentVideoIndex = 0;
                    player.loadVideoById(videoIds[currentVideoIndex]);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.video-carousel-slide').forEach(slide => {
                slide.addEventListener('click', () => {
                    const videoId = slide.getAttribute('data-video-id');
                    const index = parseInt(slide.getAttribute('data-index'));
                    if (playerReady && videoId) {
                        currentVideoIndex = index;
                        player.loadVideoById(videoId);
                    }
                });
            });
        });

        function initBookCarousel(carouselId, paginationId) {
            const carousel = document.getElementById(carouselId);
            const track = carousel.querySelector('.carousel-track');
            const slides = track.querySelectorAll('.carousel-slide');
            const pagination = document.getElementById(paginationId);
            let currentIndex = 0;
            const slideCount = slides.length;

            for (let i = 0; i < slideCount; i++) {
                const dot = document.createElement('span');
                dot.classList.add('carousel-dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    currentIndex = i;
                    updateCarousel();
                });
                pagination.appendChild(dot);
            }

            function updateCarousel() {
                track.style.transform = `translateX(-${currentIndex * 100}%)`;
                const dots = pagination.querySelectorAll('.carousel-dot');
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }

            let autoSlide = setInterval(() => {
                currentIndex = (currentIndex + 1) % slideCount;
                updateCarousel();
            }, 3000);

            carousel.addEventListener('mouseenter', () => clearInterval(autoSlide));
            carousel.addEventListener('mouseleave', () => {
                autoSlide = setInterval(() => {
                    currentIndex = (currentIndex + 1) % slideCount;
                    updateCarousel();
                }, 3000);
            });

            updateCarousel();
        }

        document.addEventListener('DOMContentLoaded', () => {
            initBookCarousel('latest-books-carousel', 'latest-books-pagination');
            initBookCarousel('most-read-books-carousel', 'most-read-books-pagination');
        });
    </script>
@endsection
