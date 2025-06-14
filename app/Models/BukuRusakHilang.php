<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuRusakHilang extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buku_rusak_hilang';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [''];

    /**
     * Get the student that owns the loan record.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nik_siswa', 'nik');
    }

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