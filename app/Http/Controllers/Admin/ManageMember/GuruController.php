<?php

namespace App\Http\Controllers\Admin\ManageMember;

use Exception;
use App\Models\Guru;
use App\Imports\GuruImport;
use Illuminate\Http\Request;
use App\Helpers\QrCodeHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GuruController extends Controller
{
    public function index()
    {
        return view('admin.manage_member.guru.index')->with('sb', 'Data Guru');
    }

    public function getall(Request $request)
    {
        $query = Guru::select('id', 'nik', 'nip', 'nama_guru', 'nama_mata_pelajaran', 'status')
            ->orderBy('nama_guru', 'ASC')
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status_badge', function (Guru $guru) {
                $badges = [
                    'aktif' => 'success',
                    'non-aktif' => 'danger'
                ];
                return '<span class="badge badge-' . $badges[$guru->status] . '">' . ucfirst($guru->status) . '</span>';
            })
            ->addColumn('action', function (Guru $guru) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $guru->id . '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' . $guru->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
                ';
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|unique:guru,nik',
            'nip' => 'required|string|unique:guru,nip',
            'nama_guru' => 'required|string|max:255',
            'nama_mata_pelajaran' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,non-aktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $qrCodePath = QrCodeHelper::generateQrCode($request->nik, $request->nik);

        Guru::create([
            'nik' => $request->nik,
            'nip' => $request->nip,
            'password' => Hash::make($request->nip),
            'nama_guru' => $request->nama_guru,
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran,
            'qr_code' => $qrCodePath,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('message', 'Data guru berhasil disimpan');
    }

    public function get(Request $request)
    {
        return response()->json(
            Guru::select('id', 'nik', 'nip', 'nama_guru', 'nama_mata_pelajaran', 'status')
                ->where('id', $request->id)
                ->first(),
            200
        );
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:guru,id',
            'nik' => 'required|string|unique:guru,nik,' . $request->id,
            'nip' => 'required|string|unique:guru,nip,' . $request->id,
            'nama_guru' => 'required|string|max:255',
            'nama_mata_pelajaran' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,non-aktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $guru = Guru::findOrFail($request->id);
        $qrCodePath = QrCodeHelper::generateQrCode($request->nik, $request->nik);

        $guru->update([
            'nik' => $request->nik,
            'nip' => $request->nip,
            'password' => Hash::make($request->nip),
            'nama_guru' => $request->nama_guru,
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran,
            'qr_code' => $qrCodePath,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('message', 'Data guru berhasil diupdate');
    }

    public function destroy(Request $request)
    {
        $guru = Guru::findOrFail($request->id);
        if ($guru->qr_code && file_exists(public_path($guru->qr_code))) {
            unlink(public_path($guru->qr_code));
        }
        $guru->delete();
        return response()->json(['message' => 'Data guru berhasil dihapus'], 200);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid input: ' . $validator->errors()->first());
        }

        try {
            Excel::import(new GuruImport, $request->file('file'));
            return redirect()->back()->with('message', 'Data guru berhasil diimpor');
        } catch (Exception $e) {
            Log::info("guru data" . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat impor data: ' . $e->getMessage());
        }
    }
}