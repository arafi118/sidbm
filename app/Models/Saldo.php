<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;
    protected $table;
    public $timestamps = false;

    public function __construct()
    {
        $this->table = 'saldo_' . Auth::user()->lokasi;
    }

    protected $fillable = ['id', 'kode_akun', 'lokasi', 'tahun', 'bulan', 'debit', 'kredit'];
}
