<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisLaporan;
use App\Models\Kecamatan;
use App\Utils\Keuangan;
use Illuminate\Http\Request;
use Session;

class KecamatanController extends Controller
{
    public function index($kd_prov, $kd_kab, $kd_kec)
    {
        $kec = Kecamatan::where('kd_kec', $kd_kec)->with('kabupaten')->first();
        $laporan = JenisLaporan::where('file', '!=', '0')->orderBy('urut', 'ASC')->get();

        $kab = $kec->kabupaten;
        $nama_kec = $kec->sebutan_kec . ' ' . $kec->nama_kec;
        if (Keuangan::startWith($kab->nama_kab, 'KOTA') || Keuangan::startWith($kab->nama_kab, 'KAB')) {
            $nama_kec .= ' ' . ucwords(strtolower($kab->nama_kab));
        } else {
            $nama_kec .= ' Kabupaten ' . ucwords(strtolower($kab->nama_kab));
        }

        Session::put('lokasi', $kec->id);
        $title = 'Pelaporan ' . $kec->sebutan_kec . ' ' . $kec->nama_kec;
        return view('admin.kecamatan.index')->with(compact('title', 'kec', 'laporan', 'nama_kec'));
    }
}
