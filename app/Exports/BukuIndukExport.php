<?php

namespace App\Exports;

use App\Models\Buku;
use App\Models\SettingApp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class BukuIndukExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $kop;

    public function __construct()
    {
        $this->kop = SettingApp::first();
    }

    public function collection()
    {
        return Buku::orderBy('id', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            [$this->kop->nama_instansi ?? 'Nama Instansi'],
            [$this->kop->nama_sub_instansi ?? 'Nama Sub Instansi'],
            [$this->kop->nama_madrasah ?? 'Nama Madrasah'],
            [$this->kop->alamat_madrasah ?? 'Alamat Madrasah'],
            [''],
            ['FORMAT BUKU INDUK PERPUSTAKAAN'],
            [$this->kop->nama_madrasah ?? 'Nama Madrasah'],
            [''],
            ['No', 'Judul', 'Penulis', 'ISBN', 'Kondisi Buku', 'Kategori Buku', 'Impresum', '', '', 'Asal Buku', 'Harga', '', 'Klasifikasi DDC', 'Keterangan'],
            ['', '', '', '', '', '', 'Tempat Terbit', 'Penerbit', 'Tahun Terbit', '', 'Satuan', 'Jumlah', '', ''],
        ];
    }

    public function map($buku): array
    {
        static $index = 0;
        $index++;

        return [
            $index . '.',
            $buku->judul_buku ?? '',
            $buku->penulis_buku ?? '', 
            $buku->isbn ?? '', 
            $buku->kondisi_buku->nama_kondisi ?? '', 
            $buku->kategori_buku->nama_kategori ?? '', 
            $buku->tempat_terbit ?? '', 
            $buku->penerbit_buku ?? '', 
            $buku->tahun_terbit ?? '', 
            $buku->asal_buku ?? '',
            'Buku', 
            $buku->stok_buku ?? '0', 
            $buku->ddc_buku->nama_klasifikasi ?? '',
            '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:N1');
        $sheet->mergeCells('A2:N2');
        $sheet->mergeCells('A3:N3');
        $sheet->mergeCells('A4:N4');
        $sheet->mergeCells('A6:N6');
        $sheet->mergeCells('A7:N7');
        $sheet->getStyle('A1:N7')->applyFromArray([
            'font' => [
                'name' => 'Times New Roman',
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->mergeCells('G9:I9');
        $sheet->mergeCells('K9:L9'); 
        $sheet->getStyle('A9:N9')->applyFromArray([
            'font' => [
                'name' => 'Times New Roman',
                'bold' => true,
                'size' => 11,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFF2F2F2'],
            ],
        ]);

        $sheet->getStyle('A10:N10')->applyFromArray([
            'font' => [
                'name' => 'Times New Roman',
                'bold' => true,
                'size' => 11,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFF2F2F2'],
            ],
        ]);

        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A11:N' . $highestRow)->applyFromArray([
            'font' => [
                'name' => 'Times New Roman',
                'size' => 11,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getStyle('L11:L' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getColumnDimension('A')->setWidth(5); // No
        $sheet->getColumnDimension('B')->setWidth(20); // Judul
        $sheet->getColumnDimension('C')->setWidth(15); // Penulis
        $sheet->getColumnDimension('D')->setWidth(15); // ISBN
        $sheet->getColumnDimension('E')->setWidth(15); // Kondisi Buku
        $sheet->getColumnDimension('F')->setWidth(15); // Kategori Buku
        $sheet->getColumnDimension('G')->setWidth(15); // Tempat Terbit
        $sheet->getColumnDimension('H')->setWidth(15); // Penerbit
        $sheet->getColumnDimension('I')->setWidth(10); // Tahun Terbit
        $sheet->getColumnDimension('J')->setWidth(15); // Asal Buku
        $sheet->getColumnDimension('K')->setWidth(10); // Satuan
        $sheet->getColumnDimension('L')->setWidth(10); // Jumlah
        $sheet->getColumnDimension('M')->setWidth(15); // Klasifikasi DDC
        $sheet->getColumnDimension('N')->setWidth(20); // Keterangan

        return [];
    }
}
?>