<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanKelompok extends Model
{
    use HasFactory;
    protected $table;
    public $timestamps = false;

    protected $guarded = ['id'];

    public function __construct()
    {
        $this->table = 'pinjaman_kelompok_' . Auth::user()->lokasi;
    }

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

    public function trx()
    {
        return $this->hasMany(Transaksi::class, 'id_pinj', 'id')->orderBy('tgl_transaksi', 'ASC')->orderBy('idtp', 'ASC');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
