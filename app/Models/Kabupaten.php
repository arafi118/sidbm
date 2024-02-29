<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Kabupaten extends Authenticatable
{
    use HasFactory;

    protected $table = 'kabupaten';

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'kd_prov', 'kode');
    }

    public function kec()
    {
        return $this->hasMany(Kecamatan::class, 'kd_kab', 'kd_kab');
    }
}
