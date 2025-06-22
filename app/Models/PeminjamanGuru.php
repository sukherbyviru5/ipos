<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanGuru extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'peminjaman_guru';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nik_guru',
        'id_qr',
        'kode',
        'grup',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'perpanjangan_count',
        'status_peminjaman',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'perpanjangan_count' => 'integer',
        'status_peminjaman' => 'string',
    ];

    /**
     * Get the student that owns the loan record.
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'nik_guru', 'nik');
    }

    /**
     * Get the book QR code associated with the loan record.
     */
    public function qrBuku()
    {
        return $this->belongsTo(QrBuku::class, 'id_qr', 'id');
    }


   
}