<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanduanController extends Controller
{
    public function index()
    {
        $title = 'Panduan Transaksi';
        return view('panduan.index', compact('title'));
    }
}
