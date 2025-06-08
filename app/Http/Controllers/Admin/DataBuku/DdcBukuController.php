<?php

namespace App\Http\Controllers\Admin\DataBuku;

use App\Models\DdcBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\DdcBukuImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DdcBukuController extends Controller
{
    public function index()
    {
        return view('admin.data_buku.ddc_buku.index')->with('sb', 'DDC Buku');
    }

    public function create(Request $request)
    {
        if (
            DdcBuku::where('no_klasifikasi', $request->no_klasifikasi)->first() != null
        ) {
            return redirect()->back()->with('message', "Data DDC Buku sudah ada");
        } else {
            DdcBuku::create([
                'no_klasifikasi' => $request->no_klasifikasi,
                'nama_klasifikasi' => $request->nama_klasifikasi,
            ]);
            return redirect()->back()->with('message', "Data DDC Buku berhasil dibuat");
        }
    }

    public function getall(Request $request)
    {
        $query = DdcBuku::select('id', 'no_klasifikasi', 'nama_klasifikasi')
            ->orderBy('no_klasifikasi', "ASC")
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (DdcBuku $k) {
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
            DdcBuku::where('id', $request->id)->first(),
            200
        );
    }

    public function update(Request $request)
    {
        $existing = DdcBuku::where('no_klasifikasi', $request->no_klasifikasi)
            ->where('id', '!=', $request->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('message', "Nomor urut sudah digunakan");
        }

        DdcBuku::where('id', $request->id)->update([
            'no_klasifikasi' => $request->no_klasifikasi,
            'nama_klasifikasi' => $request->nama_klasifikasi,
        ]);

        return redirect()->back()->with('message', "Data DDC Buku berhasil diupdate");
    }

    public function delete(Request $request)
    {
        DdcBuku::where('id', $request->id)->delete();
        return response()->json([
            'message' => "DDC Buku berhasil dihapus"
        ], 200);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            DB::beginTransaction();
            Excel::import(new DdcBukuImport, $request->file('file'));
            DB::commit();

            return response()->json([
                'message' => 'DDC Buku berhasil diimpor',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal : ' . $e->getMessage(),
            ], 500);
        }
    }

    
}
