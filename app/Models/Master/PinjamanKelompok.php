<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanKelompok extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'pinjaman_kelompok';
    protected $connection = 'master';
    protected $guarded = ['id'];
}
