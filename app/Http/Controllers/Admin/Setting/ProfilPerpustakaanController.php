<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProfilPerpustakaan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfilPerpustakaanController extends Controller
{
    public function index()
    {
        $setting = ProfilPerpustakaan::first();
        return view('admin.setting.profil_perpustakaan.index', compact('setting'))->with('sb', 'Profil Perpustakaan');
    }

    public function store(Request $request)
    {
        if (ProfilPerpustakaan::exists()) {
            return redirect()->back()->with('error', 'Pengaturan profil sudah ada. Silakan update pengaturan yang ada.');
        }

        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $this->prepareData($request);

        ProfilPerpustakaan::create($data);

        return redirect()->route('admin.setting.profil_perpustakaan.index')->with('message', 'Pengaturan profil berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $setting = ProfilPerpustakaan::findOrFail($id);

        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $this->prepareData($request);

        if ($request->hasFile('struktur_organisasi') && $setting->struktur_organisasi) {
            if (file_exists(public_path($setting->struktur_organisasi))) {
                unlink(public_path($setting->struktur_organisasi));
            }
        }

        $setting->update($data);

        return redirect()->route('admin.setting.profil_perpustakaan.index')->with('message', 'Pengaturan profil berhasil diupdate');
    }

    private function validationRules()
    {
        return [
            'sejarah' => 'nullable|string',
            'struktur_organisasi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'visi_misi' => 'nullable|string',
        ];
    }

    private function prepareData(Request $request)
    {
        $data = [
            'sejarah' => $request->sejarah,
            'visi_misi' => $request->visi_misi,
        ];

        if ($request->hasFile('struktur_organisasi')) {
            $file = $request->file('struktur_organisasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto'), $filename);
            $data['struktur_organisasi'] = 'foto/' . $filename;
        }

        return $data;
    }
}