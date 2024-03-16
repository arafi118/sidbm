<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'inventaris';
    protected $connection = 'master';
    protected $guarded = ['id'];
}
