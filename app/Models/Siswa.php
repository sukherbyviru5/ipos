<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Foundation\SiswaFoundation as Authenticatable;

class Siswa extends Authenticatable
{
    use HasFactory;
    protected $table = 'siswa';
    protected $guarded = [];

    protected $hidden = ['password'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id');
    }

    static function getSiswaById($id)
    {
        return self::where('id', $id)->first();
    }
    static function getSiswaByNik($nik)
    {
        return self::where('nik', $nik)->first();
    }
    static function getSiswaByNisn($nisn)
    {
        return self::where('nisn', $nisn)->first();
    }
    static function getall()
    {
        return self::where('nik', session('nip_nik_nisn'))->with(['kelas'])->first();
    }
}
