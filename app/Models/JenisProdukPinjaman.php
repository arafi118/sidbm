<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisProdukPinjaman extends Model
{
    use HasFactory;

    protected $table = 'jenis_produk_pinjaman';
    public $timestamps = false;
}
