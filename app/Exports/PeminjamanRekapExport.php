<?php

namespace App\Exports;

use App\Models\PeminjamanSiswa;
use App\Models\SettingApp;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Carbon\Carbon;

class PeminjamanRekapExport implements FromArray, WithHeadings, WithStyles
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

    public function array(): array
    {
        $data = [];
        $rowNumber = 1; // Initialize row number counter

        for ($month = $this->monthStart; $month <= $this->monthEnd; $month++) {
            $year = $this->yearStart;
            if ($year > $this->yearEnd || ($year == $this->yearEnd && $month > $this->monthEnd)) {
                break;
            }

            $monthStartDate = Carbon::create($year, $month, 1);
            $monthEndDate = $monthStartDate->copy()->endOfMonth();

            $peminjaman = PeminjamanSiswa::whereBetween('tanggal_pinjam', [$monthStartDate, $monthEndDate])->count();
            $pengembalian = PeminjamanSiswa::whereBetween('tanggal_kembali', [$monthStartDate, $monthEndDate])
                ->whereNotNull('tanggal_kembali')->count();

            $data[] = [
                $rowNumber, // Use incremental row number
                $this->months[$month],
                $peminjaman ?: '--',
                $pengembalian ?: '--',
            ];
            
            $rowNumber++; // Increment row number
        }
        return $data;
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
            ['REKAPITULASI LAPORAN PEMINJAMAN/PENGEMBALIAN BUKU SISWA'],
            ['BULAN ' . strtoupper($this->months[$this->monthStart]) . ' SAMPAI DENGAN ' . strtoupper($this->months[$this->monthEnd])],
            ['TAHUN ' . $yearDisplay],
            [''],
            ['No', 'Bulan', 'Jumlah Peminjaman', 'Jumlah Pengembalian'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->mergeCells('A4:D4');
        $sheet->mergeCells('A6:D6');
        $sheet->mergeCells('A7:D7');
        $sheet->mergeCells('A8:D8');
        $sheet->getStyle('A1:D8')->applyFromArray([
            'font' => ['name' => 'Times New Roman', 'bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getStyle('A10:D10')->applyFromArray([
            'font' => ['name' => 'Times New Roman', 'bold' => true, 'size' => 11],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF2F2F2']],
        ]);

        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A11:D' . $highestRow)->applyFromArray([
            'font' => ['name' => 'Times New Roman', 'size' => 11],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);

        return [];
    }
}
?>