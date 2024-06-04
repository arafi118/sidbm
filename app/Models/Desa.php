<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'desa';
    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'kd_desa';
    }

    public function sebutan_desa()
    {
        return $this->belongsTo(SebutanDesa::class, 'sebutan');
    }

    public function kelompok()
    {
        return $this->hasMany(Kelompok::class, 'desa', 'kd_desa')->orderBy('desa', 'ASC');
    }

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'desa', 'kd_desa')->orderBy('nik', 'ASC');
    }

    public function kom_saldo()
    {
        return $this->hasMany(Saldo::class, 'kode_akun', 'kode_desa');
    }

    public function saldo()
    {
        return $this->hasOne(Saldo::class, 'kode_akun', 'kode_desa');
    }

    public function kec()
    {
        return $this->belongsTo(Kecamatan::class, 'kd_kec', 'kd_kec');
    }
}
