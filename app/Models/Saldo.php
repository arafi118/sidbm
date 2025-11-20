<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Model;
use \Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\BelongsTo;
use Session;

class Saldo extends Model
{
    use TenantAware, Compoships;

    protected $baseTable = 'saldo';
    public $timestamps = false;

    protected $fillable = ['id', 'kode_akun', 'lokasi', 'tahun', 'bulan', 'debit', 'kredit'];

    public function eb()
    {
        return $this->belongsTo(Ebudgeting::class, ['kode_akun', 'tahun', 'bulan'], ['kode_akun', 'tahun', 'bulan']);
    }

    public function saldo()
    {
        return $this->belongsTo(Saldo::class, 'kode_akun', 'kode_akun');
    }

    public function rek()
    {
        return $this->belongsTo(Rekening::class, 'kode_akun', 'kode_akun');
    }
}
