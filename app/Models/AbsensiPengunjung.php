<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbsensiPengunjung extends Model
{
    use HasFactory;
    protected $table = 'absensi_pengunjungs';
    protected $guarded = [''];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function siswaNik()
    {
        return $this->belongsTo(Siswa::class, 'nik_siswa', 'nik');
    }

    public function guruNik()
    {
        return $this->belongsTo(Guru::class, 'nik_guru', 'nik');
    }

    public static function add($nik, $kelas = null, $guru_id = null, $materi = null, $is_kunjungan_kelas = false)
    {
        if (!$nik) {
            return false;
        }

        $isSiswa = Siswa::where('nik', $nik)->exists();

        $isGuru = !$isSiswa && Guru::where('nik', $nik)->exists();

        if (!$isSiswa && !$isGuru) {
            return false;
        }

        if (
            self::where($isSiswa ? 'nik_siswa' : 'nik_guru', $nik)
                ->whereDate('created_at', today())
                ->exists()
        ) {
            return false;
        }

        return DB::table('absensi_pengunjungs')->insert([
            'nik_siswa' => $isSiswa ? $nik : null,
            'nik_guru' => $isGuru ? $nik : null,
            'kelas_id' => $kelas ? $kelas : null,
            'guru_id' => $guru_id ? $guru_id : null,
            'materi' => $materi ? $materi : null,
            'is_kunjungan_kelas' => $is_kunjungan_kelas ? $is_kunjungan_kelas : false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
