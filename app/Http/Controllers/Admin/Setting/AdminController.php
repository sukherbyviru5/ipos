<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\AdminAccountImport;
use App\Exports\AdminAccountTemplateExport;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.setting.admin.index')->with('sb', 'Admin Accounts');
    }

    public function create(Request $request)
    {
        $request->validate([
            'nip_nik_nisn' => 'required|string|max:255|unique:admin,nip_nik_nisn',
            'nama' => 'required|string|max:255',
            'password' => 'required|string|confirmed',
        ]);

        Admin::create([
            'nip_nik_nisn' => $request->nip_nik_nisn,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('message', 'Akun admin berhasil dibuat');
    }

    public function getall(Request $request)
    {
        $query = Admin::select('id', 'nip_nik_nisn', 'nama')
            ->orderBy('nama', 'ASC')
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (Admin $admin) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $admin->id . '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' . $admin->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
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
            Admin::select('id', 'nip_nik_nisn', 'nama')->where('id', $request->id)->first(),
            200
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:admin,id',
            'nip_nik_nisn' => 'required|string|max:255|unique:admin,nip_nik_nisn,' . $request->id,
            'nama' => 'required|string|max:255',
            'password' => 'nullable|string|confirmed',
        ]);

        $admin = Admin::findOrFail($request->id);
        $data = [
            'nip_nik_nisn' => $request->nip_nik_nisn,
            'nama' => $request->nama,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->back()->with('message', 'Akun admin berhasil diupdate');
    }

    public function delete(Request $request)
    {
        Admin::where('id', $request->id)->delete();
        return response()->json([
            'message' => 'Akun admin berhasil dihapus'
        ], 200);
    }
    
}