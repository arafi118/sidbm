<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Model;
use Session;

class PinjamanKelompok extends Model
{
    use TenantAware;
    protected $baseTable = 'pinjaman_kelompok';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function jpp()
    {
        return $this->belongsTo(JenisProdukPinjaman::class, 'jenis_pp');
    }

    public function jasa()
    {
        return $this->belongsTo(JenisJasa::class, 'jenis_jasa');
    }

    public function angsuran_pokok()
    {
        return $this->belongsTo(SistemAngsuran::class, 'sistem_angsuran');
    }

    public function angsuran_jasa()
    {
        return $this->belongsTo(SistemAngsuran::class, 'sa_jasa');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kel', 'id');
    }

    public function sts()
    {
        return $this->belongsTo(StatusPinjaman::class, 'status', 'kd_status');
    }

    public function ra()
    {
        return $this->hasMany(RencanaAngsuran::class, 'loan_id')->orderBy('angsuran_ke', 'ASC');
    }

    public function real()
    {
        return $this->hasMany(RealAngsuran::class, 'loan_id')->orderBy('tgl_transaksi', 'ASC')->orderBy('id', 'ASC');
    }

    public function pinjaman_anggota()
    {
        return $this->hasMany(PinjamanAnggota::class, 'id_pinkel')->orderBy('id', 'asc');
    }

    public function pinjaman()
    {
        return $this->hasOne(PinjamanAnggota::class, 'id_pinkel')->orderBy('id', 'asc');
    }

    public function sis_pokok()
    {
        return $this->belongsTo(SistemAngsuran::class, 'sistem_angsuran');
    }

    public function sis_jasa()
    {
        return $this->belongsTo(SistemAngsuran::class, 'sa_jasa');
    }

    public function saldo()
    {
        return $this->hasOne(RealAngsuran::class, 'loan_id')->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC');
    }

    public function saldo2()
    {
        return $this->hasOne(RealAngsuran::class, 'loan_id')->orderBy('tgl_transaksi', 'ASC')->orderBy('id', 'ASC');
    }

    public function target()
    {
        return $this->hasOne(RencanaAngsuran::class, 'loan_id')->orderBy('jatuh_tempo', 'DESC');
    }

    public function rencana()
    {
        return $this->hasMany(RencanaAngsuran::class, 'loan_id')->orderBy('jatuh_tempo', 'ASC');
    }

    public function rencana1()
    {
        return $this->hasOne(RencanaAngsuran::class, 'loan_id')->orderBy('jatuh_tempo', 'ASC');
    }

    public function trx()
    {
        return $this->hasMany(Transaksi::class, 'id_pinj', 'id')->orderBy('tgl_transaksi', 'ASC')->orderBy('idtp', 'ASC');
    }

    public function trx_penghapusan()
    {
        return $this->hasMany(Transaksi::class, 'id_pinj', 'id')->where('rekening_debit', 'LIKE', '1.1.04%')->orderBy('tgl_transaksi', 'DESC')->orderBy('idtp', 'DESC');
    }

    public function saldo_pinjaman()
    {
        return $this->hasOne(Penghapusan::class, 'id_pinj', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pinkel()
    {
        return $this->hasOne(PinjamanKelompok::class, 'id_kel', 'id_kel')->orderBy('tgl_cair', 'DESC');
    }
}
