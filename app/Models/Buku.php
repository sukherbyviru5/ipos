<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Termwind\Components\Dd;

class Buku extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buku';

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

    public function ddc_buku()
    {
        return $this->belongsTo(DdcBuku::class, 'id_ddc');
    }

    public function kategori_buku()
    {
        return $this->belongsTo(KategoriBuku::class, 'id_kategori');
    }

    public function kondisi_buku()
    {
        return $this->belongsTo(KondisiBuku::class, 'id_kondisi');
    }

    public function jenis_buku()
    {
        return $this->belongsTo(JenisBuku::class, 'id_jenis');
    }
   
    

    static function generateKodeBuku($ddc, $kategori, $penerbit, $singkatan, $noStok = null)
    {
        $ddc = DdcBuku::find($ddc)->no_klasifikasi ?? rand(100, 999);
        $kategori = KategoriBuku::find($kategori)->no_urut ?? rand(100, 999);
        $singkatan = $singkatan ?? substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
        $penerbit = $penerbit ?? rand(100, 999) . '-fake';
        return $ddc . '.' . $kategori . '.' . $penerbit . '.' . $singkatan . '.' . $noStok;
    }


}