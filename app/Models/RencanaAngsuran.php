<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class RencanaAngsuran extends Model
{
    use HasFactory;

    protected $table;
    public $timestamps = false;

    protected $guarded = ['id'];

    public function __construct()
    {
        $this->table = 'rencana_angsuran_' . Session::get('lokasi');
    }

    public function real()
    {
        return $this->hasMany(RealAngsuran::class, 'loan_id', 'loan_id');
    }
}
