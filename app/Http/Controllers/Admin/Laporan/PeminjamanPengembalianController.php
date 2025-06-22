<?php

namespace App\Http\Controllers\Admin\Laporan;

use Carbon\Carbon;
use App\Models\Denda;
use App\Models\SettingApp;
use Illuminate\Http\Request;
use App\Models\PeminjamanSiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PeminjamanRekapExport;
use App\Exports\PeminjamanDetailExport;

class PeminjamanPengembalianController extends Controller
{
    public function index(Request $request)
    {
        $years = range(2020, Carbon::now()->year);
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $selectedYearStart = $request->year_start ?? Carbon::now()->year;
        $selectedMonthStart = $request->month_start ?? 1;
        $selectedYearEnd = $request->year_end ?? Carbon::now()->year;
        $selectedMonthEnd = $request->month_end ?? 12;

        $startDate = Carbon::create($selectedYearStart, $selectedMonthStart, 1);
        $endDate = Carbon::create($selectedYearEnd, $selectedMonthEnd)->endOfMonth();

        $rekapitulasi = [];
        for ($month = 1; $month <= 12; $month++) {
            $year = $selectedYearStart;
            if ($month > $selectedMonthStart && $year == $selectedYearStart) continue;
            if ($year > $selectedYearEnd || ($year == $selectedYearEnd && $month > $selectedMonthEnd)) break;

            $monthStart = Carbon::create($year, $month, 1);
            $monthEnd = $monthStart->copy()->endOfMonth();

            $peminjaman = PeminjamanSiswa::whereBetween('tanggal_pinjam', [$monthStart, $monthEnd])->count();
            $pengembalian = PeminjamanSiswa::whereBetween('tanggal_kembali', [$monthStart, $monthEnd])
                ->whereNotNull('tanggal_kembali')->count();

            $rekapitulasi[$month] = [
                'peminjaman' => $peminjaman,
                'pengembalian' => $pengembalian,
            ];
        }

        $details = PeminjamanSiswa::with(['siswa','denda'])
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->orderBy('tanggal_pinjam', 'asc')
            ->get();

        return view('admin.laporan.peminjaman-pengembalian.index', compact(
            'years', 'months', 'selectedYearStart', 'selectedMonthStart',
            'selectedYearEnd', 'selectedMonthEnd', 'rekapitulasi', 'details'
        ))->with('sb', 'Laporan Peminjaman/Pengembalian');
    }

    public function exportPdf(Request $request)
    {
        $yearStart = $request->year_start ?? Carbon::now()->year;
        $monthStart = $request->month_start ?? 1;
        $yearEnd = $request->year_end ?? Carbon::now()->year;
        $monthEnd = $request->month_end ?? 12;
        $type = $request->type ?? 'detail';

        $startDate = Carbon::create($yearStart, $monthStart, 1);
        $endDate = Carbon::create($yearEnd, $monthEnd)->endOfMonth();
        $currentDate = Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y');
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        if ($type == 'rekap') {
            $rekapitulasi = [];
            for ($month = 1; $month <= 12; $month++) {
                $year = $yearStart;
                if ($month > $monthStart && $year == $yearStart) continue;
                if ($year > $yearEnd || ($year == $yearEnd && $month > $monthEnd)) break;

                $monthStartDate = Carbon::create($year, $month, 1);
                $monthEndDate = $monthStartDate->copy()->endOfMonth();

                $peminjaman = PeminjamanSiswa::whereBetween('tanggal_pinjam', [$monthStartDate, $monthEndDate])->count();
                $pengembalian = PeminjamanSiswa::whereBetween('tanggal_kembali', [$monthStartDate, $monthEndDate])
                    ->whereNotNull('tanggal_kembali')->count();

                $rekapitulasi[$month] = [
                    'peminjaman' => $peminjaman,
                    'pengembalian' => $pengembalian,
                ];
            }

            $kop = SettingApp::first(); 

            return view('admin.laporan.peminjaman-pengembalian.rekap-pdf', compact('rekapitulasi', 'yearStart', 'yearEnd', 'currentDate', 'months', 'kop', 'monthStart', 'monthEnd'));
        } else {
            $kop = SettingApp::first(); 
            $details = PeminjamanSiswa::with(['siswa', 'qrBuku.buku', 'denda'])
                ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
                ->orderBy('tanggal_pinjam', 'asc')
                ->get();

            return view('admin.laporan.peminjaman-pengembalian.detail-pdf', compact('details', 'yearStart', 'monthStart', 'yearEnd', 'monthEnd', 'currentDate', 'kop', 'months'));
        }
    }

   public function exportExcel(Request $request)
    {
        $yearStart = $request->year_start ?? Carbon::now()->year;
        $monthStart = $request->month_start ?? 1;
        $yearEnd = $request->year_end ?? Carbon::now()->year;
        $monthEnd = $request->month_end ?? 12;
        $type = $request->type ?? 'detail';

        if ($type == 'rekap') {
            return Excel::download(new PeminjamanRekapExport($yearStart, $monthStart, $yearEnd, $monthEnd), 'rekap_peminjaman_' . $yearStart . '_' . $yearEnd . '.xlsx');
        } else {
            return Excel::download(new PeminjamanDetailExport($yearStart, $monthStart, $yearEnd, $monthEnd), 'detail_peminjaman_' . $yearStart . '_' . $monthStart . '_to_' . $yearEnd . '_monthEnd' . '.xlsx');
        }
    }
}
?>