<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';
    protected $fillable = [
        'nik',
        'nip',
        'password',
        'nama_guru',
        'nama_mata_pelajaran',
        'qr_code',
        'status',
    ];
    use HasFactory;


    static function getSiswaById($id)
    {
        return self::where('id', $id)->first();
    }
    static function getSiswaByNik($nik)
    {
        return self::where('nik', $nik)->first();
    }
    static function getSiswaByNip($nip)
    {
        return self::where('nip', $nip)->first();
    }
}
