<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogAktivitasGuru extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas_gurus';
    protected $guarded = [''];

    static function add($nik_guru = null, $aktivitas = null, $id_buku = null)
    {
        if(session('is_guru')) {
            DB::table('log_aktivitas_gurus')->insert([
                'nik_guru' => $nik_guru,
                'aktivitas' => $aktivitas,
                'id_buku' => $id_buku,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public static function getall()
    {
        return DB::select("SELECT nik_guru, aktivitas, created_at FROM log_aktivitas_gurus");
    }

}
