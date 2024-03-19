<?php

namespace App\Models\Master;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'kecamatan';
    protected $connection = 'master';
    protected $guarded = ['id'];

    protected $with = ['kab'];

    public function kab()
    {
        return $this->belongsTo(Kabupaten::class, 'LEFT(kecamatan.kode, 5)', 'kode');
    }
}
