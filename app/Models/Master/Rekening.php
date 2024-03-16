<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'rekening';
    protected $connection = 'master';
    protected $guarded = ['kode_akun'];
}
