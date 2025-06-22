<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LembarBuku extends Model
{
    use HasFactory;

    protected $table = 'lembar_buku';

    protected $fillable = ['id_buku', 'no_urut', 'image'];
}