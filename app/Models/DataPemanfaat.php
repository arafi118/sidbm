<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPemanfaat extends Model
{
    use HasFactory;

    protected $table = 'data_pemanfaat';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function kec()
    {
        return $this->belongsTo(Kecamatan::class, 'lokasi');
    }

    public function sts()
    {
        return $this->belongsTo(StatusPinjaman::class, 'status', 'kd_status');
    }
}
