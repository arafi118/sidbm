<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeAkun extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'kode_akun';
    protected $connection = 'master';
    protected $guarded = ['kode_akun'];
}
