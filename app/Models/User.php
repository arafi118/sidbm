<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    public $timestamps = false;

    public function j()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan');
    }

    public function l()
    {
        return $this->belongsTo(Level::class, 'level');
    }

    public function p()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan', 'id');
    }

    public function kec()
    {
        return $this->belongsTo(Kecamatan::class, 'lokasi', 'id');
    }
}
