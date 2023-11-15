<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class Penghapusan extends Model
{
    use HasFactory;
    protected $table;
    public $timestamps = false;

    public function __construct()
    {
        $this->table = 'penghapusan';
    }

    protected $guarded = ['id'];

    public function kec()
    {
        return $this->belongsTo(Kecamatan::class, 'lokasi', 'id');
    }

    public function pinkel()
    {
        return $this->belongsTo(PinjamanKelompok::class, 'id_pinj', 'id');
    }

    public function pinj()
    {
        return $this->belongsTo(PinjamanAnggota::class, 'id_pinj_i', 'id');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'nia', 'id');
    }
}
