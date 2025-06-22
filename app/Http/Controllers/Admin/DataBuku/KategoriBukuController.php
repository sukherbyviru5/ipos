<?php

namespace App\Http\Controllers\Admin\DataBuku;

use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\KategoriBukuImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\KategoriBukuTemplateExport;

class KategoriBukuController extends Controller
{
    public function index()
    {
        return view('admin.data_buku.kategori_buku.index')->with('sb', 'Kategori Buku');
    }

    public function create(Request $request)
    {
        if (KategoriBuku::where('no_urut', $request->no_urut)->first() != null) {
            return redirect()->back()->with('message', 'Data Kategori sudah ada');
        } else {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('image'), $filename);
                $fotoPath = 'image/' . $filename;
                $image = $fotoPath;
            } else {
                $image = null;
            }
            KategoriBuku::create([
                'no_urut' => $request->no_urut,
                'image' => $image,
                'nama_kategori' => $request->nama_kategori,
            ]);
            return redirect()->back()->with('message', 'Data Kategori berhasil dibuat');
        }
    }

    public function getall(Request $request)
    {
        $query = KategoriBuku::select('id', 'no_urut', 'nama_kategori')->orderBy('no_urut', 'ASC')->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (KategoriBuku $k) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' .
                    $k->id .
                    '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' .
                    $k->id .
                    '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
            ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get(Request $request)
    {
        return response()->json(KategoriBuku::where('id', $request->id)->first(), 200);
    }

    public function update(Request $request)
    {
        $existing = KategoriBuku::where('no_urut', $request->no_urut)->where('id', '!=', $request->id)->first();

        if ($existing) {
            return redirect()->back()->with('message', 'Nomor urut sudah digunakan');
        }

        $data = KategoriBuku::find($request->id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('image'), $filename);
            $fotoPath = 'image/' . $filename;
            $image = $fotoPath;
        } else {
            $image = $data->image;
        }

        $data->update([
            'no_urut' => $request->no_urut,
            'image' => $image,
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->back()->with('message', 'Data Kategori Buku berhasil diupdate');
    }

    public function delete(Request $request)
    {
        KategoriBuku::where('id', $request->id)->delete();
        return response()->json(
            [
                'message' => 'Kategori Buku berhasil dihapus',
            ],
            200,
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            DB::beginTransaction();
            Excel::import(new KategoriBukuImport(), $request->file('file'));
            DB::commit();

            return response()->json([
                'message' => 'Kategori buku berhasil diimpor',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'Gagal : ' . $e->getMessage(),
                ],
                500,
            );
        }
    }
}
