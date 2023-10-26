<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArusKas extends Model
{
    use HasFactory;
    protected $table = 'arus_kas';

    public function child()
    {
        return $this->hasMany(ArusKas::class, 'sub', 'id')->orderBy('id', 'ASC');
    }
}
