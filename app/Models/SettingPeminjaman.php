<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingPeminjaman extends Model
{
    protected $table = 'setting_peminjaman';

    protected $fillable = [
        'batas_jumlah_buku_status',
        'batas_jumlah_buku',
        'lama_peminjaman_status',
        'lama_peminjaman',
        'lama_perpanjangan_status',
        'lama_perpanjangan',
        'batas_perpanjangan_status',
        'batas_perpanjangan',
        'denda_telat_status',
        'denda_telat',
        'perhitungan_denda',
        'syarat_peminjaman',
        'syarat_perpanjangan',
        'syarat_pengembalian',
        'sanksi_kerusakan',
    ];
}