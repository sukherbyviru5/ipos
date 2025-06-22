<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Foundation\GuruFoundation as Authenticatable;

class Guru extends Authenticatable
{
    use HasFactory;
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
    protected $hidden = ['password'];

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
     static function getall()
    {
        return self::where('nik', session('nip_nik_nisn'))->first();
    }
}
