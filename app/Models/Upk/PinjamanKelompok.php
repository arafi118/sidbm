<?php

namespace App\Models\Upk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class PinjamanKelompok extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table;
    protected $connection = 'upk';
    protected $guarded = ['id'];

    public function __construct()
    {
        $this->table = 'pinjaman_kelompok' . Session::get('id_kab');
    }

    public function kel()
    {
        return $this->belongsTo(Kelompok::class, 'id_kel', 'id');
    }

    public function pinj()
    {
        return $this->hasMany(PinjamanAnggota::class, 'id_pinkel', 'id');
    }
}
