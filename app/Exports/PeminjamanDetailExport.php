<?php

namespace App\Exports;

use App\Models\PeminjamanSiswa;
use App\Models\SettingApp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Carbon\Carbon;

class PeminjamanDetailExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $yearStart, $monthStart, $yearEnd, $monthEnd, $kop, $months;

    public function __construct($yearStart, $monthStart, $yearEnd, $monthEnd)
    {
        $this->yearStart = $yearStart;
        $this->monthStart = $monthStart;
        $this->yearEnd = $yearEnd;
        $this->monthEnd = $monthEnd;
        $this->kop = SettingApp::first();
        $this->months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    }

    public function collection()
    {
        $startDate = Carbon::create($this->yearStart, $this->monthStart, 1);
        $endDate = Carbon::create($this->yearEnd, $this->monthEnd)->endOfMonth();

        return PeminjamanSiswa::with(['siswa', 'qrBuku.buku', 'denda'])
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->orderBy('tanggal_pinjam', 'asc')
            ->get();
    }

    public function map($peminjaman): array
    {
        // Align keterangan with HTML logic
        $keterangan = '';
        if ($peminjaman->status_peminjaman == 'dikembalikan' && !$peminjaman->denda?->jumlah_denda) {
            $keterangan = 'Sudah Dikembalikan';
        } elseif ($peminjaman->status_peminjaman == 'telat' || $peminjaman->denda?->jumlah_denda) {
            $keterangan = $peminjaman->status_peminjaman == 'telat' ? 'Terlambat' : ($peminjaman->status_peminjaman == 'dikembalikan' ? 'Sudah Dikembalikan' : ucfirst($peminjaman->status_peminjaman));
            if ($peminjaman->denda?->jumlah_denda) {
                $keterangan .= ' - Denda Rp ' . number_format($peminjaman->denda->jumlah_denda, 0, ',', '.') . ' (' . ($peminjaman->denda->status_denda == 'lunas' ? 'Lunas' : 'Belum Lunas') . ')';
            }
        } else {
            $keterangan = ucfirst($peminjaman->status_peminjaman);
        }

        static $index = 0;
        $index++;

        return [
            $index . '.',
            $peminjaman->siswa->nama_siswa ?? '',
            $peminjaman->qrBuku->buku->judul_buku ?? '',
            \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y'),
            $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') : '',
            $keterangan,
            $peminjaman->denda?->jumlah_denda ? 'Rp ' . number_format($peminjaman->denda->jumlah_denda, 0, ',', '.') : 'Rp 0',
        ];
    }

    public function headings(): array
    {
        $yearDisplay = $this->yearStart == $this->yearEnd ? $this->yearEnd : $this->yearStart . '-' . $this->yearEnd;
        return [
            [$this->kop->nama_instansi ?? 'Nama Instansi'],
            [$this->kop->nama_sub_instansi ?? 'Nama Sub Instansi'],
            [$this->kop->nama_madrasah ?? 'Nama Madrasah'],
            [$this->kop->alamat_madrasah ?? 'Alamat Madrasah'],
            [''],
            ['DETAIL LAPORAN PEMINJAMAN/PENGEMBALIAN BUKU SISWA'],
            ['BULAN ' . strtoupper($this->months[$this->monthStart]) . ' SAMPAI DENGAN ' . strtoupper($this->months[$this->monthEnd])],
            ['TAHUN ' . $yearDisplay],
            [''],
            ['No', 'Nama Siswa', 'Judul Buku', 'Tanggal Peminjaman', 'Tanggal Pengembalian', 'Keterangan', 'Denda'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $sheet->mergeCells('A4:G4');
        $sheet->mergeCells('A6:G6');
        $sheet->mergeCells('A7:G7');
        $sheet->mergeCells('A8:G8');
        $sheet->getStyle('A1:G8')->applyFromArray([
            'font' => ['name' => 'Times New Roman', 'bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getStyle('A10:G10')->applyFromArray([
            'font' => ['name' => 'Times New Roman', 'bold' => true, 'size' => 11],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF2F2F2']],
        ]);

        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A11:G' . $highestRow)->applyFromArray([
            'font' => ['name' => 'Times New Roman', 'size' => 11],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(15);

        return [];
    }
}
?>