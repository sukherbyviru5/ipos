<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\LogAktivitasGuru;
use App\Models\AbsensiPengunjung;
use App\Models\LogAktivitasSiswa;
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
            return redirect()->to('/')->withErrors(['message' => 'Gagal Masuk, Coba Lagi..'])->withInput($request->all());
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
                return redirect()->to('admin')->with('success', 'Selamat datang, Admin!');
            }

            // Guru
            $guru = Guru::where('nik', $data['nip_nik_nisn'])->get();
            foreach ($guru as $g) {
                if (password_verify($data['password'], $g->password)) {
                    $guruDetail = Guru::find($g->id);
                    if ($guruDetail->status == 'non-aktif') {
                        return redirect()->to('/login')->withErrors(['message' => 'Akun sudah tidak aktif']);
                    }
                    $data_session = [
                        'role' => 'guru',
                        'is_guru' => true,
                        'nama' => $guruDetail?->nama_guru,
                        'id' => $guruDetail?->id,
                        'nip_nik_nisn' => $guruDetail?->nik
                    ];
                    $request->session()->put($data_session);
                    AbsensiPengunjung::add($guruDetail->nik);
                    LogAktivitasGuru::add($guruDetail->nik, 'Masuk ke aplikasi pada hari ' . Carbon::now()->translatedFormat('l, d F Y H:i'));
                    return redirect()->to('/')->with('success', 'Selamat datang, ' . $guruDetail->nama_guru . '!');
                }
            }

            // Siswa
            $siswa = Siswa::where('nik', $data['nip_nik_nisn'])->get();
            foreach ($siswa as $s) {
                if (password_verify($data['password'], $s->password)) {
                    $siswaDetail = Siswa::find($s->id);
                    if ($siswaDetail->status == 'non-aktif') {
                        return redirect()->to('/login')->withErrors(['message' => 'Akun sudah tidak aktif']);
                    }
                    $data_session = [
                        'role' => 'siswa',
                        'is_siswa' => true,
                        'nama' => $siswaDetail?->nama_siswa,
                        'id' => $siswaDetail?->id,
                        'kelas_id' => $siswaDetail?->id_kelas,
                        'nip_nik_nisn' => $siswaDetail->nik,
                    ];
                    $request->session()->put($data_session);
                    AbsensiPengunjung::add($siswaDetail->nik);
                    LogAktivitasSiswa::add($siswaDetail->nik, 'Masuk ke aplikasi pada hari ' . Carbon::now()->translatedFormat('l, d F Y H:i'));
                    return redirect()->to('/')->with('success', 'Selamat datang, ' . $siswaDetail->nama_siswa . '!');
                }
            }

            return redirect()->to('/')->withErrors(['message' => 'Akun tidak ditemukan']);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->to('/');
    }
}
