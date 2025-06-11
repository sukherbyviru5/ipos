<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SettingPeminjaman;
use Illuminate\Support\Facades\Validator;

class SettingPeminjamanController extends Controller
{
    public function index()
    {
        $setting = SettingPeminjaman::first();
        return view('admin.peminjaman.settings.index', compact('setting'))->with('sb', 'Setting Peminjaman');
    }

    public function store(Request $request)
    {
        if (SettingPeminjaman::exists()) {
            return redirect()->back()->with('error', 'Pengaturan peminjaman sudah ada. Silakan update pengaturan yang ada.');
        }

        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        SettingPeminjaman::create($this->prepareData($request));

        return redirect()->route('admin.peminjaman.settings.index')->with('message', 'Pengaturan peminjaman berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $setting = SettingPeminjaman::findOrFail($id);

        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $setting->update($this->prepareData($request));

        return redirect()->route('admin.peminjaman.settings.index')->with('message', 'Pengaturan peminjaman berhasil diupdate');
    }

    private function validationRules()
    {
        return [
            'batas_jumlah_buku_status' => 'in:aktif',
            'batas_jumlah_buku' => 'required_if:batas_jumlah_buku_status,aktif|nullable|integer|min:1',
            'lama_peminjaman_status' => 'in:aktif',
            'lama_peminjaman' => 'required_if:lama_peminjaman_status,aktif|nullable|integer|min:1',
            'lama_perpanjangan_status' => 'in:aktif',
            'lama_perpanjangan' => 'required_if:lama_perpanjangan_status,aktif|nullable|integer|min:1',
            'batas_perpanjangan_status' => 'in:aktif',
            'batas_perpanjangan' => 'required_if:batas_perpanjangan_status,aktif|nullable|integer|min:1',
            'denda_telat_status' => 'in:aktif',
            'denda_telat' => 'required_if:denda_telat_status,aktif|nullable|integer|min:0',
            'perhitungan_denda' => 'required|in:non aktif,per hari,per minggu',
            'syarat_peminjaman' => 'required|string',
            'syarat_perpanjangan' => 'required|string',
            'syarat_pengembalian' => 'required|string',
            'sanksi_kerusakan' => 'required|string',
        ];
    }

    private function prepareData(Request $request)
    {
        return [
            'batas_jumlah_buku_status' => $request->has('batas_jumlah_buku_status') ? 'aktif' : 'non aktif',
            'batas_jumlah_buku' => $request->has('batas_jumlah_buku_status') && $request->filled('batas_jumlah_buku') ? $request->batas_jumlah_buku : 'non aktif',
            'lama_peminjaman_status' => $request->has('lama_peminjaman_status') ? 'aktif' : 'non aktif',
            'lama_peminjaman' => $request->has('lama_peminjaman_status') && $request->filled('lama_peminjaman') ? $request->lama_peminjaman : 'non aktif',
            'lama_perpanjangan_status' => $request->has('lama_perpanjangan_status') ? 'aktif' : 'non aktif',
            'lama_perpanjangan' => $request->has('lama_perpanjangan_status') && $request->filled('lama_perpanjangan') ? $request->lama_perpanjangan : 'non aktif',
            'batas_perpanjangan_status' => $request->has('batas_perpanjangan_status') ? 'aktif' : 'non aktif',
            'batas_perpanjangan' => $request->has('batas_perpanjangan_status') && $request->filled('batas_perpanjangan') ? $request->batas_perpanjangan : 'non aktif',
            'denda_telat_status' => $request->has('denda_telat_status') ? 'aktif' : 'non aktif',
            'denda_telat' => $request->has('denda_telat_status') && $request->filled('denda_telat') ? $request->denda_telat : 'non aktif',
            'perhitungan_denda' => $request->has('denda_telat_status') ? $request->perhitungan_denda : 'non aktif',
            'syarat_peminjaman' => $request->syarat_peminjaman,
            'syarat_perpanjangan' => $request->syarat_perpanjangan,
            'syarat_pengembalian' => $request->syarat_pengembalian,
            'sanksi_kerusakan' => $request->sanksi_kerusakan,
        ];
    }
}