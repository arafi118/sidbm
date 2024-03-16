<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanAnggota extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'pinjaman_anggota';
    protected $connection = 'master';
    protected $guarded = ['id'];
}
