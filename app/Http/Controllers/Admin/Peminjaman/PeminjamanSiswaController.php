<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use App\Models\Siswa;
use App\Models\QrBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PeminjamanSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\SettingPeminjaman;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class PeminjamanSiswaController extends Controller
{
    public function index()
    {
        return view('admin.peminjaman.peminjaman_siswa.index')->with('sb', "Peminjaman Siswa");
    }

    public function create()
    {
        return view('admin.peminjaman.peminjaman_siswa.create')->with('sb', "Peminjaman Siswa");
    }

    public function detail($id)
    {
        $peminjaman = PeminjamanSiswa::find($id);
        return view('admin.peminjaman.peminjaman_siswa.detail', compact('peminjaman'))->with('sb', "Peminjaman Siswa");
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'nik_siswa' => 'required|string|exists:siswa,nik',
            'buku' => 'required|array|min:1',
            'buku.*' => 'integer|exists:qr_buku,id',
            'tanggal_pinjam' => 'required|date',
            'status_peminjaman' => 'required|in:dipinjam,dikembalikan,telat',
            '_token' => 'required|string',
        ]);

        $cacheKey = 'peminjaman_siswa_' . md5(json_encode($request->only(['nik_siswa', 'buku', 'tanggal_pinjam'])));
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
                $peminjaman = PeminjamanSiswa::create([
                    'nik_siswa' => $request->nik_siswa,
                    'id_qr' => $id_qr,
                    'kode' => uniqid('PS-'), 
                    'grup' => $grup,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_jatuh_tempo' => $setting->lama_peminjaman
                        ? Carbon::parse($request->tanggal_pinjam)->addDays($setting->lama_peminjaman)
                        : null,
                    'status_peminjaman' => $request->status_peminjaman,
                    'denda_total' => 0,
                ]);
            }

            Cache::put($cacheKey, true, now()->addMinutes(5));

            DB::commit();

            return response()->json([
                'message' => 'Peminjaman Siswa berhasil ditambahkan.',
                'grup' => $grup 
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing peminjaman siswa: ' . $e->getMessage());

            return response()->json([
                'error' => 'Gagal menyimpan peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }


    public function checkSiswa(Request $request)
    {
        $code = $request->code;
        Log::info('Checking code: ' . $code);

        $siswa = Siswa::where('nik', $code)->first();
        if ($siswa) {
            $data = [
                'id' => $siswa->id,
                'nik' => $siswa->nik,
                'nama_siswa' => $siswa->nama_siswa,
                'kelas' => $siswa->kelas->tingkat_kelas . ' ' . $siswa->kelas->kelompok . ' ( ' . $siswa->kelas->urusan_kelas . ' ) ( Jurusan ' . $siswa->kelas->jurusan . ' ) ',
            ];
            Log::info('Found siswa: ' . json_encode([$data]));
            return response()->json(['type' => 'siswa', 'data' => $data]);
        }
        return response()->json(['error' => 'Kode tidak ditemukan'], 404);
    }

    public function checkBuku(Request $request)
    {
        $code = $request->code;
        Log::info('Checking code: ' . $code);

        $qr = QrBuku::where('kode', $code)->first();

        $setting = SettingPeminjaman::first();
        if (!$setting) {
            return response()->json(['error' => 'Maaf Setting peminjaman belum ditentukan.'], 403);
        }
        if ($qr && $qr->buku) {
            if ($qr->buku->jenis_buku && $qr->buku->jenis_buku->nama_jenis != 'Buku Siswa') {
                Log::info('jenis Buku: ' . $qr->buku->jenis_buku->nama_jenis);
                return response()->json(['error' => 'Kode tidak ditemukan atau bukan buku siswa'], 404);
            }

            $peminjaman = PeminjamanSiswa::where('id_qr', $qr->id)->where('status_peminjaman', 'dipinjam')->first();
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
            return response()->json(['type' => 'buku', 'data' => $data, 'settings' => $setting]);
        }

        return response()->json(['error' => 'Kode tidak ditemukan'], 404);
    }



    public function getall(Request $request)
    {
        $query = PeminjamanSiswa::with(['siswa', 'qrBuku.buku']) 
            ->select('peminjaman_siswa.*')
            ->orderBy('tanggal_pinjam', 'DESC')
            ->get();

        foreach ($query as $item) {
            $item->tgl_pinjam = Carbon::parse($item->tanggal_pinjam)->locale('id')->isoFormat('D MMMM YYYY');
            $item->tgl_jatuh_tempo = Carbon::parse($item->tanggal_jatuh_tempo)->locale('id')->isoFormat('D MMMM YYYY');
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('buku', function (PeminjamanSiswa $peminjaman) {
                    return '<a href="' . url('/admin/data-buku/detail/' . $peminjaman->qrBuku->buku->id) . '" target="_blank">'
                        . e($peminjaman->qrBuku->kode ?? '-') . '</a>';
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
                $detailLink = '<li><a class="dropdown-item" href="/admin/peminjaman/peminjaman-siswa/detail/' . $peminjaman->id . '">Detail</a></li>';

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
        $peminjamans = PeminjamanSiswa::with(['siswa', 'qrBuku'])->where('grup', $request->get('grup'))->get();
        $setting = SettingPeminjaman::first();
        return view('admin.peminjaman.peminjaman_siswa.result', [
            'peminjamans' => $peminjamans,
            'setting'     => $setting
        ]);
    }

    public function delete(Request $request)
    {
        PeminjamanSiswa::where('id', $request->id)->delete();
        return response()->json([
            'message' => "Peminjaman Siswa berhasil dihapus"
        ], 200);
    }
}