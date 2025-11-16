<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Model;
use Session;

class RealAngsuran extends Model
{
    use TenantAware;

    protected $baseTable  = 'real_angsuran';
    public $timestamps = false;

    protected $guarded = [''];

    public function trx()
    {
        return $this->hasMany(Transaksi::class, 'idtp', 'id');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'idtp', 'id');
    }
}
