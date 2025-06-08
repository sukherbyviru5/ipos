<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BooksExport implements FromCollection, WithHeadings, WithEvents
{
    protected $ddcs;
    protected $kategoris;
    protected $kondisis;
    protected $jeniss;

    public function __construct($ddcs, $kategoris, $kondisis, $jeniss)
    {
        $this->ddcs = $ddcs;
        $this->kategoris = $kategoris;
        $this->kondisis = $kondisis;
        $this->jeniss = $jeniss;
    }

    public function collection()
    {
        return collect([
            [
                'DDC' => '',
                'Kategori Buku' => '',
                'Kondisi Buku' => '',
                'Jenis Buku' => '',
                'Singkatan Buku' => '',
                'ISBN' => '',
                'Judul Buku' => '',
                'Penulis Buku' => '',
                'Penerbit Buku' => '',
                'Tempat Terbit' => '',
                'Tahun Terbit' => '',
                'Asal Buku' => '',
                'Sinopsis' => '',
                'Harga Buku' => '',
                'Stok Buku' => '',
                'Lokasi Lemari' => '',
                'Lokasi Rak' => '',
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'DDC',
            'Kategori Buku',
            'Kondisi Buku',
            'Jenis Buku',
            'Singkatan Buku',
            'ISBN',
            'Judul Buku',
            'Penulis Buku',
            'Penerbit Buku',
            'Tempat Terbit',
            'Tahun Terbit',
            'Asal Buku',
            'Sinopsis',
            'Harga Buku',
            'Stok Buku',
            'Lokasi Lemari',
            'Lokasi Rak',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $row_count = 50;

                // DDC Validation (Column A)
                $ddcValidation = $sheet->getCell('A2')->getDataValidation();
                $ddcValidation->setType(DataValidation::TYPE_LIST);
                $ddcValidation->setFormula1('"' . implode(',', $this->ddcs->pluck('nama_klasifikasi')->toArray()) . '"');
                $ddcValidation->setShowDropDown(true);
                for ($i = 2; $i <= $row_count; $i++) {
                    $sheet->getCell("A{$i}")->setDataValidation(clone $ddcValidation);
                }

                // Kategori Buku Validation (Column B)
                $kategoriValidation = $sheet->getCell('B2')->getDataValidation();
                $kategoriValidation->setType(DataValidation::TYPE_LIST);
                $kategoriValidation->setFormula1('"' . implode(',', $this->kategoris->pluck('nama_kategori')->toArray()) . '"');
                $kategoriValidation->setShowDropDown(true);
                for ($i = 2; $i <= $row_count; $i++) {
                    $sheet->getCell("B{$i}")->setDataValidation(clone $kategoriValidation);
                }

                // Kondisi Buku Validation (Column C)
                $kondisiValidation = $sheet->getCell('C2')->getDataValidation();
                $kondisiValidation->setType(DataValidation::TYPE_LIST);
                $kondisiValidation->setFormula1('"' . implode(',', $this->kondisis->pluck('nama_kondisi')->toArray()) . '"');
                $kondisiValidation->setShowDropDown(true);
                for ($i = 2; $i <= $row_count; $i++) {
                    $sheet->getCell("C{$i}")->setDataValidation(clone $kondisiValidation);
                }

                // Jenis Buku Validation (Column D)
                $jenisValidation = $sheet->getCell('D2')->getDataValidation();
                $jenisValidation->setType(DataValidation::TYPE_LIST);
                $jenisValidation->setFormula1('"' . implode(',', $this->jeniss->pluck('nama_jenis')->toArray()) . '"');
                $jenisValidation->setShowDropDown(true);
                for ($i = 2; $i <= $row_count; $i++) {
                    $sheet->getCell("D{$i}")->setDataValidation(clone $jenisValidation);
                }

                // Tahun Terbit Validation (Column J, index 10)
                $tahunValidation = $sheet->getCell('J2')->getDataValidation();
                $tahunValidation->setType(DataValidation::TYPE_WHOLE);
                $tahunValidation->setErrorStyle(DataValidation::STYLE_STOP);
                $tahunValidation->setAllowBlank(true);
                $tahunValidation->setErrorTitle('Input Error');
                $tahunValidation->setError('Tahun Terbit harus berupa angka!');
                $tahunValidation->setPromptTitle('Hanya Angka');
                $tahunValidation->setPrompt('Masukkan hanya angka untuk tahun (contoh: 2020).');
                for ($i = 2; $i <= $row_count; $i++) {
                    $sheet->getCell("J{$i}")->setDataValidation(clone $tahunValidation);
                }

                // Harga Buku Validation (Column N, index 13)
                $hargaValidation = $sheet->getCell('N2')->getDataValidation();
                $hargaValidation->setType(DataValidation::TYPE_WHOLE);
                $hargaValidation->setErrorStyle(DataValidation::STYLE_STOP);
                $hargaValidation->setAllowBlank(true);
                $hargaValidation->setErrorTitle('Input Error');
                $hargaValidation->setError('Harga Buku harus berupa angka tanpa titik atau karakter lain!');
                $hargaValidation->setPromptTitle('Hanya Angka');
                $hargaValidation->setPrompt('Masukkan hanya angka untuk harga (contoh: 1000).');
                for ($i = 2; $i <= $row_count; $i++) {
                    $sheet->getCell("N{$i}")->setDataValidation(clone $hargaValidation);
                }

                // Stok Buku Validation (Column O, index 14)
                $stokValidation = $sheet->getCell('O2')->getDataValidation();
                $stokValidation->setType(DataValidation::TYPE_WHOLE);
                $stokValidation->setErrorStyle(DataValidation::STYLE_STOP);
                $stokValidation->setAllowBlank(true);
                $stokValidation->setErrorTitle('Input Error');
                $stokValidation->setError('Stok Buku harus berupa angka tanpa titik atau karakter lain!');
                $stokValidation->setPromptTitle('Hanya Angka');
                $stokValidation->setPrompt('Masukkan hanya angka untuk stok (contoh: 50).');
                for ($i = 2; $i <= $row_count; $i++) {
                    $sheet->getCell("O{$i}")->setDataValidation(clone $stokValidation);
                }

                // Set format to text for all columns
                $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q'];
                foreach ($columns as $column) {
                    for ($i = 2; $i <= $row_count; $i++) {
                        $sheet->getStyle("{$column}{$i}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                    }
                }
            }
        ];
    }
}