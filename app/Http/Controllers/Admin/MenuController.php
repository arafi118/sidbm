<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $menu = Menu::where('parent_id', '0')->with('child')->get();
        dd($menu);

        $title = 'Pengaturan Menu';
        return view('admin.menu.index')->with(compact('title'));
    }
}
