<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
</head>
<style>
    /* ====== BASE ====== */
    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt;
        margin: 2cm;
        line-height: 1.5;
        color: #000;
        background: #fff;
    }

    /* ====== LAYOUT & SECTION ====== */
    .title {
        text-align: center;
        margin: 20px 0;
    }

    .title h2 {
        text-decoration: underline;
        font-size: 14pt;
        margin: 0;
    }

    .title p {
        text-align: end;
        margin: 20px 0;
    }

    .table-container {
        margin-top: 20px;
    }

    /* ====== TABLE UMUM ====== */
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11pt;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    /* ====== TABLE KHUSUS: KOP ====== */
    table.kop {
        width: 100%;
        border: none;
        border-bottom: 5px double #000;
        margin-bottom: 20px;
    }

    table.kop td {
        vertical-align: top;
        border: none;

    }

    table.kop img {
        max-height: 100px;
        position: absolute;
    }

    /* ====== BACK BUTTON ====== */
    .back-button {
        margin-bottom: 20px;
        text-align: left;
    }

    .back-button a {
        padding: 8px 16px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
    }

    /* ====== SIGNATURE SECTION ====== */
    .signature {
        margin-top: 50px;
        display: flex;
        justify-content: space-between;
    }

    .signature div {
        width: 45%;
        text-align: center;
    }

    .signature p {
        margin: 0;
    }

    .signature .underline {
        border-bottom: 1px solid #000;
        margin-top: 60px;
        margin-bottom: 5px;
    }

    /* ====== MEDIA PRINT ====== */
    @media print {
        .back-button {
            display: none;
        }

        body {
            margin: 1cm;
        }

        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
    }
</style>

<body>
    <table class="kop">
        <tbody style="border: none;">
            <tr style="border: none;">
                <td style="text-align: star;">
                    @if ($kop && $kop->logo)
                        <img src="{{ asset($kop->logo) }}" alt="Logo" class="logo">
                    @endif
                </td>
                <td style="text-align: center; ">
                    <h3 style="margin: 0; font-size: 14pt;">{{ $kop->nama_instansi ?? 'Nama Instansi' }}</h3>
                    <h3 style="margin: 0; font-size: 13pt;">{{ $kop->nama_sub_instansi ?? 'Nama Sub Instansi' }}
                    </h3>
                    <h2 style="margin: 0; font-size: 16pt; font-weight: bold;">
                        {{ $kop->nama_madrasah ?? 'Nama Madrasah' }}</h2>
                    <p style="margin: 0; font-size: 11pt;">{{ $kop->alamat_madrasah ?? 'Alamat Madrasah' }}</p>
                </td>
            </tr>
        </tbody>
    </table>

    @yield('content')
    
    <div id="tempat" style="display: flex; justify-content: end;">
        <p>Parepare, {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <div class="signature">
        <div>
            <p>Kepala Madrasah</p>
            <p class="underline"></p>
            <p>{{ $kop->nama_kepala_madrasah ?? '-' }}</p>
            <p>NIP: {{ $kop->nip_kamad ?? '-' }}</p>
        </div>
        <div>
            <p>Kepala Perpustakaan</p>
            <p class="underline"></p>
            <p>{{ $kop->nama_kepala_perpustakaan ?? '-' }}</p>
            <p>NIP: {{ $kop->nip_kepala_perpustakaan ?? '-' }}</p>
        </div>
    </div>
    <script>
        window.onload = function() {
          window.print();
        };
    </script>
</body>

</html>
