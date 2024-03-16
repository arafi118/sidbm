<?php

namespace App\Models\Upk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class Kelompok extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table;
    protected $connection = 'upk';
    protected $guarded = ['id'];

    public function __construct()
    {
        $this->table = 'kelompok_' . Session::get('id_kab');
    }
}
