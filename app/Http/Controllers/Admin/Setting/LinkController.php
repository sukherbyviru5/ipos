<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\Link;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    public function index()
    {
        return view('admin.setting.link.index')->with('sb', 'Link');
    }

    public function create(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'link' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [];
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('logo'), $filename);
            $data['logo'] = 'logo/' . $filename;
        }

        $data['link'] = $request->link;

        Link::create($data);

        return redirect()->back()->with('message', 'Link berhasil ditambahkan');
    }

    public function getall(Request $request)
    {
        $query = Link::select('id', 'logo', 'link')
            ->orderBy('created_at', 'ASC')
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (Link $logo) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $logo->id . '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' . $logo->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
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
            Link::where('id', $request->id)->first(),
            200
        );
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'link' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [];
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('logo'), $filename);
            $data['logo'] = 'logo/' . $filename;

            // Hapus logo lama jika ada
            $logo = Link::find($request->id);
            if ($logo->logo && file_exists(public_path($logo->logo))) {
                unlink(public_path($logo->logo));
            }
        }

        $data['link'] = $request->link;

        Link::where('id', $request->id)->update($data);

        return redirect()->back()->with('message', 'Link berhasil diupdate');
    }

    public function delete(Request $request)
    {
        $logo = Link::find($request->id);
        if ($logo && $logo->logo && file_exists(public_path($logo->logo))) {
            unlink(public_path($logo->logo)); 
        }

        Link::where('id', $request->id)->delete();
        return response()->json([
            'message' => 'Link berhasil dihapus'
        ], 200);
    }
}