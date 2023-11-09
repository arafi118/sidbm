<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class RealAngsuran extends Model
{
    use HasFactory;

    protected $table;
    public $timestamps = false;

    protected $guarded = [''];

    public function __construct()
    {
        $this->table = 'real_angsuran_' . Session::get('lokasi');
    }

    public function trx()
    {
        return $this->hasMany(Transaksi::class, 'idtp', 'id');
    }
}
