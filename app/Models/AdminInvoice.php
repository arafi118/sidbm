<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminInvoice extends Model
{
    use HasFactory;

    protected $table = 'admin_invoice';
    public $timestamps = false;

    protected $guarded = ['idv'];

    public function jp()
    {
        return $this->belongsTo(AdminJenisPembayaran::class, 'jenis_pembayaran', 'id');
    }

    public function trx()
    {
        return $this->hasMany(AdminTransaksi::class, 'idv', 'idv');
    }

    public function kec()
    {
        return $this->belongsTo(Kecamatan::class, 'lokasi', 'id');
    }

    public function getRouteKeyName()
    {
        return 'idv';
    }
}
