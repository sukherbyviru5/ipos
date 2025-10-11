<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'percent', 'product_id', 'status'];

    static function getCode($code) {
        $voucher = static::where('code', $code)->first();
        if($voucher) {
            return $voucher->percent;
        }
        return '0';
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
