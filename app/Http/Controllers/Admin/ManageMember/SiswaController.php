<?php

namespace App\Http\Controllers\Admin\ManageMember;

use Exception;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use App\Helpers\QrCodeHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SiswaController extends Controller
{
    public function index()
    {
        $kelas = Kelas::select('id', 'tingkat_kelas', 'jurusan', 'urusan_kelas', 'kelompok')->get();
        return view('admin.manage_member.siswa.index', compact('kelas'))->with('sb', 'Data Siswa');
    }

     public function getall(Request $request)
    {
        $query = Siswa::select('siswa.id', 'siswa.nisn', 'siswa.nik', 'siswa.nama_siswa', 'kelas.tingkat_kelas', 'kelas.kelompok')
                ->join('kelas', 'kelas.id', '=', 'siswa.id_kelas') 
                ->where('kelas.id', $request->id)
                ->orderBy('siswa.nama_siswa', "ASC")  
                ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (Siswa $siswa) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $siswa->id . '" class="dropdown-item edit">Edit</a></li>
                        <li><a data-id="' . $siswa->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|unique:siswa,nik',
            'nisn' => 'required|string|unique:siswa,nisn',
            'nama_siswa' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'id_kelas' => 'required|exists:kelas,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('siswa_fotos'), $filename);
            $fotoPath = 'siswa_fotos/' . $filename;
        }

        $qrCodePath = QrCodeHelper::generateQrCode($request->nisn, $request->nisn);
        Siswa::create([
            'nik' => $request->nik,
            'nisn' => $request->nisn,
            'password' => Hash::make($request->nisn),
            'nama_siswa' => $request->nama_siswa,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_kelas' => $request->id_kelas,
            'foto' => $fotoPath,
            'qr_code' => $qrCodePath,
            'status' => 'aktif',
            'is_alumni' => false,
        ]);


        return redirect()->back()->with('message', 'Data siswa berhasil disimpan');
    }


    public function get(Request $request)
    {
        return response()->json(
            Siswa::findOrFail($request->id),
            200
        );
    }

    public function getKelas(Request $request)
    {
        $kelas = Kelas::all();  

        return response()->json($kelas);
    }


    public function update(Request $request)
    {
        $id = $request->id;
        if (!$id) {
            return redirect()->back()->with('error', 'ID siswa tidak ditemukan');
        }
        $siswa = Siswa::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|unique:siswa,nik,' . $siswa->id,
            'nisn' => 'required|string|unique:siswa,nisn,' . $siswa->id,
            'nama_siswa' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'id_kelas' => 'required|exists:kelas,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fotoPath = $siswa->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && file_exists(public_path($fotoPath))) {
                unlink(public_path($fotoPath));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('siswa_fotos'), $filename);
            $fotoPath = 'siswa_fotos/' . $filename;
        }

        $qrCodePath = QrCodeHelper::generateQrCode($siswa->nisn, $siswa->nisn);
        $siswa->update([
            'nik' => $request->nik,
            'nisn' => $request->nisn,
            'password' => Hash::make($request->nisn),
            'nama_siswa' => $request->nama_siswa,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_kelas' => $request->id_kelas,
            'foto' => $fotoPath,
            'qr_code' => $qrCodePath,
        ]);

        return redirect()->back()->with('message', 'Data siswa berhasil diupdate');
    }

    public function destroy(Request $request)
    {
        $siswa = Siswa::findOrFail($request->id);
        if ($siswa->foto && file_exists(public_path($siswa->foto))) {
            unlink(public_path($siswa->foto));
        }
        $siswa->delete();
        return response()->json(['message' => 'Data siswa berhasil dihapus'], 200);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx',
            'id_kelas' => 'required|exists:kelas,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid input: ' . $validator->errors()->first());
        }

        try {
            Excel::import(new SiswaImport($request->id_kelas), $request->file('file'));
            return redirect()->back()->with('message', 'Data siswa berhasil diimpor');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat impor data');
        }
    }

   
}