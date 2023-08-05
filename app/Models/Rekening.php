<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Awobaz\Compoships\Compoships;

class Rekening extends Model
{
    use HasFactory, Compoships;
    protected $table;
    public $timestamps = false;

    protected $guarded = ['id'];

    public function __construct()
    {
        $this->table = 'rekening_' . Auth::user()->lokasi;
    }

    public function trx_debit()
    {
        return $this->hasMany(Transaksi::class, 'rekening_debit', 'kode_akun');
    }

    public function trx_kredit()
    {
        return $this->hasMany(Transaksi::class, 'rekening_kredit', 'kode_akun');
    }
}
