<?php

namespace App\Http\Controllers\Admin\DataBuku;

use App\Models\JenisBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\JenisBukuImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class JenisBukuController extends Controller
{
    public function index()
    {
        return view('admin.data_buku.jenis_buku.index')->with('sb', 'Jenis Buku');
    }

    public function create(Request $request)
    {
        $nama_jenis = strtolower($request->nama_jenis);
        
        if (JenisBuku::whereRaw('LOWER(nama_jenis) = ?', [$nama_jenis])->first() != null) {
            return redirect()->back()->with('message', "Data Jenis Buku sudah ada");
        } else {
            JenisBuku::create([
                'nama_jenis' => $nama_jenis,
            ]);
            return redirect()->back()->with('message', "Data Jenis Buku berhasil dibuat");
        }
    }

    public function getall(Request $request)
    {
        $query = JenisBuku::select('id', 'nama_jenis')
            ->orderBy('nama_jenis', "ASC")
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (JenisBuku $k) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $k->id . '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' . $k->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
            ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get(Request $request)
    {
        return response()->json(
            JenisBuku::where('id', $request->id)->first(),
            200
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
        ]);

        $nama_jenis = strtolower($request->nama_jenis);
        $existing = JenisBuku::whereRaw('LOWER(nama_jenis) = ?', [$nama_jenis])
            ->where('id', '!=', $request->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('message', "Data Jenis Buku sudah ada");
        }

        JenisBuku::where('id', $request->id)->update([
            'nama_jenis' => $nama_jenis,
        ]);

        return redirect()->back()->with('message', "Data Jenis Buku berhasil diupdate");
    }

    public function delete(Request $request)
    {
        JenisBuku::where('id', $request->id)->delete();
        return response()->json([
            'message' => "Jenis Buku berhasil dihapus"
        ], 200);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            DB::beginTransaction();
            Excel::import(new JenisBukuImport, $request->file('file'));
            DB::commit();

            return response()->json([
                'message' => 'Jenis Buku berhasil diimpor',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal : ' . $e->getMessage(),
            ], 500);
        }
    }

    
}
