<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;

class AdminController extends Controller
{
    public function index()
    {
        $title = 'Admin Page';
        return view('admin.index')->with(compact('title'));
    }

    public function laporan()
    {
        $wilayah = Wilayah::WhereRaw('LENGTH(kode)=2')->orderBy('nama', 'ASC')->get();

        $title = 'Laporan Pusat';
        return view('admin.wilayah')->with(compact('title', 'wilayah'));
    }
}
