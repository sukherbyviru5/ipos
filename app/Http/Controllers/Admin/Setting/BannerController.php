<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin.setting.banner.index')->with('sb', 'Banner');
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
            $file->move(public_path('banner'), $filename);
            $data['foto'] = 'banner/' . $filename;
        }

        $data['keterangan'] = $request->keterangan;

        Banner::create($data);

        return redirect()->back()->with('message', 'Banner berhasil ditambahkan');
    }

    public function getall(Request $request)
    {
        $query = Banner::select('id', 'foto', 'keterangan')
            ->orderBy('created_at', 'ASC')
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (Banner $banner) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $banner->id . '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' . $banner->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
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
            Banner::where('id', $request->id)->first(),
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
            $file->move(public_path('banner'), $filename);
            $data['foto'] = 'banner/' . $filename;

            // Hapus foto lama jika ada
            $banner = Banner::find($request->id);
            if ($banner->foto && file_exists(public_path($banner->foto))) {
                unlink(public_path($banner->foto));
            }
        }

        $data['keterangan'] = $request->keterangan;

        Banner::where('id', $request->id)->update($data);

        return redirect()->back()->with('message', 'Banner berhasil diupdate');
    }

    public function delete(Request $request)
    {
        $banner = Banner::find($request->id);
        if ($banner && $banner->foto && file_exists(public_path($banner->foto))) {
            unlink(public_path($banner->foto)); // Hapus file foto
        }

        Banner::where('id', $request->id)->delete();
        return response()->json([
            'message' => 'Banner berhasil dihapus'
        ], 200);
    }
}