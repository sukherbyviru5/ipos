@extends('layout.kop')
@section('title')
    LAPORAN BUKU INDUK PERPUSTAKAAN <br>{{ $kop->nama_madrasah ?? 'Nama Madrasah' }}
@endsection

@section('content')
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Judul</th>
                    <th style="text-align: center">Penulis</th>
                    <th style="text-align: center">ISBN</th>
                    <th style="text-align: center">Kondisi Buku</th>
                    <th style="text-align: center">Kategori Buku</th>
                    <th colspan="3" style="text-align: center">Impresum</th>
                    <th style="text-align: center">Asal Buku</th>
                    <th colspan="2" style="text-align: center">Harga</th>
                    <th style="text-align: center">Klasifikasi DDC</th>
                    <th style="text-align: center">Keterangan</th>
                </tr>
                <tr>
                    <th style="text-align: center"></th>
                    <th style="text-align: center"></th>
                    <th style="text-align: center"></th>
                    <th style="text-align: center"></th>
                    <th style="text-align: center"></th>
                    <th style="text-align: center"></th>
                    <th style="text-align: center">Tempat Terbit</th>
                    <th style="text-align: center">Penerbit</th>
                    <th style="text-align: center">Tahun Terbit</th>
                    <th style="text-align: center"></th>
                    <th style="text-align: center">Satuan</th>
                    <th style="text-align: center">Jumlah</th>
                    <th style="text-align: center"></th>
                    <th style="text-align: center"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($buku as $index => $book)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $book->judul_buku ?? '-' }}</td>
                        <td>{{ $book->penulis_buku ?? '-' }}</td>
                        <td>{{ $book->isbn ?? '-' }}</td>
                        <td>{{ $book->kondisi_buku->nama_kondisi ?? '-' }}</td>
                        <td>{{ $book->kategori_buku->nama_kategori ?? '-' }}</td>
                        <td>{{ $book->tempat_terbit ?? '-' }}</td>
                        <td>{{ $book->penerbit_buku ?? '-' }}</td>
                        <td>{{ $book->tahun_terbit ?? '-' }}</td>
                        <td>{{ $book->asal_buku ?? '-' }}</td>
                        <td>Buku</td>
                        <td>{{ $book->stok_buku ?? '0' }}</td>
                        <td>{{ $book->ddc_buku->nama_klasifikasi ?? '-' }}</td>
                        <td>{{ '' }}</td> 
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" style="text-align: center;">Tidak ada data buku.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection