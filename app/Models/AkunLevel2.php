<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunLevel2 extends Model
{
    use HasFactory;

    protected $table = 'akun_level_2';
    public $timestamps = false;

    protected $primaryKey = 'kode_akun';
    protected $keyType = 'string';

    public function akun3()
    {
        return $this->hasMany(AkunLevel3::class, 'parent_id', 'id')->orderBy('kode_akun', 'ASC');
    }

    public function rek()
    {
        return $this->hasMany(Rekening::class, 'lev1', 'lev1');
    }
}
