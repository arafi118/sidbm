<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminJenisPembayaran extends Model
{
    use HasFactory;

    protected $table = 'admin_jenis_pembayaran';
    public $timestamps = false;

    protected $guarded = ['id'];
}
