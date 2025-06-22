@extends('guest')
@section('title', 'Foto Kegiatan')
@section('meta_description', 'Foto foto kegiatan perpustakaan')
@section('meta_keywords', 'buku digital, perpustakaan online, parepare, koleksi buku, foto kegiatan')
@section('content')
    <!-- Foto Kegiatan -->
    <div class="top-products-area py-3">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h5 class="faq-heading text-center">Foto-foto Kegiatan</h5>
                    <!-- Search Form-->
                    <form class="faq-search-form" action="#" method="">
                        <input class="form-control" type="search" name="q" value="{{ request('q') }}"
                            placeholder="Search">
                        <button type="submit"><i class="ti ti-search"></i></button>
                    </form>
                </div>
            </div>
            <div class="section-heading d-flex align-items-center justify-content-between rtl-flex-d-row-r">
            </div>
            <div class="row g-2 rtl-flex-d-row-r">
                @forelse ($foto as $item)
                    <div class="col-12 col-md-6">
                        <div class="card blog-card list-card">
                            <div class="post-img">
                                <img src="{{ asset($item->foto) }}" alt="{{ $item->keterangan }}">
                            </div>
                            <a class="btn btn-primary btn-sm read-more-btn" href="javascript:void(0)"
                                data-image="{{ asset($item->foto) }}" data-caption="{{ $item->keterangan }}">Lihat Foto</a>
                            <div class="post-content">
                                <div class="bg-shapes">
                                    <div class="circle1"></div>
                                    <div class="circle2"></div>
                                    <div class="circle3"></div>
                                    <div class="circle4"></div>
                                </div>
                                <a class="post-title">{{ $item->keterangan }}</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center">Tidak ada foto kegiatan yang tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Lightbox -->
    <div class="custom-lightbox" id="customLightbox">
        <div class="lightbox-content">
            <span class="lightbox-close">&times;</span>
            <img src="" alt="Lightbox Image" class="lightbox-image">
            <p class="lightbox-caption"></p>
        </div>
    </div>

@endsection
@section('styles')
    <style>
        .custom-lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            overflow: auto;
        }

        .lightbox-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            text-align: center;
        }

        .lightbox-image {
            max-width: 100%;
            max-height: 70vh;
            object-fit: contain;
            border-radius: 8px;
        }

        .lightbox-caption {
            color: #fff;
            font-size: 16px;
            margin-top: 10px;
            max-width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .lightbox-close {
            position: absolute;
            top: -30px;
            right: -10px;
            color: #fff;
            font-size: 30px;
            cursor: pointer;
            user-select: none;
        }

        .custom-lightbox.show {
            display: flex;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lightbox = document.getElementById('customLightbox');
            const lightboxImage = lightbox.querySelector('.lightbox-image');
            const lightboxCaption = lightbox.querySelector('.lightbox-caption');
            const lightboxClose = lightbox.querySelector('.lightbox-close');
            const viewButtons = document.querySelectorAll('.read-more-btn');

            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const imageSrc = this.getAttribute('data-image');
                    const caption = this.getAttribute('data-caption');

                    lightboxImage.src = imageSrc;
                    lightboxCaption.textContent = caption;
                    lightbox.classList.add('show');
                });
            });

            lightboxClose.addEventListener('click', function() {
                lightbox.classList.remove('show');
            });

            lightbox.addEventListener('click', function(e) {
                if (e.target === lightbox) {
                    lightbox.classList.remove('show');
                }
            });
        });
    </script>
@endsection
