<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class AkunLevel3 extends Model
{
    use HasFactory, Compoships;

    protected $table = 'akun_level_3';
    public $timestamps = false;

    protected $primaryKey = 'kode_akun';
    protected $keyType = 'string';

    public function rek()
    {
        return $this->hasMany(Rekening::class, ['lev1', 'lev2', 'lev3'], ['lev1', 'lev2', 'lev3'])->orderBy('kode_akun', 'ASC');
    }
}
