<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SettingPeminjaman;
use Yajra\DataTables\Facades\DataTables;

class SettingPeminjamanController extends Controller
{
    public function index()
    {
        return view('admin.peminjaman.settings.index')->with('sb', "Setting Peminjaman");
    }

    public function create(Request $request)
    {
        SettingPeminjaman::create([
            'batas_jumlah_buku' => $request->batas_jumlah_buku,
            'lama_peminjaman' => $request->lama_peminjaman,
            'lama_perpanjangan' => $request->lama_perpanjangan,
            'batas_perpanjangan' => $request->batas_perpanjangan,
            'denda_telat' => $request->denda_telat,
            'perhitungan_denda' => $request->perhitungan_denda,
            'syarat_peminjaman' => $request->syarat_peminjaman,
            'syarat_perpanjangan' => $request->syarat_perpanjangan,
            'syarat_pengembalian' => $request->syarat_pengembalian,
            'sanksi_kerusakan' => $request->sanksi_kerusakan,
        ]);
        return redirect()->to('admin/peminjaman/settings')->with('message', "Setting Peminjaman berhasil dibuat");
    }

    public function getall(Request $request)
    {
        $query = SettingPeminjaman::select('id', 'batas_jumlah_buku', 'lama_peminjaman', 'lama_perpanjangan', 'batas_perpanjangan', 'denda_telat', 'perhitungan_denda')
            ->orderBy('id', "ASC")
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (SettingPeminjaman $setting) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $setting->id . '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' . $setting->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
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
            SettingPeminjaman::where('id', $request->id)->first(),
            200
        );
    }

    public function update(Request $request)
    {
        SettingPeminjaman::where('id', $request->id)->update([
            'batas_jumlah_buku' => $request->batas_jumlah_buku,
            'lama_peminjaman' => $request->lama_peminjaman,
            'lama_perpanjangan' => $request->lama_perpanjangan,
            'batas_perpanjangan' => $request->batas_perpanjangan,
            'denda_telat' => $request->denda_telat,
            'perhitungan_denda' => $request->perhitungan_denda,
            'syarat_peminjaman' => $request->syarat_peminjaman,
            'syarat_perpanjangan' => $request->syarat_perpanjangan,
            'syarat_pengembalian' => $request->syarat_pengembalian,
            'sanksi_kerusakan' => $request->sanksi_kerusakan,
        ]);
        return redirect()->to('admin/peminjaman/settings')->with('message', "Setting Peminjaman berhasil diupdate");
    }

    public function delete(Request $request)
    {
        SettingPeminjaman::where('id', $request->id)->delete();
        return response()->json([
            'message' => "Setting Peminjaman berhasil dihapus"
        ], 200);
    }
}