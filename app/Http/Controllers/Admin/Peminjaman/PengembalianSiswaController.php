<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use App\Models\Denda;
use App\Models\Siswa;
use App\Models\QrBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PeminjamanSiswa;
use App\Models\SettingPeminjaman;
use App\Models\TransaksiKeuangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class PengembalianSiswaController extends Controller
{
    public function index()
    {
        return view('admin.peminjaman.pengembalian_siswa.index')->with('sb', 'Pengembalian Siswa');
    }

    public function create()
    {
        return view('admin.peminjaman.pengembalian_siswa.create')->with('sb', 'Pengembalian Siswa');
    }

    public function getall(Request $request)
    {
        PeminjamanSiswa::where('status_peminjaman', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', Carbon::today())
            ->update(['status_peminjaman' => 'telat']);

        $query = PeminjamanSiswa::with(['siswa', 'qrBuku.buku'])
            ->select('peminjaman_siswa.*')
            ->orderBy('tanggal_pinjam', 'DESC')
            ->whereIn('status_peminjaman', ['dipinjam', 'telat', 'bermasalah'])
            ->get();

        foreach ($query as $item) {
            $item->tgl_pinjam = Carbon::parse($item->tanggal_pinjam)->locale('id')->isoFormat('D MMMM YYYY');
            $item->tgl_jatuh_tempo = Carbon::parse($item->tanggal_jatuh_tempo)->locale('id')->isoFormat('D MMMM YYYY');
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('buku', function (PeminjamanSiswa $peminjaman) {
                return '<a href="' . url('/admin/data-buku/detail/' . $peminjaman->qrBuku->buku->id . '?u=' . $peminjaman->qrBuku->no_urut) . '" target="_blank">' . e($peminjaman->qrBuku->kode ?? '-') . '</a>';
            })
            ->addColumn('status_peminjaman', function (PeminjamanSiswa $peminjaman) {
                $badgeClass = 'badge-secondary';
                $status = $peminjaman->status_peminjaman;

                if ($status == 'dipinjam') {
                    $badgeClass = 'badge-success';
                } elseif ($status == 'telat') {
                    $badgeClass = 'badge-danger';
                }

                return '<span class="badge ' . $badgeClass . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('action', function (PeminjamanSiswa $peminjaman) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' .
                    $peminjaman->id .
                    '" class="dropdown-item hapus" href="#">Batal / Hapus Data</a></li>
                    </ul>
                </div>
                ';
            })
            ->rawColumns(['action', 'status_peminjaman', 'buku'])
            ->make(true);
    }

    public function detailPeminjamanSiswa($nik)
    {
        $siswa = Siswa::with('kelas')->where('nik', $nik)->firstOrFail();
        $peminjaman = PeminjamanSiswa::with(['qrBuku.buku', 'denda'])
            ->where('nik_siswa', $nik)
            ->whereIn('status_peminjaman', ['dipinjam', 'telat'])
            ->get();

        $setting = SettingPeminjaman::first();
        $dendaPerHari = $setting && $setting->denda_telat_status === 'aktif' ? $setting->denda_telat : 0;

        foreach ($peminjaman as $item) {
            $item->tgl_pinjam = Carbon::parse($item->tanggal_pinjam)->locale('id')->isoFormat('D MMMM YYYY');
            $item->tgl_jatuh_tempo = Carbon::parse($item->tanggal_jatuh_tempo)->locale('id')->isoFormat('D MMMM YYYY');
            $item->denda_total = 0;

            if ($item->status_peminjaman == 'telat' && $setting->denda_telat_status === 'aktif') {
                $denda = $item->denda ?: new Denda(['peminjaman_siswa_id' => $item->id]);
                $bookTitle = $item->qrBuku->buku->judul_buku;
                $dueDate = Carbon::parse($item->tanggal_jatuh_tempo);
                $today = Carbon::today();

                if ($today->gt($dueDate)) {
                    if ($setting->perhitungan_denda === 'per hari') {
                        $hariTelat = $today->diffInDays($dueDate);
                        $denda->jumlah_denda = $hariTelat * $dendaPerHari;
                        $denda->keterangan = "Denda keterlambatan pengembalian buku '$bookTitle' selama $hariTelat hari (Rp " . number_format($dendaPerHari, 0, ',', '.') . "/hari)";
                    } elseif ($setting->perhitungan_denda === 'per minggu') {
                        $mingguTelat = ceil($today->diffInDays($dueDate) / 7);
                        $denda->jumlah_denda = $mingguTelat * $dendaPerHari;
                        $denda->keterangan = "Denda keterlambatan pengembalian buku '$bookTitle' selama $mingguTelat minggu (Rp " . number_format($dendaPerHari, 0, ',', '.') . "/minggu)";
                    }

                    if (!$item->denda) {
                        $denda->save();
                    } else {
                        $denda->update([
                            'jumlah_denda' => $denda->jumlah_denda,
                            'keterangan' => $denda->keterangan
                        ]);
                    }

                    $item->denda_total = $denda->jumlah_denda;
                }
            }
        }

        return view('admin.peminjaman.pengembalian_siswa.detail', compact('siswa', 'peminjaman', 'setting'))->with('sb', 'Pengembalian Siswa');
    }

    public function kembalikanBuku(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman_siswa,id',
            'tanggal_kembali' => 'required|date',
            'status_peminjaman' => 'required|in:dikembalikan',
            'is_denda_paid' => 'nullable',
        ]);

        $peminjaman = PeminjamanSiswa::with('denda')->findOrFail($request->peminjaman_id);

        if ($peminjaman->status_peminjaman === 'dikembalikan') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Buku sudah dikembalikan.',
                ],
                400,
            );
        }

        $peminjaman->tanggal_kembali = $request->tanggal_kembali;
        $peminjaman->status_peminjaman = $request->status_peminjaman;
        $peminjaman->save();

        if ($peminjaman->denda && $peminjaman->denda->jumlah_denda > 0) {
            $peminjaman->denda->status_denda = $request->is_denda_paid ? 'lunas' : 'belum_lunas';
            if ($request->is_denda_paid) {
                $peminjaman->denda->tanggal_pembayaran = Carbon::today();
                TransaksiKeuangan::create([
                    'uraian' => $peminjaman->siswa->nama_siswa .' Telah Membayar '. $peminjaman->denda->keterangan,
                    'nominal' => $peminjaman->denda->jumlah_denda,
                    'type' => 'debit',
                    'sumber' => 'denda',
                    'tanggal' => Carbon::today(),
                ]);
            }
            $peminjaman->denda->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dikembalikan.',
        ]);
    }

    public function checkBuku(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'nik_siswa' => 'required|string',
        ]);

        $peminjaman = PeminjamanSiswa::with(['qrBuku.buku'])
            ->where('nik_siswa', $request->nik_siswa)
            ->whereHas('qrBuku', function ($query) use ($request) {
                $query->where('kode', $request->code);
            })
            ->whereIn('status_peminjaman', ['dipinjam', 'telat'])
            ->first();

        if (!$peminjaman) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Buku tidak ditemukan atau tidak dipinjam oleh siswa ini.',
                ],
                404,
            );
        }

        return response()->json([
            'success' => true,
            'data' => [
                'kode' => $peminjaman->qrBuku->kode,
            ],
            'book_id' => $peminjaman->qrBuku->buku->id,
            'book_title' => $peminjaman->qrBuku->buku->judul_buku,
            'peminjaman_id' => $peminjaman->id,
            'denda_total' => $peminjaman->denda ? $peminjaman->denda->jumlah_denda : 0,
            'status_denda' => $peminjaman->denda ? $peminjaman->denda->status_denda : 'lunas',
        ]);
    }
}
