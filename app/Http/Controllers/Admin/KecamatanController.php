<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class KecamatanController extends Controller
{
    public function index()
    {
        $title = 'Daftar Provinsi';
        return view('admin.index')->with(compact('title'));
    }
}
