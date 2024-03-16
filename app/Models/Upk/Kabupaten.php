<?php

namespace App\Models\Upk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'kabupaten';
    protected $connection = 'upk';
    protected $guarded = ['id'];
}
