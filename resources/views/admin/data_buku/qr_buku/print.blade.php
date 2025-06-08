<!DOCTYPE html>
<html>
<head>
    <title>Cetak QR Code - {{ $buku->judul_buku }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0;
            background-color: #fff;
            margin: 0;
        }
        .print-container {
            margin: 0 auto;
            max-width: 800px;
        }
        .qr-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px; /* Space between items */
        }
        .qr-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 10px;
            padding: 1px;
            width: 150px;
            height: 150px;
            justify-content: center;
            break-inside: avoid; /* Prevent item from breaking across pages */
        }
        .qr-image {
            width: 100px;
            height: 100px;
            background-color: #fff;
        }
        .qr-code-text {
            font-size: 10px;
            text-align: center;
            font-weight: bold;
            margin-top: 5px;
            word-break: break-all;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .back-link {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            margin-top: 5px;
        }
        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .qr-item:nth-child(20n) {
            page-break-after: always;
        }
        @media print {
            body {
                margin: 0;
            }
            .print-container {
                margin: 0;
            }
            .back-link {
                display: none;
            }
            .qr-list {
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="print-container">
        <div class="back-link">
            <a href="{{ url('admin/data-buku/qr-buku/detail/' . $buku->id) }}">Kembali</a>
        </div>
        <h2>Cetak QR Code - {{ $buku->judul_buku }}</h2>
        <div class="qr-list">
            @foreach ($qrBuku as $qr)
                <div class="qr-item">
                    <div class="qr-image">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($qr->kode) }}" alt="QR Code {{ $qr->kode }}" style="width: 100%; height: 100%;">
                    </div>
                    <div class="qr-code-text">{{ $qr->kode }}</div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>