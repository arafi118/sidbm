<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Model;

class PinjamanAnggota extends Model
{
    use TenantAware;

    protected $baseTable = 'pinjaman_anggota';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function sts()
    {
        return $this->belongsTo(StatusPinjaman::class, 'status', 'kd_status');
    }

    public function sis_pokok()
    {
        return $this->belongsTo(SistemAngsuran::class, 'sistem_angsuran');
    }

    public function sis_jasa()
    {
        return $this->belongsTo(SistemAngsuran::class, 'sa_jasa');
    }

    public function pinkel()
    {
        return $this->belongsTo(PinjamanKelompok::class, 'id_pinkel');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kel');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'nia', 'id');
    }

    public function pinjaman()
    {
        return $this->hasOne(PinjamanAnggota::class, 'nia', 'nia');
    }

    public function pemanfaat()
    {
        return $this->hasOne(DataPemanfaat::class, 'nia', 'nia');
    }

    public function pinj_ang()
    {
        return $this->hasOne(PinjamanAnggota::class, 'nia', 'nia')->orderBy('tgl_cair', 'DESC');
    }

    public function pinjaman_lain()
    {
        return $this->hasMany(PinjamanAnggota::class, 'nia', 'nia')->orderBy('tgl_cair', 'DESC');
    }

    public function trx()
    {
        return $this->hasMany(Transaksi::class, 'id_pinj_i', 'id')->orderBy('tgl_transaksi', 'ASC')->orderBy('idtp', 'ASC');
    }
}
