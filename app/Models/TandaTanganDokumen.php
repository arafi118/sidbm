<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TandaTanganDokumen extends Model
{
    use HasFactory;
    protected $table = 'tanda_tangan_dokumen';

    protected $guarded = ['id'];
    public $timestamps = false;
}
