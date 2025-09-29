<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoProduct extends Model
{
    use HasFactory;
    protected $table = 'photo_product';
    protected $guarded = [''];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id');
    }
}
