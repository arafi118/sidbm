<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'transaksi';
    protected $connection = 'master';
    protected $guarded = ['idt'];
}
