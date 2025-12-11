<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUpdate extends Model
{
    use HasFactory;

    protected $table = 'app_update';

    protected $fillable = [
        'latest_version',
        'version_code',
        'apk_name',
        'apk_url',
        'changelog',
        'force_update',
        'min_supported_version',
    ];
}
