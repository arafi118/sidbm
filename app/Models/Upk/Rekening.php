<?php

namespace App\Models\Upk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class Rekening extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table;
    protected $connection = 'upk';
    protected $guarded = ['kd_rekening'];

    public function __construct()
    {
        $this->table = 'rekening_' . Session::get('lokasi');
    }
}
