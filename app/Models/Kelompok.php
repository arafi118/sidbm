<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class Kelompok extends Model
{
    use HasFactory;

    protected $table;
    public $timestamps = false;

    protected $guarded = ['id'];

    public function __construct()
    {
        $this->table = 'kelompok_' . Session::get('lokasi');
    }

    public function getRouteKeyName()
    {
        return 'kd_kelompok';
    }

    public function jenis_pp()
    {
        return $this->belongsTo(JenisProdukPinjaman::class, 'jenis_produk_pinjaman');
    }

    public function usaha()
    {
        return $this->belongsTo(JenisUsaha::class, 'jenis_usaha');
    }

    public function kegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan');
    }

    public function tk()
    {
        return $this->belongsTo(TingkatKelompok::class, 'tingkat_kelompok');
    }

    public function fk()
    {
        return $this->belongsTo(FungsiKelompok::class, 'fungsi_kelompok');
    }

    public function pinkel()
    {
        return $this->hasMany(PinjamanKelompok::class, 'id_kel', 'id')->orderBy('id', 'ASC');
    }

    public function pinjaman()
    {
        return $this->hasOne(PinjamanKelompok::class, 'id_kel', 'id');
    }

    public function d()
    {
        return $this->belongsTo(Desa::class, 'desa', 'kd_desa');
    }
}
