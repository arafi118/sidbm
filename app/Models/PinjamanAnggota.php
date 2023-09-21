<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanAnggota extends Model
{
    use HasFactory;
    protected $table;
    public $timestamps = false;

    protected $guarded = ['id'];

    public function __construct()
    {
        $this->table = 'pinjaman_anggota_' . Auth::user()->lokasi;
    }

    public function sts()
    {
        return $this->belongsTo(StatusPinjaman::class, 'status', 'kd_status');
    }

    public function pinkel()
    {
        return $this->belongsTo(PinjamanKelompok::class, 'id_pinkel');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kel');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'nia', 'id');
    }

    public function pinjaman()
    {
        return $this->hasOne(PinjamanAnggota::class, 'nia', 'nia');
    }

    public function pemanfaat()
    {
        return $this->hasOne(DataPemanfaat::class, 'nia', 'nia');
    }
}
