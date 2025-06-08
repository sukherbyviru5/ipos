<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Foundation\AdminFoundation as Authenticatable;;

class Admin extends Authenticatable
{
    use HasFactory;
    protected $table = 'admin';
    protected $fillable = [
        'id', 'nip_nik_nisn', 'nama', 'password', 'created_at', 'updated_at'
    ];
    protected $hidden = ['password'];
}
