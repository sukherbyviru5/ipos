<?php

namespace App\Http\Controllers\Admin\DataBuku;

use App\Models\KondisiBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\KondisiBukuImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class KondisiBukuController extends Controller
{
    public function index()
    {
        return view('admin.data_buku.kondisi_buku.index')->with('sb', 'Kondisi Buku');
    }

    public function create(Request $request)
    {
        if (
            KondisiBuku::where('nama_kondisi', $request->nama_kondisi)->first() != null
        ) {
            return redirect()->back()->with('message', "Data Kondisi Buku sudah ada");
        } else {
            KondisiBuku::create([
                'nama_kondisi' => $request->nama_kondisi,
            ]);
            return redirect()->back()->with('message', "Data Kondisi Buku berhasil dibuat");
        }
    }

    public function getall(Request $request)
    {
        $query = KondisiBuku::select('id', 'nama_kondisi')
            ->orderBy('nama_kondisi', "ASC")
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (KondisiBuku $k) {
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
            KondisiBuku::where('id', $request->id)->first(),
            200
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_kondisi' => 'required|string|max:255',
        ]);
        KondisiBuku::where('id', $request->id)->update([
            'nama_kondisi' => $request->nama_kondisi,
        ]);

        return redirect()->back()->with('message', "Data Kondisi Buku berhasil diupdate");
    }

    public function delete(Request $request)
    {
        KondisiBuku::where('id', $request->id)->delete();
        return response()->json([
            'message' => "Kondisi Buku berhasil dihapus"
        ], 200);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            DB::beginTransaction();
            Excel::import(new KondisiBukuImport, $request->file('file'));
            DB::commit();

            return response()->json([
                'message' => 'Kondisi Buku berhasil diimpor',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal : ' . $e->getMessage(),
            ], 500);
        }
    }

    
}
