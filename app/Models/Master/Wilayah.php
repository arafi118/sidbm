<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'wilayah';
    protected $connection = 'master';
    protected $guarded = ['kode'];
}
