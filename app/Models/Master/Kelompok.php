<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'kelompok';
    protected $connection = 'master';
    protected $guarded = ['id'];
}
