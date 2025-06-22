<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Foundation\AdminFoundation as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;
    protected $table = 'admin';
    protected $fillable = [
        'id', 'nip_nik_nisn', 'nama', 'password', 'created_at', 'updated_at'
    ];
    protected $hidden = ['password'];

    static function getAdminById($id)
    {
        return self::where('id', $id)->first();
    }
    static function getAdminByNipNikNisn($nip_nik_nisn)
    {
        return self::where('nip_nik_nisn', $nip_nik_nisn)->first();
    }

    static function getall()
    {
        return self::where('nip_nik_nisn', session('nip_nik_nisn'))->first();
    }
}
