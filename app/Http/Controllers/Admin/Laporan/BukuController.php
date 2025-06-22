<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Models\Buku;
use App\Models\SettingApp;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\BukuIndukExport;
use App\Models\TransaksiKeuangan;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiKeuanganExport;
use Yajra\DataTables\Facades\DataTables;

class BukuController extends Controller
{
    public function index()
    {
        $data = [
            'jumlah' => Buku::count(),
        ];
        return view('admin.laporan.buku.index', $data)->with('sb', 'Laporan Data Buku');
    }

    public function exportPdf(Request $request)
    {
        $buku = Buku::orderBy('id', 'desc')->get();
        $kop = SettingApp::first();
        $currentDate = Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i A');

        return view('admin.laporan.buku.pdf', compact('buku', 'kop', 'currentDate'));
    }

     public function exportExcel(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;
        $month = $request->month ?? Carbon::now()->month;
        return Excel::download(new BukuIndukExport(), 'buku_induk_' . $year . '_' . $month . '.xlsx');
    }
}
