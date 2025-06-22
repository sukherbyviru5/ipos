<?php

namespace App\Http\Controllers\Admin\Laporan;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\TransaksiKeuangan;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiKeuanganExport;
use App\Models\SettingApp;

class TransaksiKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->input('year', Carbon::now()->year);
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $years = range(2023, Carbon::now()->year);
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $yearStats = TransaksiKeuangan::whereYear('tanggal', $selectedYear)
            ->selectRaw("
                SUM(CASE WHEN type = 'kredit' AND sumber = 'kas' THEN nominal ELSE 0 END) as kredit,
                SUM(CASE WHEN type = 'debit' THEN nominal ELSE 0 END) as debit,
                SUM(CASE WHEN type = 'kredit' AND sumber = 'denda' THEN nominal ELSE 0 END) as denda,
                SUM(CASE WHEN type = 'kredit' THEN nominal ELSE 0 END) - SUM(CASE WHEN type = 'debit' THEN nominal ELSE 0 END) as saldo
            ")
            ->first();

        $monthStats = TransaksiKeuangan::whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', $selectedMonth)
            ->selectRaw("
                SUM(CASE WHEN type = 'kredit' THEN nominal ELSE 0 END) as month_kredit,
                SUM(CASE WHEN type = 'debit' THEN nominal ELSE 0 END) as month_debit,
                SUM(CASE WHEN type = 'kredit' THEN nominal ELSE 0 END) - SUM(CASE WHEN type = 'debit' THEN nominal ELSE 0 END) as month_saldo
            ")
            ->first();

        $stats = [
            'kredit' => $yearStats->kredit ?? 0,
            'debit' => $yearStats->debit ?? 0,
            'denda' => $yearStats->denda ?? 0,
            'saldo' => $yearStats->saldo ?? 0,
            'month_kredit' => $monthStats->month_kredit ?? 0,
            'month_debit' => $monthStats->month_debit ?? 0,
            'month_saldo' => $monthStats->month_saldo ?? 0,
        ];

        return view('admin.laporan.transaksi_keuangan.index', compact(
            'years',
            'months',
            'selectedYear',
            'selectedMonth',
            'stats'
        ))->with('sb', "Data Transaksi Keuangan");
    }

    public function create(Request $request)
    {
        $request->validate([
            'uraian' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'type' => 'required|in:debit,kredit',
            'sumber' => 'required|in:kas,denda',
            'tanggal' => 'required|date'
        ]);

        TransaksiKeuangan::create([
            'uraian' => $request->uraian,
            'nominal' => $request->nominal,
            'type' => $request->type,
            'sumber' => $request->sumber,
            'tanggal' => $request->tanggal,
        ]);

        return redirect(url('admin/laporan/transaksi-keuangan/'))->with('message', "Data transaksi keuangan berhasil dibuat");
    }

    public function getall(Request $request)
    {
        $query = TransaksiKeuangan::select('id', 'uraian', 'nominal', 'type', 'sumber', 'tanggal');

        if ($request->year) {
            $query->whereYear('tanggal', $request->year);
        }
        if ($request->month) {
            $query->whereMonth('tanggal', $request->month);
        }

        $query->orderBy('tanggal', 'DESC');

        return DataTables::of($query)
            ->editColumn('nominal', function (TransaksiKeuangan $transaksi) {
                return 'Rp ' . number_format($transaksi->nominal, 0, ',', '.');
            })
            ->editColumn('tanggal', function (TransaksiKeuangan $transaksi) {
                return Carbon::parse($transaksi->tanggal)->locale('id')->isoFormat('D MMMM Y');
            })
            ->editColumn('type', function (TransaksiKeuangan $transaksi) {
                $badgeClass = $transaksi->type === 'debit' ? 'badge-danger' : 'badge-success';
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($transaksi->type) . '</span>';
            })
            ->addColumn('action', function (TransaksiKeuangan $transaksi) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $transaksi->id . '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' . $transaksi->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
                ';
            })
            ->rawColumns(['action', 'type'])
            ->make(true);
    }

    public function get(Request $request)
    {
        return response()->json(
            TransaksiKeuangan::where('id', $request->id)->first(),
            200
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'uraian' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'type' => 'required|in:debit,kredit',
            'sumber' => 'required|in:kas,denda',
            'tanggal' => 'required|date'
        ]);

        TransaksiKeuangan::where('id', $request->id)->update([
            'uraian' => $request->uraian,
            'nominal' => $request->nominal,
            'type' => $request->type,
            'sumber' => $request->sumber,
            'tanggal' => $request->tanggal,
        ]);

        return redirect(url('admin/laporan/transaksi-keuangan/'))->with('message', "Data transaksi keuangan berhasil diupdate");
    }

    public function delete(Request $request)
    {
        TransaksiKeuangan::where('id', $request->id)->delete();
        return response()->json([
            'message' => "Transaksi keuangan berhasil dihapus"
        ], 200);
    }

    public function exportPdf(Request $request)
    {
       $query = TransaksiKeuangan::select('uraian', 'nominal', 'type', 'sumber', 'tanggal');

        if ($request->year) {
            $query->whereYear('tanggal', $request->year);
        }
        if ($request->month) {
            $query->whereMonth('tanggal', $request->month);
        }

        $transactions = $query->orderBy('tanggal', 'DESC')->get();
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $monthName = $request->month ? $months[$request->month] : 'Semua Bulan';
        $year = $request->year ?? 'Semua Tahun';
        $kop = SettingApp::first(); 
        $currentDate = Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i A');

        return view('admin.laporan.transaksi_keuangan.pdf', compact('transactions', 'monthName', 'year', 'kop', 'currentDate'));
    }

    public function exportExcel(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;
        $month = $request->month ?? Carbon::now()->month;
        return Excel::download(new TransaksiKeuanganExport($year, $month), 'transaksi_keuangan_' . $year . '_' . $month . '.xlsx');
    }
}