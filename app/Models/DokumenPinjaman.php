<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class DokumenPinjaman extends Model
{
    use HasFactory;
    protected $table = 'dokumen_pinjaman';

    public function tanda_tangan()
    {
        return $this->hasOne(TandaTanganDokumen::class, 'dokumen_pinjaman_id')->where('lokasi', Session::get('lokasi'));
    }
}
