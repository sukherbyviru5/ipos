<?php

namespace App\Http\Controllers\Admin\ManageMember;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KenaikanKelasController extends Controller
{
    public function index()
    {
        $data = [
            'kelas' => Kelas::get(),
            'siswa' => Siswa::where('id_kelas', @$_GET['kelas_id'])->where('status', 'aktif')->get(),
        ];
        return view('admin.manage_member.kenaikan_kelas.index', $data)->with('sb', 'Kenaikan Kelas');
    }

    public function migrasiSiswa(Request $request)
    {
        if (empty($request->siswa_id)) {
            return response()->json([
                'message' => 'Checklist minimal satu siswa',
            ], 422);
        }

        $sukses = 0;
        foreach ($request->siswa_id as $s) {
            $siswa = Siswa::find($s);
            if (!$siswa) {
                continue;
            }

            if ($request->action === 'migrate') {
                if (!$request->kelas_tujuan_id) {
                    return response()->json([
                        'message' => 'Pilih kelas tujuan untuk migrasi',
                    ], 422);
                }
                $existing = Siswa::where('id_kelas', $request->kelas_tujuan_id)
                    ->where('nisn', $siswa->nisn)
                    ->exists();
                if ($existing) {
                    continue;
                }
                $siswa->update([
                    'id_kelas' => $request->kelas_tujuan_id,
                ]);
                $sukses++;
            } elseif ($request->action === 'alumni') {
                $siswa->update([
                    'is_alumni' => true,
                    'status' => 'non-aktif',
                    'id_kelas' => null,
                    'tanggal_kelulusan' => now(),
                ]);
                $sukses++;
            }
        }

        $message = $request->action === 'alumni' 
            ? "$sukses siswa berhasil dijadikan alumni" 
            : "$sukses data siswa berhasil dimigrasi";

        return response()->json([
            'message' => $message,
        ]);
    }
}