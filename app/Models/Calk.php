<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calk extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'calk';
    protected $guarded = ['id'];

    public function kec()
    {
        return $this->hasMany(Kecamatan::class, 'lokasi', 'id');
    }
}
