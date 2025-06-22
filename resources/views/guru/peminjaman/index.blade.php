@extends('guest')
@section('title', 'Peminjaman Buku')
@section('content')
    <div class="container mt-1">
        <br>
        <!-- Coupon Area -->
        <div class="card coupon-card mb-3 py-3">
            <div class="card-body">
                <div class="apply-coupon">
                    <h6 class="mb-0">Cari Buku Saya ?</h6>
                    <p class="mb-2">Masukkan kata kunci untuk menemukan buku yang saya pernah pinjam !</p>
                    <div class="coupon-form">
                        <form action="">
                            <input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="Cari Judul">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cart Amount Area -->
        <div class="card cart-amount-area">
            <div class="card-body d-flex align-items-center justify-content-between">
                <h6 class="total-price mb-0">(<span class="counter">{{ $peminjaman->total() }}</span>) Buku yang dipinjam</h6>
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
                                <th scope="col">Judul Buku</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjaman as $index => $row)
                            <tr>
                                <td>{{ $peminjaman->firstItem() + $index }}.</td>
                                <td><a class="product-title" href="#" target="_blank">{{ $row->qrBuku->buku->judul_buku }}</a><span style="font-size: 10px;" class="mt-1">{{ $row->qrBuku->kode }}</span></td>
                                <td>
                                    <span class="badge {{ $row->status_peminjaman == 'dipinjam' ? 'bg-success' : ($row->status_peminjaman == 'dikembalikan' ? 'bg-secondary' : 'bg-danger') }}">
                                        {{ ucfirst($row->status_peminjaman) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $peminjaman->links('pagination::simple-tailwind') }}
            </div>
        </div>
    </div>

@endsection