<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'price', 'stock', 'neto', 'pieces'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function photos()
    {
        return $this->hasMany(PhotoProduct::class, 'id_product');
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
