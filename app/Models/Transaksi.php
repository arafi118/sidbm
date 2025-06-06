<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Awobaz\Compoships\Compoships;
use Session;

class Transaksi extends Model
{
    use HasFactory, Compoships;
    protected $table;
    public $timestamps = false;

    protected $primaryKey = 'idt';
    protected $guarded = ['idt'];

    public function __construct()
    {
        $this->table = 'transaksi_' . Session::get('lokasi');
    }

    public function angs()
    {
        return $this->hasMany(Transaksi::class, ['idtp', 'tgl_transaksi'], ['idtp', 'tgl_transaksi']);
    }

    public function kas_angs()
    {
        return $this->hasMany(Transaksi::class, ['idtp', 'tgl_transaksi', 'rekening_debit'], ['idtp', 'tgl_transaksi', 'rekening_debit']);
    }

    public function rek_debit()
    {
        return $this->belongsTo(Rekening::class, 'rekening_debit', 'kode_akun');
    }

    public function rek_kredit()
    {
        return $this->belongsTo(Rekening::class, 'rekening_kredit', 'kode_akun');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function tr_idtp()
    {
        return $this->hasMany(Transaksi::class, 'idtp', 'idtp');
    }

    public function pinkel()
    {
        return $this->belongsTo(PinjamanKelompok::class, 'id_pinj');
    }
}
