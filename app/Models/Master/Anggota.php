<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'anggota';
    protected $connection = 'master';
    protected $guarded = ['id'];
}
