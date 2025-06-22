<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Struk Peminjaman</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            font-family: monospace;
            font-size: 10px;
            margin: 0;
            padding: 5px 10px;
            width: 58mm;
            max-width: 384px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .bold {
            font-weight: bold;
        }

        .section {
            margin-bottom: 6px;
        }

        .btn-kembali {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #000;
            color: #fff;
            font-size: 12px;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            z-index: 999;
            box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        @media print {
            .btn-kembali {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <a href="/admin/peminjaman/peminjaman-guru/" class="btn-kembali">← Kembali</a>
    <div class="center bold">
        PERPUSTAKAAN SEKOLAH<br>
        STRUK PEMINJAMAN
    </div>
    <div class="line"></div>

    @foreach ($peminjamans as $peminjaman)
        @php
            $tglPinjam = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam);
            
        @endphp

        <div class="section">
            NIK : {{ $peminjaman->nik_guru }}<br>
            Nama : {{ $peminjaman->guru->nama_guru ?? '-' }}<br>
            Buku : {{ $peminjaman->qrBuku->buku->judul_buku ?? '-' }}<br>
            Kode : {{ $peminjaman->qrBuku->kode ?? '-' }}<br>
            Pinjam : {{ $tglPinjam->format('d-m-Y') }}<br>
            Tempo : - <br>
            Status : {{ ucfirst($peminjaman->status_peminjaman) }}
        </div>
        <div class="line"></div>
    @endforeach

    <div class="center">Terima kasih - Selamat Membaca!</div>
</body>

</html>
