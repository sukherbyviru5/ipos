<?php

namespace App\Exports;

use App\Models\Siswa;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;

class SiswaExport implements FromView, WithColumnWidths, WithStyles, WithColumnFormatting, WithEvents
{
    private $mapel_id, $jadwal_id, $kelas_id;

    public function __construct($mapel_id, $jadwal_id, $kelas_id)
    {
        $this->mapel_id = $mapel_id;
        $this->jadwal_id = $jadwal_id;
        $this->kelas_id = $kelas_id;
    }

    public function columnWidths(): array
    {

        return [
            'A' => 5.0,
            'B' => 10.0,
            'C' => 15.0,
            'D' => 30.0,
            'E' => 15.0,
        ];
    }

    public function styles(Worksheet $sheet)
    {

        return [
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT
        ];
    }

    public function registerEvents(): array
    {
        return [];
    }

    public function view(): View
    {
        $data = [
            'siswa' => Siswa::where('kelas_id', $this->kelas_id)->orderBy('nis', "ASC")->get(),
            'mapel_id' => $this->mapel_id,
            'jadwal_id' => $this->jadwal_id
        ];

        return view('report.excel.nilai_excel', $data);
    }
}
