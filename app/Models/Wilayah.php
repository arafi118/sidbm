<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    public $timestamps = false;

    use HasFactory;

    public function kab()
    {
        return $this->hasMany(Kabupaten::class, 'kd_prov', 'kode');
    }

    public function kec()
    {
        return $this->hasOne(Kecamatan::class, 'kd_kec', 'kode');
    }
}
