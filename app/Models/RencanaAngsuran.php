<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Model;
use Session;

class RencanaAngsuran extends Model
{
    use TenantAware;

    protected $baseTable = 'rencana_angsuran';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function real()
    {
        return $this->hasMany(RealAngsuran::class, 'loan_id', 'loan_id');
    }

    public function ra()
    {
        return $this->hasMany(RencanaAngsuran::class, 'loan_id', 'loan_id');
    }
}
