<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;
    protected $table = 'saldo';
    public $timestamps = false;

    protected $fillable = ['id', 'kode_akun', 'lokasi', 'tahun', 'bulan', 'debit', 'kredit'];
}
