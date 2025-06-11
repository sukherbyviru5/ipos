<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SettingApp;
use Illuminate\Support\Facades\Validator;

class SettingAplikasiController extends Controller
{
    public function index()
    {
        $setting = SettingApp::first();
        return view('admin.setting.apps.index', compact('setting'))->with('sb', 'Setting Apps');
    }

    public function store(Request $request)
    {
        if (SettingApp::exists()) {
            return redirect()->back()->with('error', 'Pengaturan aplikasi sudah ada. Silakan update pengaturan yang ada.');
        }

        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $this->prepareData($request);

        SettingApp::create($data);

        return redirect()->route('admin.setting.apps.index')->with('message', 'Pengaturan aplikasi berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $setting = SettingApp::findOrFail($id);

        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $this->prepareData($request);

        if ($request->hasFile('logo') && $setting->logo) {
            if (file_exists(public_path($setting->logo))) {
                unlink(public_path($setting->logo));
            }
        }

        $setting->update($data);

        return redirect()->route('admin.setting.apps.index')->with('message', 'Pengaturan aplikasi berhasil diupdate');
    }

    private function validationRules()
    {
        return [
            'nama_instansi' => 'required|string|max:255',
            'nama_sub_instansi' => 'required|string|max:255',
            'nama_madrasah' => 'required|string|max:255',
            'alamat_madrasah' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nama_kepala_madrasah' => 'required|string|max:255',
            'nip_kamad' => 'required|string|max:255',
            'nama_kepala_perpustakaan' => 'required|string|max:255',
            'nip_kepala_perpustakaan' => 'required|string|max:255',
            'email_madrasah' => 'nullable|email|max:255',
            'no_telpon' => 'nullable|string|max:20',
            'embed_maps' => 'nullable|string',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
        ];
    }

    private function prepareData(Request $request)
    {
        $data = $request->only([
            'nama_instansi',
            'nama_sub_instansi',
            'nama_madrasah',
            'alamat_madrasah',
            'nama_kepala_madrasah',
            'nip_kamad',
            'nama_kepala_perpustakaan',
            'nip_kepala_perpustakaan',
            'email_madrasah',
            'no_telpon',
            'embed_maps',
            'facebook',
            'instagram',
            'youtube'
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('logo'), $filename);
            $data['logo'] = 'logo/' . $filename;
        }

        return $data;
    }
}