<?php

namespace App\Models\Upk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'kecamatan';
    protected $connection = 'upk';
    protected $guarded = ['id'];
}
