<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Model;
use Session;

class Anggota extends Model
{
    use TenantAware;
    protected $baseTable = 'anggota';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function pinjaman_anggota()
    {
        return $this->hasMany(PinjamanAnggota::class, 'nia');
    }

    public function pinjaman()
    {
        return $this->hasOne(PinjamanAnggota::class, 'nia');
    }

    public function d()
    {
        return $this->belongsTo(Desa::class, 'desa', 'kd_desa');
    }

    public function pemanfaat()
    {
        return $this->hasOne(DataPemanfaat::class, 'nik', 'nik');
    }

    public function u()
    {
        return $this->belongsTo(Usaha::class, 'usaha', 'id');
    }

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'hubungan', 'id');
    }

    public function getRouteKeyName()
    {
        return 'nik';
    }
}
