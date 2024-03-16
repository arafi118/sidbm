<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebudgeting extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'ebudgeting';
    protected $connection = 'master';
    protected $guarded = ['id'];
}
