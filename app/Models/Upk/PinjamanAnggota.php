<?php

namespace App\Models\Upk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class PinjamanAnggota extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table;
    protected $connection = 'upk';
    protected $guarded = ['id'];

    public function __construct()
    {
        $this->table = 'pinjaman_anggota_' . Session::get('id_kab');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'nia', 'id');
    }

    public function pinkel()
    {
        return $this->belongsTo(PinjamanKelompok::class, 'id_pinkel', 'id');
    }
}
