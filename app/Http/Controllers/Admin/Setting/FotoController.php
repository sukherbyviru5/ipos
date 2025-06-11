<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\Foto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class FotoController extends Controller
{
    public function index()
    {
        return view('admin.setting.foto.index')->with('sb', 'Foto Kegiatan');
    }

    public function create(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [];
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto'), $filename);
            $data['foto'] = 'foto/' . $filename;
        }

        $data['keterangan'] = $request->keterangan;

        Foto::create($data);

        return redirect()->back()->with('message', 'Foto berhasil ditambahkan');
    }

    public function getall(Request $request)
    {
        $query = Foto::select('id', 'foto', 'keterangan')
            ->orderBy('created_at', 'ASC')
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (Foto $foto) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $foto->id . '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' . $foto->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
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
            Foto::where('id', $request->id)->first(),
            200
        );
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [];
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto'), $filename);
            $data['foto'] = 'foto/' . $filename;

            // Hapus foto lama jika ada
            $foto = Foto::find($request->id);
            if ($foto->foto && file_exists(public_path($foto->foto))) {
                unlink(public_path($foto->foto));
            }
        }

        $data['keterangan'] = $request->keterangan;

        Foto::where('id', $request->id)->update($data);

        return redirect()->back()->with('message', 'Foto berhasil diupdate');
    }

    public function delete(Request $request)
    {
        $foto = Foto::find($request->id);
        if ($foto && $foto->foto && file_exists(public_path($foto->foto))) {
            unlink(public_path($foto->foto)); 
        }

        Foto::where('id', $request->id)->delete();
        return response()->json([
            'message' => 'Foto berhasil dihapus'
        ], 200);
    }
}