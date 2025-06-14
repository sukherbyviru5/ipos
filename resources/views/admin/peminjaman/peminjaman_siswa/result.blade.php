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
    <a href="/admin/peminjaman/peminjaman-siswa/" class="btn-kembali">← Kembali</a>
    <div class="center bold">
        PERPUSTAKAAN SEKOLAH<br>
        STRUK PEMINJAMAN
    </div>
    <div class="line"></div>

    @foreach ($peminjamans as $peminjaman)
        @php
            $tglPinjam = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam);
            $tglJatuhTempo = \Carbon\Carbon::parse($peminjaman->tanggal_jatuh_tempo);
            $tglKembali = $peminjaman->tanggal_pengembalian
                ? \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)
                : null;
            $perpanjangan = $peminjaman->jumlah_perpanjangan ?? 0;

            $denda = 0;
            if ($setting->denda_telat_status && $tglKembali && $tglKembali->gt($tglJatuhTempo)) {
                $hariTelat = $tglKembali->diffInDays($tglJatuhTempo);
                $denda = $hariTelat * $setting->denda_telat;
            }
        @endphp

        <div class="section">
            NIK : {{ $peminjaman->nik_siswa }}<br>
            Nama : {{ $peminjaman->siswa->nama_siswa ?? '-' }}<br>
            Buku : {{ $peminjaman->qrBuku->buku->judul_buku ?? '-' }}<br>
            Kode : {{ $peminjaman->qrBuku->kode ?? '-' }}<br>
            Pinjam : {{ $tglPinjam->format('d-m-Y') }}<br>
            Tempo : {{ $tglJatuhTempo->format('d-m-Y') }}<br>
            Perpanjang : {{ $perpanjangan }}x<br>
            Status : {{ ucfirst($peminjaman->status_peminjaman) }}
        </div>
        <div class="line"></div>
    @endforeach

    @if ($setting->denda_telat_status == 'aktif')
        <div class="section">
            <div class="bold">Jumlah Denda:</div>
            @if ($setting->perhitungan_denda === 'per hari')
                Apabila terlambat mengembalikan buku maka dikenakan denda Rp
                {{ number_format($setting->denda_telat, 0, ',', '.') }} /hari dari tanggal tempo
            @elseif ($setting->perhitungan_denda === 'per minggu')
                Apabila terlambat mengembalikan buku maka dikenakan denda Rp
                {{ number_format($setting->denda_telat, 0, ',', '.') }} /minggu dari tanggal tempo
            @else
                -
            @endif
        </div>
    @endif

    <div class="section">
        <div class="bold">Syarat Peminjaman:</div>
        {!! nl2br(e($setting->syarat_peminjaman)) !!}
    </div>

    <div class="section">
        <div class="bold">Syarat Perpanjangan:</div>
        {!! nl2br(e($setting->syarat_perpanjangan)) !!}
    </div>

    <div class="section">
        <div class="bold">Syarat Pengembalian:</div>
        {!! nl2br(e($setting->syarat_pengembalian)) !!}
    </div>

    <div class="section">
        <div class="bold">Sanksi Kerusakan:</div>
        {!! nl2br(e($setting->sanksi_kerusakan)) !!}
    </div>

    <div class="line"></div>
    <div class="center">Terima kasih - Selamat Membaca!</div>
</body>

</html>
