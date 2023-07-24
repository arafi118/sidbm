<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPinjaman extends Model
{
    use HasFactory;
    protected $table = 'status_pinjaman';
    public $timestamps = false;

    protected $guarded = ['id'];
}
