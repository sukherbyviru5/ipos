<?php

namespace App\Http\Controllers\Admin\Publikasi;

use App\Models\Artikel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Siswa;
use Yajra\DataTables\Facades\DataTables;

class ArtikelController extends Controller
{
    public function index()
    {
        return view('admin.publikasi.artikel.index')->with('sb', 'Artikel');
    }

    public function create(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'status' => 'required|in:draft,setuju,tolak',
        ]);

        $baseSlug = Str::slug($request->judul);
        $count = Artikel::where('slug', 'like', $baseSlug . '%')->count();
        $slug = $count ? $baseSlug . '-' . ($count + 1) : $baseSlug;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $slug . '_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('artikel'), $filename);
            $fileName = 'artikel/' . $filename;
        } else {
            return redirect()->back()->withErrors(['file' => 'File is required'])->withInput();
        }
        Artikel::create([
            'judul' => $request->judul,
            'slug' => $slug,
            'file' => $fileName,
            'status' => $request->status,
            'created_by' => auth()->user()->nip_nik_nisn,
        ]);

        return redirect()->back()->with('message', 'Artikel berhasil dibuat');
    }

    public function getall(Request $request)
    {
        $query = Artikel::select('id', 'judul', 'file', 'slug', 'status', 'created_by')
            ->orderBy('created_at', 'DESC')
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('title', function (Artikel $artikel) {
                return '<a href="' . asset($artikel->file) . '" target="_blank">' . $artikel->judul . '</a>';
            })
            ->addColumn('created_by', function (Artikel $artikel) {
                $siswa = Siswa::getSiswaByNik($artikel->created_by);
                $admin = Admin::getAdminByNipNikNisn($artikel->created_by);
                $guru = Guru::getSiswaByNik($artikel->created_by);
                if ($siswa) {
                    return $siswa->nama_siswa;
                } elseif ($admin) {
                    return $admin->nama;
                } elseif ($guru) {
                    return $guru->nama_guru;
                }
                return 'Unknown';
            })
            ->addColumn('status_badge', function (Artikel $artikel) {
                $badges = [
                    'draft' => 'warning',
                    'setuju' => 'success',
                    'tolak' => 'danger'
                ];
                return '<span class="badge badge-' . $badges[$artikel->status] . '">' . ucfirst($artikel->status) . '</span>';
            })
            ->addColumn('action', function (Artikel $artikel) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $artikel->id . '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' . $artikel->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
                ';
            })
            ->rawColumns(['status_badge','title', 'action'])
            ->make(true);
    }

    public function get(Request $request)
    {
        return response()->json(
            Artikel::select('id', 'judul', 'status', 'file')->where('id', $request->id)->first(),
            200
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:artikel,id',
            'judul' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'status' => 'required|in:draft,setuju,tolak',
        ]);

        $artikel = Artikel::findOrFail($request->id);
        $data = [
            'judul' => $request->judul,
            'status' => $request->status,
        ];

        if ($request->hasFile('file')) {
            if (file_exists(public_path($artikel->file))) {
                unlink(public_path($artikel->file));
            }
            $file = $request->file('file');
            $filename = $artikel->slug . '_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('artikel'), $filename);
            $fileName = 'artikel/' . $filename;
            $data['file'] = $fileName;
        }

        $artikel->update($data);

        return redirect()->back()->with('message', 'Artikel berhasil diupdate');
    }

    public function delete(Request $request)
    {
        $artikel = Artikel::findOrFail($request->id);
        if (file_exists(public_path('artikel/' . $artikel->file))) {
            unlink(public_path('artikel/' . $artikel->file));
        }
        $artikel->delete();
        return response()->json([
            'message' => 'Artikel berhasil dihapus'
        ], 200);
    }
}