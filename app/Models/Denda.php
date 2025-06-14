<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';
    
    protected $fillable = [
        'peminjaman_siswa_id',
        'jumlah_denda',
        'status_denda',
        'tanggal_pembayaran',
        'keterangan'
    ];

    public function peminjamanSiswa()
    {
        return $this->belongsTo(PeminjamanSiswa::class, 'peminjaman_siswa_id');
    }
}
