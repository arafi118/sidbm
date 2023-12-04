<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisLaporan;
use App\Models\Kecamatan;
use App\Models\Wilayah;
use Session;

class AdminController extends Controller
{
    public function index()
    {
        $title = 'Admin Page';
        return view('admin.index')->with(compact('title'));
    }

    public function kecamatan($kd_prov, $kd_kab, $kd_kec)
    {
        $kec = Kecamatan::where('kd_kec', $kd_kec)->first();
        $laporan = JenisLaporan::where('file', '!=', '0')->orderBy('urut', 'ASC')->get();

        Session::put('lokasi', $kec->id);
        $title = 'Pelaporan ' . $kec->sebutan_kec . ' ' . $kec->nama_kec;
        return view('admin.kecamatan.index')->with(compact('title', 'kec', 'laporan'));
    }

    public function laporan()
    {
        $wilayah = Wilayah::WhereRaw('LENGTH(kode)=2')->orderBy('nama', 'ASC')->get();

        $title = 'Laporan Pusat';
        return view('admin.wilayah')->with(compact('title', 'wilayah'));
    }
}
