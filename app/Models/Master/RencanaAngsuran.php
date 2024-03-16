<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaAngsuran extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'rencana_angsuran';
    protected $connection = 'master';
    protected $guarded = ['id'];
}
