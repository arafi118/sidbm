<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminBuku extends Model
{
    use HasFactory;

    protected $table = 'admin_buku';
    public $timestamps = false;

    protected $guarded = ['id'];
}
