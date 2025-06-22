<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AbsensiPengunjung;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Buku;
use App\Models\PeminjamanSiswa;
use App\Models\LogAktivitasSiswa;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $data = [
            'today' => Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->isoFormat('dddd, D MMMM Y'),
            'anggotaCount' => Siswa::count(),
            'bukuCount' => Buku::count(),
            'peminjamanCount' => PeminjamanSiswa::whereIn('status_peminjaman', ['dipinjam', 'telat'])->count(),
            'pengembalianCount' => PeminjamanSiswa::where('status_peminjaman', 'dikembalikan')->count(),
            'pengunjungHariIni' => AbsensiPengunjung::whereDate('created_at', $today)->count(),
            'peminjamHariIni' => PeminjamanSiswa::whereDate('tanggal_pinjam', $today)->count(),
            'tempoHariIni' => PeminjamanSiswa::where('tanggal_jatuh_tempo', $today)->where('status_peminjaman', 'dipinjam')->count(),
            'terlambatCount' => PeminjamanSiswa::where('status_peminjaman', 'telat')->count(),
            'bukuTerbaru' => Buku::orderBy('created_at', 'desc')->take(5)->get(),
            'logSiswa' => LogAktivitasSiswa::with(['siswa', 'buku'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];

        // dd($data);

        return view('admin.dashboard.index', $data)->with('sb', 'Dashboard');
    }
}
