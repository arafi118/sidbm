<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Model;
use \Awobaz\Compoships\Compoships;
use Session;

class Ebudgeting extends Model
{
    use TenantAware, Compoships;
    protected $baseTable = 'ebudgeting';
    public $timestamps = false;

    protected $guarded = ['id'];
}
