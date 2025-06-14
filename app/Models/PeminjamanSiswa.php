<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanSiswa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'peminjaman_siswa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nik_siswa',
        'id_qr',
        'kode',
        'grup',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'perpanjangan_count',
        'denda_total',
        'status_peminjaman',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_kembali' => 'date',
        'perpanjangan_count' => 'integer',
        'denda_total' => 'decimal:2',
        'status_peminjaman' => 'string',
    ];

    /**
     * Get the student that owns the loan record.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nik_siswa', 'nik');
    }

    /**
     * Get the book QR code associated with the loan record.
     */
    public function qrBuku()
    {
        return $this->belongsTo(QrBuku::class, 'id_qr', 'id');
    }


    public function denda()
    {
        return $this->hasOne(Denda::class, 'peminjaman_siswa_id');
    }
}