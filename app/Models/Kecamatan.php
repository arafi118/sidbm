<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatan';

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kd_kab', 'kd_kab');
    }

    public function desa()
    {
        return $this->hasMany(Desa::class, 'kd_kec', 'kd_kec');
    }

    public function ttd()
    {
        return $this->belongsTo(TandaTanganLaporan::class, 'id', 'lokasi');
    }
}
