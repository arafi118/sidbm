<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SebutanDesa extends Model
{
    use HasFactory;

    protected $table = 'sebutan_desa';

    public function desa()
    {
        return $this->hasMany(Desa::class, 'sebutan', 'id');
    }
}
