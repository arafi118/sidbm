<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AkunLevel1 extends Model
{
    use HasFactory;

    protected $table = 'akun_level_1';
    public $timestamps = false;

    protected $primaryKey = 'kode_akun';
    protected $keyType = 'string';

    public function akun2()
    {
        return $this->hasMany(AkunLevel2::class, 'parent_id', 'id')->orderBy('kode_akun', 'ASC');
    }

    public function saldo_awal()
    {
        return $this->belongsTo(Saldo::class, 'kode_akun', 'kode_akun')->where('bulan', '12')->orderBy('id', 'ASC');
    }

    public function saldo()
    {
        return $this->belongsTo(Saldo::class, 'kode_akun', 'kode_akun');
    }
}
