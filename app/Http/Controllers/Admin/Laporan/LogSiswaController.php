<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Models\Siswa;
use App\Models\SettingApp;
use App\Models\LogAktivitasSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LogSiswaExport;
use Yajra\DataTables\Facades\DataTables;

class LogSiswaController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::orderBy('nama_siswa', 'asc')->get();
        $data = [
            'siswa' => $siswa,
            'selected_nik' => $request->nik_siswa,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
        ];
        return view('admin.laporan.log-siswa.index', $data)->with('sb', 'Laporan Aktivitas Siswa');
    }

    public function getall(Request $request)
    {
        $query = LogAktivitasSiswa::with(['siswa', 'buku'])
            ->when($request->nik_siswa, function ($query, $nik_siswa) {
                return $query->where('nik_siswa', $nik_siswa);
            })
            ->when($request->date_start && $request->date_end, function ($query) use ($request) {
                return $query->whereBetween('created_at', [Carbon::parse($request->date_start)->startOfDay(), Carbon::parse($request->date_end)->endOfDay()]);
            })
            ->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_siswa', fn($row) => $row->siswa->nama_siswa ?? '-')
            ->addColumn('judul_buku', fn($row) => $row->buku->judul_buku ?? '-')
            ->addColumn('aktivitas', fn($row) => $row->aktivitas ?? '-')
            ->addColumn('created_at', fn($row) => Carbon::parse($row->created_at)->format('d/m/Y H:i'))
            ->rawColumns(['nama_siswa', 'judul_buku', 'aktivitas', 'created_at'])
            ->make(true);
    }

    public function exportPdf(Request $request)
    {
        $query = LogAktivitasSiswa::with(['siswa', 'buku'])
            ->when($request->nik_siswa, function ($query, $nik_siswa) {
                return $query->where('nik_siswa', $nik_siswa);
            })
            ->when($request->date_start && $request->date_end, function ($query) use ($request) {
                return $query->whereBetween('created_at', [Carbon::parse($request->date_start)->startOfDay(), Carbon::parse($request->date_end)->endOfDay()]);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $kop = SettingApp::first();
        $currentDate = Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i');
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $nik_siswa = $request->nik_siswa;

        return view('admin.laporan.log-siswa.pdf', compact('query', 'kop', 'currentDate', 'date_start', 'date_end', 'nik_siswa'));
    }

    public function exportExcel(Request $request)
    {
        $nik_siswa = $request->nik_siswa;
        $date_start = $request->date_start ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $date_end = $request->date_end ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        return Excel::download(new LogSiswaExport($nik_siswa, $date_start, $date_end), 'log_siswa_' . $date_start . '_to_' . $date_end . '.xlsx');
    }
}
?>
