<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\SettingApp;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\AbsensiPengunjung;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PengunjungController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'month_start' => $request->month_start ?? 1,
            'month_end' => $request->month_end ?? 12,
            'year' => $request->year ?? now()->year,
        ];

        return view('admin.laporan.pengunjung.index', $data)->with('sb', 'Laporan Pengunjung');
    }

    public function cetak(Request $request)
    {
        $monthStart = $request->month_start ?? 1;
        $monthEnd = $request->month_end ?? 12;
        $year = $request->year ?? now()->year;
        $reportType = $request->report_type ?? 'harian_kelas';

        $startDate = Carbon::create($year, $monthStart, 1)->startOfMonth();
        $endDate = Carbon::create($year, $monthEnd, 1)->endOfMonth();

        $kop = SettingApp::first();

        $view = match ($reportType) {
            'harian_kelas' => 'admin.laporan.pengunjung.harian_kelas',
            'rekap_bulanan' => 'admin.laporan.pengunjung.rekap_bulanan',
            'harian_siswa_guru' => 'admin.laporan.pengunjung.harian_siswa_guru',
            'grafik_pengunjung' => 'admin.laporan.pengunjung.grafik_pengunjung',
            default => abort(404, 'Tipe laporan tidak ditemukan.'),
        };

        // ==== GRAFIK ====
        if ($reportType === 'grafik_pengunjung') {
            $labels = [];
            $kelasList = Kelas::orderBy('tingkat_kelas')->get();
            $dataKelas = [];

            for ($m = $monthStart; $m <= $monthEnd; $m++) {
                $labels[] = Carbon::create()->month($m)->translatedFormat('F');
            }

            foreach ($kelasList as $kelas) {
                $namaKelas = $kelas->tingkat_kelas . ' ' . ($kelas->kelompok ?? '') . ' (' . $kelas->urusan_kelas . ')' . ' (Jurusan ' . $kelas->jurusan . ')';

                $dataKelas[$namaKelas] = [];

                for ($m = $monthStart; $m <= $monthEnd; $m++) {
                    $jumlah = AbsensiPengunjung::where('is_kunjungan_kelas', true)->where('kelas_id', $kelas->id)->whereYear('created_at', $year)->whereMonth('created_at', $m)->count();

                    $dataKelas[$namaKelas][] = $jumlah;
                }
            }

            return view($view, compact('labels', 'dataKelas', 'monthStart', 'monthEnd', 'year', 'kop', 'reportType'));
        }

        // ==== REKAP BULANAN KELAS ====
        if ($reportType === 'rekap_bulanan') {
            $months = [];
            $dailyCounts = [];

            // Initialize array for all days (1-31) and months
            for ($day = 1; $day <= 31; $day++) {
                $dailyCounts[$day] = 0;
            }

            // Populate monthly data
            for ($m = $monthStart; $m <= $monthEnd; $m++) {
                $monthName = Carbon::create()->month($m)->translatedFormat('F');
                $months[$monthName] = [];

                for ($day = 1; $day <= 31; $day++) {
                    $date = Carbon::create($year, $m, $day)->startOfDay();
                    $count = AbsensiPengunjung::whereIn('is_kunjungan_kelas', [false, true])->whereDate('created_at', $date)->count();

                    $months[$monthName][$day] = $count;
                    $dailyCounts[$day] += $count; 
                }
            }

            // Calculate total visits per month
            $monthlyTotals = array_map(function ($monthData) {
                return array_sum($monthData);
            }, $months);

            // Calculate grand total
            $grandTotal = array_sum($dailyCounts);

            return view($view, compact('months', 'dailyCounts', 'monthlyTotals', 'grandTotal', 'monthStart', 'monthEnd', 'year', 'kop', 'reportType'));
        }

        // ==== LAPORAN BIASA ====
        $query = AbsensiPengunjung::query()->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);

        if ($reportType === 'harian_kelas') {
            $query->where('is_kunjungan_kelas', true);
        } elseif ($reportType === 'harian_siswa_guru') {
            $query->where('is_kunjungan_kelas', false);
        }

        $data = $query
            ->with(['kelas', 'guru', 'siswaNik', 'guruNik'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view($view, compact('data', 'reportType', 'monthStart', 'monthEnd', 'year', 'kop'));
    }
}
