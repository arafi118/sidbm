<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Awobaz\Compoships\Compoships;
use Session;

class Rekening extends Model
{
    use HasFactory, Compoships;
    protected $table;
    public $timestamps = false;

    protected $guarded = ['id'];

    public function __construct()
    {
        $this->table = 'rekening_' . Session::get('lokasi');
    }

    public function trx_debit()
    {
        return $this->hasMany(Transaksi::class, 'rekening_debit', 'kode_akun');
    }

    public function trx_kredit()
    {
        return $this->hasMany(Transaksi::class, 'rekening_kredit', 'kode_akun');
    }

    public function inventaris()
    {
        return $this->hasMany(Inventaris::class, 'kategori', 'lev4');
    }

    public function saldo()
    {
        return $this->hasOne(Saldo::class, 'kode_akun', 'kode_akun');
    }

    public function kom_saldo()
    {
        return $this->hasMany(Saldo::class, 'kode_akun', 'kode_akun');
    }

    public function kom_eb()
    {
        return $this->hasMany(Ebudgeting::class, 'kode_akun', 'kode_akun');
    }

    public function eb()
    {
        return $this->hasOne(Ebudgeting::class, 'kode_akun', 'kode_akun');
    }

    public function getRouteKeyName()
    {
        return 'kode_akun';
    }
}
