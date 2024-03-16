<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'desa';
    protected $connection = 'master';
    protected $guarded = ['kode'];
}
