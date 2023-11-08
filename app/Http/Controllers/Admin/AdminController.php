<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $title = 'Admin Page';
        return view('admin.index')->with(compact('title'));
    }

    public function kecamatan()
    {
        $kecamatan = Kecamatan::orderBy('id', 'ASC')->get();

        $title = 'Daftar Kecamatan';
        return view('admin.kecamatan')->with(compact('title', 'kecamatan'));
    }
}
