<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrBuku extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qr_buku';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [''];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function peminjamanSiswa()
    {
        return $this->hasMany(PeminjamanSiswa::class, 'id_qr', 'id');
    }
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id');
    }
    
}