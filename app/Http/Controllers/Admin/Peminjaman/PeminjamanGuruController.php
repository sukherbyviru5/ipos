<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use App\Models\Guru;
use App\Models\QrBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PeminjamanGuru;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\SettingPeminjaman;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class PeminjamanGuruController extends Controller
{
    public function index()
    {
        return view('admin.peminjaman.peminjaman_guru.index')->with('sb', "Peminjaman Guru");
    }

    public function create()
    {
        return view('admin.peminjaman.peminjaman_guru.create')->with('sb', "Peminjaman Guru");
    }

    public function detail($id)
    {
        $peminjaman = PeminjamanGuru::find($id);
        return view('admin.peminjaman.peminjaman_guru.detail', compact('peminjaman'))->with('sb', "Peminjaman Guru");
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'nik_guru' => 'required|string|exists:guru,nik',
            'buku' => 'required|array|min:1',
            'buku.*' => 'integer|exists:qr_buku,id',
            'tanggal_pinjam' => 'required|date',
            'status_peminjaman' => 'required|in:dipinjam,dikembalikan,telat',
            '_token' => 'required|string',
        ]);

        $cacheKey = 'peminjaman_guru_' . md5(json_encode($request->only(['nik_guru', 'buku', 'tanggal_pinjam'])));
        if (Cache::has($cacheKey)) {
            return response()->json([
                'error' => 'Peminjaman sudah diproses sebelumnya.'
            ], 409);
        }

        try {
            DB::beginTransaction();

            $setting = SettingPeminjaman::first();
            if (!$setting) {
                throw new \Exception('Maaf, setting peminjaman belum ditentukan.');
            }

            $grup = uniqid('PG-'); 
            foreach ($request->buku as $id_qr) {
                $peminjaman = PeminjamanGuru::create([
                    'nik_guru' => $request->nik_guru,
                    'id_qr' => $id_qr,
                    'kode' => uniqid('PP-'), 
                    'grup' => $grup,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_jatuh_tempo' => null,
                    'status_peminjaman' => $request->status_peminjaman,
                ]);
            }

            Cache::put($cacheKey, true, now()->addMinutes(5));

            DB::commit();

            return response()->json([
                'message' => 'Peminjaman Guru berhasil ditambahkan.',
                'grup' => $grup 
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing peminjaman guru: ' . $e->getMessage());

            return response()->json([
                'error' => 'Gagal menyimpan peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }


    public function checkGuru(Request $request)
    {
        $code = $request->code;
        Log::info('Checking code: ' . $code);

        $guru = Guru::where('nik', $code)->first();
        if ($guru) {
            $data = [
                'id' => $guru->id,
                'nik' => $guru->nik,
                'nama_guru' => $guru->nama_guru,
                'nama_mata_pelajaran' => $guru->nama_mata_pelajaran,
            ];
            Log::info('Found guru: ' . json_encode([$data]));
            return response()->json(['type' => 'guru', 'data' => $data]);
        }
        return response()->json(['error' => 'Kode tidak ditemukan'], 404);
    }

    public function checkBuku(Request $request)
    {
        $code = $request->code;
        Log::info('Checking code: ' . $code);

        $qr = QrBuku::where('kode', $code)->first();

        if ($qr && $qr->buku) {

            $peminjaman = PeminjamanGuru::where('id_qr', $qr->id)->where('status_peminjaman', 'dipinjam')->first();
            if($peminjaman) {
                return response()->json(['error' => 'Buku ' . $qr->kode . ' masih berstatus dipinjam'], 404);
            }


            $data = [
                'id' => $qr->id,
                'judul_buku' => $qr->buku->judul_buku,
                'kode' => $qr->kode,
            ];

            if ($qr->buku->stok_buku <= 0) {
                Log::info('Buku tidak tersedia: ' . json_encode($data));
                return response()->json(['error' => 'Buku tidak tersedia'], 404);
            }

            Log::info('Found buku: ' . json_encode($data));
            return response()->json(['type' => 'buku', 'data' => $data]);
        }

        return response()->json(['error' => 'Kode tidak ditemukan'], 404);
    }



    public function getall(Request $request)
    {
        $query = PeminjamanGuru::with(['guru', 'qrBuku.buku']) 
            ->select('peminjaman_guru.*')
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
                $detailLink = '<li><a class="dropdown-item" href="/admin/peminjaman/peminjaman-guru/detail/' . $peminjaman->id . '">Detail</a></li>';

                if ($peminjaman->status_peminjaman === 'dikembalikan') {
                    $actions = $detailLink;
                } else {
                    $hapusLink = '<li><a data-id="' . $peminjaman->id . '" class="dropdown-item hapus" href="#">Batal / Hapus Data</a></li>';
                    $actions = $hapusLink . $detailLink;
                }

                return '
                    <div class="dropdown d-inline dropleft">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu">
                            ' . $actions . '
                        </ul>
                    </div>
                ';
            })

            ->rawColumns(['action', 'status_peminjaman', 'buku'])
            ->make(true);
    }


    public function result(Request $request)
    {
        $peminjamans = PeminjamanGuru::with(['guru', 'qrBuku'])->where('grup', $request->get('grup'))->get();
        return view('admin.peminjaman.peminjaman_guru.result', [
            'peminjamans' => $peminjamans
        ]);
    }

    public function delete(Request $request)
    {
        PeminjamanGuru::where('id', $request->id)->delete();
        return response()->json([
            'message' => "Peminjaman Guru berhasil dihapus"
        ], 200);
    }
}