<?php

namespace App\Exports;

use App\Models\TransaksiKeuangan;
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

class TransaksiKeuanganExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $year;
    protected $month;
    protected $monthName;
    protected $kop;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $this->monthName = $month ? $months[$month] : 'Semua Bulan';
        $this->year = $year ?? 'Semua Tahun';
        $this->kop = SettingApp::first();
    }

    public function collection()
    {
        $query = TransaksiKeuangan::select('uraian', 'nominal', 'type', 'sumber', 'tanggal');

        if ($this->year) {
            $query->whereYear('tanggal', $this->year);
        }
        if ($this->month) {
            $query->whereMonth('tanggal', $this->month);
        }

        $transactions = $query->orderBy('tanggal', 'DESC')->get();

        $debitTotal = $transactions->where('type', 'debit')->sum('nominal');
        $creditTotal = $transactions->where('type', 'kredit')->sum('nominal');
        $balance = $debitTotal - $creditTotal;

        $summary = collect([
            (object) [
                'tanggal' => null,
                'uraian' => 'Jumlah',
                'nominal' => $debitTotal,
                'type' => 'debit',
                'is_summary' => true,
                'is_balance' => false,
            ],
            (object) [
                'tanggal' => null,
                'uraian' => 'Jumlah',
                'nominal' => $creditTotal,
                'type' => 'kredit',
                'is_summary' => true,
                'is_balance' => false,
            ],
            (object) [
                'tanggal' => null,
                'uraian' => 'Saldo',
                'nominal' => $balance,
                'type' => 'balance',
                'is_summary' => true,
                'is_balance' => true,
            ],
        ]);

        return $transactions->concat($summary);
    }

    public function headings(): array
    {
        return [
            [$this->kop->nama_instansi ?? 'Nama Instansi'],
            [$this->kop->nama_sub_instansi ?? 'Nama Sub Instansi'],
            [$this->kop->nama_madrasah ?? 'Nama Madrasah'],
            [$this->kop->alamat_madrasah ?? 'Alamat Madrasah'],
            [''],
            ['LAPORAN TRANSAKSI KEUANGAN PERPUSTAKAAN'],
            [$this->kop->nama_madrasah ?? 'Nama Madrasah'],
            ['Bulan: ' . $this->monthName . ' ' . $this->year],
            [''],
            ['No', 'Tanggal', 'Uraian', 'Debit', 'Kredit'],
        ];
    }

    public function map($transaction): array
    {
        if (isset($transaction->is_summary) && $transaction->is_summary) {
            if ($transaction->is_balance) {
                return [
                    '', 
                    '', 
                    $transaction->uraian,
                    '', 
                    'Rp ' . number_format($transaction->nominal, 0, ',', '.'), 
                ];
            }
            return [
                '', 
                '', 
                $transaction->uraian,
                $transaction->type === 'debit' ? 'Rp ' . number_format($transaction->nominal, 0, ',', '.') : '',
                $transaction->type === 'kredit' ? 'Rp ' . number_format($transaction->nominal, 0, ',', '.') : '',
            ];
        }

        static $index = 0;
        $index++;
        return [
            $index . '.', 
            $transaction->tanggal ? Carbon::parse($transaction->tanggal)->format('d/m/Y') : '', 
            $transaction->uraian,
            $transaction->type === 'debit' ? 'Rp ' . number_format($transaction->nominal, 0, ',', '.') : '-',
            $transaction->type === 'kredit' ? 'Rp ' . number_format($transaction->nominal, 0, ',', '.') : '-',
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

        $sheet->getStyle('A10:E10')->applyFromArray([
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
        $sheet->getStyle('A10:E' . $highestRow)->applyFromArray([
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

        $sheet->getStyle('D10:E' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $summaryStartRow = $highestRow - 2;
        $sheet->getStyle('A' . $summaryStartRow . ':E' . $highestRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);

        return [];
    }
}
?>