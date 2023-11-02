<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRekening extends Model
{
    use HasFactory;

    protected $table = 'admin_rekening';
    public $timestamps = false;

    protected $guarded = ['id'];
}
