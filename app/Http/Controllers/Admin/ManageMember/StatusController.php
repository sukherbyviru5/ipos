<?php

namespace App\Http\Controllers\Admin\ManageMember;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        $kelas_id = $request->get('kelas_id');
        $action = $request->get('action', 'aktif');

        $query = Siswa::query();

        // Filter by kelas_id if provided and action is not alumni
        if ($kelas_id && $action !== 'alumni') {
            $query->where('id_kelas', $kelas_id);
        }

        // Filter by status based on action
        if ($action === 'alumni') {
            $query->where('is_alumni', true);
            if ($request->has('tahun_kelulusan') && $request->tahun_kelulusan) {
                $query->whereYear('tanggal_kelulusan', $request->tahun_kelulusan);
            }
        } else {
            $query->where('status', $action)->where('is_alumni', false);
        }

        $data = [
            'kelas' => Kelas::get(),
            'siswa' => $query->get(),
            'action' => $action,
            'kelas_id' => $kelas_id,
            'tahun_kelulusan' => $request->tahun_kelulusan,
            'tahun_list' => Siswa::whereNotNull('tanggal_kelulusan')
                ->selectRaw('YEAR(tanggal_kelulusan) as tahun')
                ->distinct()
                ->pluck('tahun')
                ->sort()
                ->values(),
        ];

        return view('admin.manage_member.status_siswa.index', $data)->with('sb', 'Status Siswa');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|array|min:1',
            'siswa_id.*' => 'exists:siswa,id',
            'action' => 'required|in:aktif,non-aktif,alumni',
            'kelas_id' => 'required_if:action,aktif,non-aktif|exists:kelas,id',
            'tahun_kelulusan' => 'required_if:action,alumni|nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        $sukses = 0;
        $skipped = [];

        DB::beginTransaction();
        try {
            foreach ($request->siswa_id as $s) {
                $siswa = Siswa::find($s);
                if (!$siswa) {
                    $skipped[] = $s;
                    continue;
                }

                if ($request->action === 'alumni') {
                    
                    $siswa->update([
                        'is_alumni' => true,
                        'status' => 'non-aktif',
                        'id_kelas' => null,
                        'tanggal_kelulusan' => $request->tahun_kelulusan . '-06-30',
                    ]);
                    $sukses++;
                } else {
                    $updateData = [
                        'status' => $request->action,
                    ];

                    if ($request->action === 'aktif') {
                        if ($siswa->is_alumni) {
                            $updateData['is_alumni'] = false;
                            $updateData['id_kelas'] = $request->kelas_id;
                            $updateData['tanggal_kelulusan'] = null;
                        } else {
                            $updateData['id_kelas'] = $request->kelas_id;
                        }
                    } elseif ($request->action === 'non-aktif') {
                        $updateData['id_kelas'] = $request->kelas_id;
                    }

                    $siswa->update($updateData);
                    $sukses++;
                }
            }

            DB::commit();

            $message = match ($request->action) {
                'alumni' => "$sukses siswa berhasil dijadikan alumni",
                'aktif' => "$sukses siswa berhasil diaktifkan",
                'non-aktif' => "$sukses siswa berhasil dinonaktifkan",
            };

            if (!empty($skipped)) {
                $message  .= ". Skipped: " . implode(', ', $skipped) . " (data tidak ditemukan atau sudah alumni)";
            }

            return response()->json([
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}