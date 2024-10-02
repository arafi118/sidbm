<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuTombol extends Model
{
    use HasFactory;
    protected $table = 'menu_tombol';
    public $timestamps = false;

    public function child()
    {
        return $this->hasMany(MenuTombol::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(MenuTombol::class, 'parent_id', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id');
    }
}
