<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use App\Models\Denda;
use App\Models\Guru;
use App\Models\QrBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PeminjamanGuru;
use App\Models\SettingPeminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class PengembalianGuruController extends Controller
{
    public function index()
    {
        return view('admin.peminjaman.pengembalian_guru.index')->with('sb', 'Pengembalian Guru');
    }

    public function getall(Request $request)
    {
     
        $query = PeminjamanGuru::with(['guru', 'qrBuku.buku'])
            ->select('peminjaman_guru.*')
            ->whereIn('status_peminjaman', ['dipinjam', 'telat'])
            ->orderBy('tanggal_pinjam', 'DESC')
            ->get();

        foreach ($query as $item) {
            $item->tgl_pinjam = Carbon::parse($item->tanggal_pinjam)->locale('id')->isoFormat('D MMMM YYYY');
            $item->tgl_jatuh_tempo = Carbon::parse($item->tanggal_jatuh_tempo)->locale('id')->isoFormat('D MMMM YYYY');
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('buku', function (PeminjamanGuru $peminjaman) {
                return '<a href="' . url('/admin/data-buku/detail/' . $peminjaman->qrBuku->buku->id . '?u=' . $peminjaman->qrBuku->no_urut) . '" target="_blank">' . e($peminjaman->qrBuku->kode ?? '-') . '</a>';
            })
            ->addColumn('status_peminjaman', function (PeminjamanGuru $peminjaman) {
                $badgeClass = 'badge-secondary';
                $status = $peminjaman->status_peminjaman;

                if ($status == 'dipinjam') {
                    $badgeClass = 'badge-success';
                } elseif ($status == 'telat') {
                    $badgeClass = 'badge-danger';
                }

                return '<span class="badge ' . $badgeClass . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('action', function (PeminjamanGuru $peminjaman) {
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

    public function detailPeminjamanGuru($nik)
    {
        $guru = Guru::where('nik', $nik)->firstOrFail();
        $peminjaman = PeminjamanGuru::with(['qrBuku.buku'])
            ->where('nik_guru', $nik)
            ->whereIn('status_peminjaman', ['dipinjam', 'telat'])
            ->get();

        return view('admin.peminjaman.pengembalian_guru.detail', compact('guru', 'peminjaman'))->with('sb', 'Pengembalian Guru');
    }

    public function kembalikanBuku(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman_guru,id',
            'tanggal_kembali' => 'required|date',
            'status_peminjaman' => 'required|in:dikembalikan',
        ]);

        $peminjaman = PeminjamanGuru::findOrFail($request->peminjaman_id);

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


        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dikembalikan.',
        ]);
    }

    public function checkBuku(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'nik_guru' => 'required|string',
        ]);

        $peminjaman = PeminjamanGuru::with(['qrBuku.buku'])
            ->where('nik_guru', $request->nik_guru)
            ->whereHas('qrBuku', function ($query) use ($request) {
                $query->where('kode', $request->code);
            })
            ->whereIn('status_peminjaman', ['dipinjam', 'telat'])
            ->first();

        if (!$peminjaman) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Buku tidak ditemukan atau tidak dipinjam oleh guru ini.',
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
        ]);
    }
}
