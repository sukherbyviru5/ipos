@extends('guest')
@section('title', 'Publikasi Artikel')
@section('content')
    <div class="container mt-1">
        <br>
        <!-- Coupon Area -->
        <div class="card coupon-card mb-3 py-3">
            <div class="card-body">
                <div class="apply-coupon">
                    <h6 class="mb-0">Cari Artikel Saya ?</h6>
                    <p class="mb-2">Masukkan kata kunci untuk menemukan artikel yang saya buat!</p>
                    <div class="coupon-form">
                        <form action="">
                            <input class="form-control" type="search" name="q" value="{{ request('q') }}"
                                placeholder="Cari Artikel">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cart Amount Area -->
        <div class="card cart-amount-area">
            <div class="card-body d-flex align-items-center justify-content-between">
                <h6 class="total-price mb-0">(<span class="counter">{{ $artikels->total() }}</span>) artikel</h6>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addArtikelModal">Tambah
                    Artikel</button>
            </div>
        </div>
        <!-- Cart Wrapper -->
        <div class="cart-wrapper-area py-3">
            <div class="cart-table card mb-3">
                <div class="table-responsive card-body">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Judul Artikel</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($artikels as $index => $artikel)
                                <tr>
                                    <td>{{ $artikels->firstItem() + $index }}.</td>
                                    <td><a class="product-title" href="{{ asset($artikel->file) }}"
                                            target="_blank">{{ $artikel->judul }}</a></td>
                                    <td>
                                        <span
                                            class="badge {{ $artikel->status == 'setuju' ? 'bg-success' : ($artikel->status == 'tolak' ? 'bg-danger' : 'bg-warning') }}">
                                            {{ ucfirst($artikel->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal"
                                            data-bs-target="#editArtikelModal" data-id="{{ $artikel->id }}"
                                            data-judul="{{ $artikel->judul }}" data-file="{{ $artikel->file }}"
                                            onclick="populateEditModal(this)">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <form action="{{ route('guru.publikasi.delete', $artikel->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus artikel ini?')">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $artikels->links('pagination::simple-tailwind') }}
            </div>
        </div>
    </div>

    <!-- Add Artikel Modal -->
    <div class="modal fade" id="addArtikelModal" tabindex="-1" aria-labelledby="addArtikelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addArtikelModalLabel">Tambah Artikel Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('guru.publikasi.store') }}" method="POST" enctype="multipart/form-data"
                    id="addArtikelForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Artikel</label>
                            <input type="text" class="form-control" id="judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Upload File PDF</label>
                            <div class="custom-file-upload">
                                <input type="file" class="form-control" id="file" name="file" accept=".pdf"
                                    required>
                                <span class="file-name">Pilih file PDF...</span>
                                <div class="upload-progress" style="display: none;">
                                    <div class="progress-bar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Artikel Modal -->
    <div class="modal fade" id="editArtikelModal" tabindex="-1" aria-labelledby="editArtikelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editArtikelModalLabel">Edit Artikel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data" id="editArtikelForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_judul" class="form-label">Judul Artikel</label>
                            <input type="text" class="form-control" id="edit_judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_file" class="form-label">Upload File PDF</label>
                            <div class="custom-file-upload">
                                <input type="file" class="form-control" id="edit_file" name="file"
                                    accept=".pdf">
                                <span class="file-name">Pilih file PDF...</span>
                                <div class="upload-progress" style="display: none;">
                                    <div class="progress-bar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <style>
        .custom-file-upload {
            position: relative;
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
        }

        .custom-file-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-name {
            display: block;
            margin-top: 10px;
            color: #666;
        }

        .upload-progress {
            margin-top: 10px;
            height: 5px;
            background: #f0f0f0;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar {
            width: 0;
            height: 100%;
            background: #007bff;
            transition: width 0.3s ease;
        }
    </style>
@endsection

@section('scripts')
    <script>
        function populateEditModal(button) {
            const id = button.dataset.id;
            const judul = button.dataset.judul;
            const file = button.dataset.file;
            const form = document.getElementById('editArtikelForm');
            form.action = `/guru/publikasi/${id}`;
            document.getElementById('edit_judul').value = judul;
            document.querySelector('#editArtikelModal .file-name').textContent = file ? file.split('/').pop() :
                'Pilih file PDF...';
        }

        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files[0]?.name || 'Pilih file PDF...';
                const fileNameSpan = this.parentElement.querySelector('.file-name');
                const progressBar = this.parentElement.querySelector('.progress-bar');
                const progressContainer = this.parentElement.querySelector('.upload-progress');

                fileNameSpan.textContent = fileName;
                progressContainer.style.display = 'block';
                progressBar.style.width = '0%';

                let progress = 0;
                const interval = setInterval(() => {
                    progress += 10;
                    progressBar.style.width = `${progress}%`;
                    if (progress >= 100) {
                        clearInterval(interval);
                        setTimeout(() => {
                            progressContainer.style.display = 'none';
                        }, 500);
                    }
                }, 200);
            });
        });
    </script>
@endsection
