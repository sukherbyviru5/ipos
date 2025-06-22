<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\QrBuku;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BukuRusakHilang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BukuRusakHilangController extends Controller
{
    public function index()
    {
        return view('admin.peminjaman.rusak_hilang.index')->with('sb', 'Buku Rusak/Hilang');
    }

    public function create()
    {
        $data = [
            'qrBuku' => QrBuku::orderBy('id', 'desc')->get(),
        ];
        return view('admin.peminjaman.rusak_hilang.create', $data)->with('sb', 'Buku Rusak/Hilang');
    }

    public function edit($id)
    {
        $data = [
            'qrBuku' => QrBuku::orderBy('id', 'desc')->get(),
            'edit' => BukuRusakHilang::find($id),
        ];
        return view('admin.peminjaman.rusak_hilang.edit', $data)->with('sb', 'Buku Rusak/Hilang');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik_siswa' => 'nullable|exists:siswa,nik',
            'nik_guru' => 'nullable|exists:guru,nik',
            'id_qr' => 'required|exists:qr_buku,id',
            'sanksi' => 'required|string|max:255',
            'status_buku' => 'required|in:rusak,hilang',
            'status_sanksi' => 'required|in:selesai,belum_selesai',
            'tanggal_laporan' => 'required|date',
        ]);

        if (BukuRusakHilang::where('id_qr', $request->id_qr)->exists()) {
            return redirect()->back()->with('message', 'Data Buku Rusak/Hilang sudah ada untuk QR ini');
        }

        BukuRusakHilang::create([
            'nik_siswa' => $request->nik_siswa,
            'nik_guru' => $request->nik_guru,
            'id_qr' => $request->id_qr,
            'sanksi' => $request->sanksi,
            'status_buku' => $request->status_buku,
            'status_sanksi' => $request->status_sanksi,
            'tanggal_laporan' => $request->tanggal_laporan,
        ]);

        return redirect()->back()->with('message', 'Data Buku Rusak/Hilang berhasil dibuat');
    }

    public function getall(Request $request)
    {
        $query = BukuRusakHilang::select('id', 'nik_siswa', 'nik_guru', 'id_qr', 'sanksi', 'status_buku', 'status_sanksi', 'tanggal_laporan')
            ->with([
                'siswa' => function ($q) {
                    $q->select('nik', 'nama_siswa');
                },
                'guru' => function ($q) {
                    $q->select('nik', 'nama_guru');
                },
                'qrBuku' => function ($q) {
                    $q->select('id', 'kode');
                },
            ])
            ->orderBy('tanggal_laporan', 'DESC')
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama', function ($row) {
                $role = $row->nik_siswa ? 'Siswa' : 'Guru';
                $nama = $row->siswa ? $row->siswa->nama_siswa : ($row->guru ? $row->guru->nama_guru : '-');
                return '<span class="text-decoration-underline" data-placement="top" title="' . $role . '">' . $nama . '</span>';
            })
            ->addColumn('kode_qr', function ($row) {
                return $row->qrBuku ? $row->qrBuku->kode : '-';
            })
            ->addColumn('status_sanksi', function (BukuRusakHilang $row) {
                $badgeClass = 'badge-secondary';
                $status = $row->status_sanksi;

                if ($status == 'selesai') {
                    $badgeClass = 'badge-success';
                } elseif ($status == 'belum_selesai') {
                    $badgeClass = 'badge-danger';
                }

                return '<span class="badge ' . $badgeClass . '">' . Str::replace('_', ' ', ucfirst($status)) . '</span>';
            })
            ->addColumn('action', function (BukuRusakHilang $row) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item edit" href="/admin/peminjaman/buku-rusak-hilang/edit/' . $row->id . '">Edit</a></li>
                        <li><a data-id="' . $row->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
                ';
            })
            ->rawColumns(['action', 'kode_qr', 'status_sanksi', 'nama'])
            ->make(true);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('term');

        $data = QrBuku::select('qr_buku.id', 'qr_buku.kode', 'buku.judul_buku as judul_buku')
            ->join('buku', 'qr_buku.id_buku', '=', 'buku.id')
            ->where('qr_buku.kode', 'LIKE', "%{$searchTerm}%")
            ->orWhere('buku.judul_buku', 'LIKE', "%{$searchTerm}%")
            ->limit(10)
            ->get();

        return response()->json($data, 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'id_qr' => 'required|exists:qr_buku,id',
            'sanksi' => 'required|string|max:255',
            'status_buku' => 'required|in:rusak,hilang',
            'status_sanksi' => 'required|in:selesai,belum_selesai',
            'tanggal_laporan' => 'required|date',
        ]);

        $existing = BukuRusakHilang::where('id_qr', $request->id_qr)->where('id', '!=', $request->id)->first();

        if ($existing) {
            return redirect()->back()->with('message', 'QR Buku sudah digunakan di data lain');
        }

        $data = BukuRusakHilang::find($request->id);
        if (!$data) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Tidak ditemukan',
                ],
                404,
            );
        }

       $data->update([
            'nik_siswa' => $data->nik_siswa,
            'nik_guru' => $data->nik_guru,
            'id_qr' => $request->id_qr,
            'sanksi' => $request->sanksi,
            'status_buku' => $request->status_buku,
            'status_sanksi' => $request->status_sanksi,
            'tanggal_laporan' => $request->tanggal_laporan,
        ]);

        return response()->json(
            [
                'status' => 201,
                'message' => 'Data Buku Rusak/Hilang berhasil diupdate',
            ],
            201,
        );
    }


    public function check(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
        ]);

        $code = $request->code;

        $guru = Guru::where('nik', $code)->first();
        if ($guru) {
            $data = [
                'id' => $guru->id,
                'nik' => $guru->nik,
                'nama_guru' => $guru->nama_guru, 
                'nama_mata_pelajaran' => $guru->nama_mata_pelajaran ?? '-', 
                'role' => 'guru',
            ];
            return response()->json(['type' => 'guru', 'data' => $data]);
        }

        $siswa = Siswa::where('nik', $code)->first();
        if ($siswa) {
            $kelas = $siswa->kelas ? 
                "{$siswa->kelas->tingkat_kelas} {$siswa->kelas->kelompok} ({$siswa->kelas->urusan_kelas}) (Jurusan {$siswa->kelas->jurusan})" : 
                '-';
            $data = [
                'id' => $siswa->id,
                'nik' => $siswa->nik,
                'nama_siswa' => $siswa->nama_siswa,
                'kelas' => $kelas, 
                'role' => 'siswa', 
            ];
            return response()->json(['type' => 'siswa', 'data' => $data]);
        }

        return response()->json(['error' => 'Kode tidak ditemukan'], 404);
    }
    
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:buku_rusak_hilang,id',
        ]);

        BukuRusakHilang::where('id', $request->id)->delete();
        return response()->json(
            [
                'message' => 'Data Buku Rusak/Hilang berhasil dihapus',
            ],
            200,
        );
    }
}
