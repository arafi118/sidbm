<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $title = 'Daftar User';
        return view('admin.user.index')->with(compact('title'));
    }
}
