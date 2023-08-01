<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;


class AkunLevel1 extends Model
{
    use HasFactory, Compoships;

    protected $table = 'akun_level_1';
    public $timestamps = false;

    protected $primaryKey = 'kode_akun';
    protected $keyType = 'string';

    public function akun2()
    {
        return $this->hasMany(AkunLevel2::class, 'lev1', 'lev1')->orderBy('kode_akun', 'ASC');
    }
}
