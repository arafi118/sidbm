<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealAngsuran extends Model
{
    use HasFactory;

    protected $table;
    public $timestamps = false;

    protected $guarded = [''];

    public function __construct()
    {
        $this->table = 'real_angsuran_' . Auth::user()->lokasi;
    }

    public function trx()
    {
        return $this->hasMany(Transaksi::class, 'idtp', 'id');
    }
}
