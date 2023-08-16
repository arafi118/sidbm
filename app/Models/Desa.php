<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'desa';
    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'kd_desa';
    }

    public function sebutan_desa()
    {
        return $this->belongsTo(SebutanDesa::class, 'sebutan');
    }

    public function kelompok()
    {
        return $this->hasMany(Kelompok::class, 'desa', 'kd_desa');
    }
}
