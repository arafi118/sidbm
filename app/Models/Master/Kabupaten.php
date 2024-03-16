<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'kabupaten';
    protected $connection = 'master';
    protected $guarded = ['kode'];
}
