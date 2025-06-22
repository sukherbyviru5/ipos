<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Keterangan Perpustakaan</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            margin: 0;
            padding: 5px 8px;
            width: 58mm;
            max-width: 384px;
            line-height: 1.4;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .section {
            margin-bottom: 6px;
        }

        .field {
            margin: 3px 0;
        }

        .field-label {
            display: inline-block;
            width: 80px;
        }

        .book-item {
            margin: 4px 0;
            padding-left: 10px;
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

<body>
    <div class="section">
        @if ($hasLoans)
            <div class="center bold">KETERANGAN DAFTAR BUKU PINJAMAN</div>
            <div class="line"></div>
            <div class="field">
                <span class="field-label">NIK:</span> {{ $userData['nik'] }}
            </div>
            <div class="field">
                <span class="field-label">NAMA:</span> {{ $userData['nama_siswa'] ?? $userData['nama_guru'] }}
            </div>
            @if (!empty($userData['kelas']) && $userData['kelas'] !== '-')
                <div class="field">
                    <span class="field-label">KELAS:</span> {{ $userData['kelas'] }}
                </div>
            @endif
            @if (!empty($userData['nama_mata_pelajaran']) && $userData['nama_mata_pelajaran'] !== '-')
                <div class="field">
                    <span class="field-label">MAPEL:</span> {{ $userData['nama_mata_pelajaran'] }}
                </div>
            @endif
            <div class="line"></div>
            @foreach ($loans as $index => $loan)
                JUDUL: {{ $loan->qrBuku->buku->judul_buku ?? 'N/A' }}<br>
                KODE: {{ $loan->qrBuku->kode ?? 'N/A' }}<br>
                PINJAM: {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d-m-Y') }}<br>
                TEMPO: {{ \Carbon\Carbon::parse($loan->tanggal_jatuh_tempo)->format('d-m-Y') }}<br>
                PERPANJANG: {{ $loan->perpanjang ?? '0x' }}<br>
                STATUS: {{ $loan->status_peminjaman ?? 'Dipinjam' }}
                <div class="line"></div>
            @endforeach
            <div class="field center">Tgl Cetak:
                {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y') }}</div>
        @else
            <div class="center bold">KETERANGAN BEBAS PERPUSTAKAAN</div>
            <div class="line"></div>
            <div class="center">SELAMAT ANDA BEBAS PERPUSTAKAAN</div>
            <div class="field">
                <span class="field-label">NIK:</span> {{ $userData['nik'] }}
            </div>
            <div class="field">
                <span class="field-label">NAMA:</span> {{ $userData['nama_siswa'] ?? $userData['nama_guru'] }}
            </div>
            @if (!empty($userData['kelas']) && $userData['kelas'] !== '-')
                <div class="field">
                    <span class="field-label">KELAS:</span> {{ $userData['kelas'] }}
                </div>
            @endif
            @if (!empty($userData['nama_mata_pelajaran']) && $userData['nama_mata_pelajaran'] !== '-')
                <div class="field">
                    <span class="field-label">MAPEL:</span> {{ $userData['nama_mata_pelajaran'] }}
                </div>
            @endif
            <div class="line"></div>
            <div class="field center">Tgl Cetak:
                {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y') }}</div>
        @endif
    </div>
</body>

</html>
