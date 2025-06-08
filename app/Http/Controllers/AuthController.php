<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        return view('auth.index');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip_nik_nisn' => 'required',
            'password' => 'required',

        ]);

        if ($validator->fails()) {
           return redirect()->to('/')->withErrors(['message' => 'Recaptcha belum terverifikasi'])->withInput($request->all());
        } else {
            $data = [
                'nip_nik_nisn' => $request->nip_nik_nisn,
                'password' => $request->password,
            ];

            
            // Admin
            if (Auth::guard('admin')->attempt($data)) {
                $data_session = [
                    'role' => 'admin',
                    'is_admin' => true,
                    'nama' => auth('admin')->user()->nama,
                    'id' => auth('admin')->user()->id,
                    'nip_nik_nisn' => auth('admin')->user()->nip_nik_nisn,
                ];
                $request->session()->put($data_session);
                return redirect()->to('admin');
            }

            // # Guru Get
            // $guru = Guru::where('nip_nik_nisn', $data['nip_nik_nisn'])->get();
            // foreach ($guru as $g) {
            //     # Verifikasi Guru
            //     if (password_verify($data['password'], $g->password)) {
            //         # Guru Ada
            //         $guruDetail = Guru::find($g->id);
            //         $data_session = [
            //             'role' => 'guru',
            //             'is_guru' => true,
            //             'nama' => $guruDetail?->nama,
            //             'id' => $guruDetail?->id,
            //             'nip_nik_nisn' => $guruDetail?->nip_nik_nisn,
            //             'sekolah_id' => $guruDetail?->sekolah_id
            //         ];
            //         $request->session()->put($data_session);
            //         return redirect()->to('guru');
            //     }
            // }

            # Siswa Set
            // $siswa = Siswa::where('nip_nik_nisn', $data['nip_nik_nisn'])->get();
            // foreach ($siswa as $s) {
            //     # Verifikasi Siswa
            //     if (password_verify($data['password'], $s->password)) {
            //         # Siswa Ada
            //         $siswaDetail = Siswa::find($s->id);
            //         $data_session = [
            //             'role' => 'siswa',
            //             'is_siswa' => true,
            //             'nama' => $siswaDetail?->nama,
            //             'id' => $siswaDetail?->id,
            //             'sekolah_id' => $siswaDetail?->sekolah_id,
            //             'kelas_id' => $siswaDetail?->kelas_id,
            //         ];
            //         $request->session()->put($data_session);
            //         return redirect()->to('siswa');
            //     }
            // }

            return redirect()->to('/')->withErrors(['message' => 'Akun tidak ditemukan']);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->to('/');
    }
}
