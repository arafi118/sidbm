<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $title = 'Admin Page';
        return view('admin.index')->with(compact('title'));
    }
}
