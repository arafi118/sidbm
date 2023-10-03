<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TandaTanganLaporan extends Model
{
    use HasFactory;
    protected $table = 'tanda_tangan_laporan';
    public $timestamps = false;

    protected $guarded = ['id'];
}
