<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personalia extends Model
{
    use HasFactory;

    protected $table = 'personalia';
    public $timestamps = false;

    protected $guarded = ['id'];
}
