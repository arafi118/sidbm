<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;
    protected $table;
    public $timestamps = false;

    protected $guarded = ['id'];

    public function __construct()
    {
        $this->table = 'anggota_' . Auth::user()->lokasi;
    }

    public function pinjaman_anggota()
    {
        return $this->hasMany(PinjamanAnggota::class, 'nia');
    }

    public function pinjaman()
    {
        return $this->hasOne(PinjamanAnggota::class, 'nia');
    }

    public function getRouteKeyName()
    {
        return 'nik';
    }
}
