<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminTransaksi extends Model
{
    use HasFactory;

    protected $table = 'admin_transaksi';
    public $timestamps = false;

    protected $guarded = ['idt'];
}
