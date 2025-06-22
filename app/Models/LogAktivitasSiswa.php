<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogAktivitasSiswa extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas_siswas';
    protected $guarded = [''];

    static function add($nik_siswa = null, $aktivitas = null, $id_buku = null)
    {
        if(session('is_siswa')) {
            DB::table('log_aktivitas_siswas')->insert([
                'nik_siswa' => $nik_siswa,
                'aktivitas' => $aktivitas,
                'id_buku' => $id_buku,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public static function getall()
    {
        return DB::select("SELECT nik_siswa, aktivitas, created_at FROM log_aktivitas_siswas");
    }


    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nik_siswa', 'nik');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id');
    }

}
