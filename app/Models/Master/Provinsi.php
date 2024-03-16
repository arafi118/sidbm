<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'provinsi';
    protected $connection = 'master';
    protected $guarded = ['kode'];
}
