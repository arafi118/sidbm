<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'saldo';
    protected $connection = 'master';
    protected $guarded = ['id'];
}
