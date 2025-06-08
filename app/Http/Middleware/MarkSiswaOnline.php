<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\SiswaOnline;
use Carbon\Carbon;
use App\Models\Siswa;

class MarkSiswaOnline
{
    public function handle($request, Closure $next)
    {
        // Ambil ID siswa dari session
        $siswa_id = $request->session()->get('id');

        // Pastikan ada ID siswa yang valid
        if ($siswa_id) {
            // Mengambil data siswa berdasarkan ID yang ada di session
            $siswa = Siswa::where('id', $siswa_id)->first();

            if ($siswa) {
                // Update atau buat entry baru di tabel SiswaOnline
                SiswaOnline::updateOrCreate(
                    ['siswa_id' => $siswa->id], // Gunakan ID siswa
                    ['last_active' => Carbon::now()], // Update waktu aktif terakhir
                );
            }
        }

        // Lanjutkan request ke middleware berikutnya
        return $next($request);
    }
}
