<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table;
    public $timestamps = false;

    protected $primaryKey = 'idt';
    protected $guarded = ['idt'];

    public function __construct()
    {
        $this->table = 'transaksi_' . Auth::user()->lokasi;
    }
}
