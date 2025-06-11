<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $guarded = [];
    use HasFactory;

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
}
