<?php

namespace App\Exports;

use App\Models\LogAktivitasSiswa;
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

class LogSiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $nik_siswa, $date_start, $date_end, $kop;

    public function __construct($nik_siswa, $date_start, $date_end)
    {
        $this->nik_siswa = $nik_siswa;
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->kop = SettingApp::first();
    }

    public function collection()
    {
        $query = LogAktivitasSiswa::with(['siswa', 'buku'])
            ->when($this->nik_siswa, function ($query) {
                return $query->where('nik_siswa', $this->nik_siswa);
            })
            ->when($this->date_start && $this->date_end, function ($query) {
                return $query->whereBetween('created_at', [
                    Carbon::parse($this->date_start)->startOfDay(),
                    Carbon::parse($this->date_end)->endOfDay(),
                ]);
            })
            ->orderBy('created_at', 'desc');

        return $query->get();
    }

    public function map($log): array
    {
        static $index = 0;
        $index++;

        return [
            $index . '.',
            $log->siswa->nama_siswa ?? '-',
            $log->buku->judul_buku ?? '-',
            $log->aktivitas ?? '-',
            Carbon::parse($log->created_at)->format('d/m/Y H:i'),
        ];
    }

    public function headings(): array
    {
        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        $yearDisplay = Carbon::parse($this->date_start)->year == Carbon::parse($this->date_end)->year
            ? Carbon::parse($this->date_end)->year
            : Carbon::parse($this->date_start)->year . '-' . Carbon::parse($this->date_end)->year;
        $dateStartDisplay = strtoupper(Carbon::parse($this->date_start)->isoFormat('DD MMMM Y'));
        $dateEndDisplay = strtoupper(Carbon::parse($this->date_end)->isoFormat('DD MMMM Y'));

        return [
            [$this->kop->nama_instansi ?? 'Nama Instansi'],
            [$this->kop->nama_sub_instansi ?? 'Nama Sub Instansi'],
            [$this->kop->nama_madrasah ?? 'Nama Madrasah'],
            [$this->kop->alamat_madrasah ?? 'Alamat Madrasah'],
            [''],
            ['LAPORAN AKTIVITAS SISWA'],
            [$this->kop->nama_madrasah ?? 'Nama Madrasah'],
            ['TANGGAL ' . $dateStartDisplay . ' SAMPAI DENGAN ' . $dateEndDisplay],
            ['TAHUN ' . $yearDisplay],
            [''],
            ['No', 'Nama Siswa', 'Judul Buku', 'Aktivitas', 'Tanggal'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        $sheet->mergeCells('A4:E4');
        $sheet->mergeCells('A6:E6');
        $sheet->mergeCells('A7:E7');
        $sheet->mergeCells('A8:E8');
        $sheet->getStyle('A1:E8')->applyFromArray([
            'font' => ['name' => 'Times New Roman', 'bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getStyle('A10:E10')->applyFromArray([
            'font' => ['name' => 'Times New Roman', 'bold' => true, 'size' => 11],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF2F2F2']],
        ]);

        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A11:E' . $highestRow)->applyFromArray([
            'font' => ['name' => 'Times New Roman', 'size' => 11],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(15);

        return [];
    }
}
?>