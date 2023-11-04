<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\BelongsTo;

class Saldo extends Model
{
    use HasFactory, Compoships;
    protected $table;
    public $timestamps = false;

    public function __construct()
    {
        $this->table = 'saldo_' . Auth::user()->lokasi;
    }

    protected $fillable = ['id', 'kode_akun', 'lokasi', 'tahun', 'bulan', 'debit', 'kredit'];

    public function eb()
    {
        return $this->belongsTo(Ebudgeting::class, ['kode_akun', 'tahun', 'bulan'], ['kode_akun', 'tahun', 'bulan']);
    }
}
