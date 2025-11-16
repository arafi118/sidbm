<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Model;
use Session;

class Inventaris extends Model
{
    use TenantAware;
    protected $baseTable = 'inventaris';
    public $timestamps = false;

    protected $guarded = ['id'];
}
