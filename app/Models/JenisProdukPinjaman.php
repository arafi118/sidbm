<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisProdukPinjaman extends Model
{
    use HasFactory;

    protected $table = 'jenis_produk_pinjaman';
    public $timestamps = false;

    public function pinjaman_kelompok()
    {
        return $this->hasMany(PinjamanKelompok::class, 'jenis_pp');
    }

    public function pinjaman_anggota()
    {
        return $this->hasMany(PinjamanAnggota::class, 'jenis_pp');
    }
}
