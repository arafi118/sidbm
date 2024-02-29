<?php

namespace App\Http\Controllers\Kabupaten;

use App\Http\Controllers\Controller;
use App\Models\JenisLaporan;
use App\Models\Kecamatan;
use App\Models\Wilayah;
use App\Utils\Keuangan;
use Session;

class KabupatenController extends Controller
{
    public function index()
    {
        $title = Session::get('nama_kab') . ' Page';
        return view('kabupaten.index')->with(compact('title'));
    }

    public function kecamatan($kd_kec)
    {
        $kec = Kecamatan::where('kd_kec', $kd_kec)->with('kabupaten')->first();
        $laporan = JenisLaporan::where('file', '!=', '0')->orderBy('urut', 'ASC')->get();

        if (!$kec) {
            $kec = Wilayah::where('kode', $kd_kec)->first();

            $title = 'Kecamatan Belum Terdaftar';
            return view('kabupaten._kecamatan')->with(compact('title', 'kec'));
        }

        $kab = $kec->kabupaten;
        $nama_kec = $kec->sebutan_kec . ' ' . $kec->nama_kec;
        if (Keuangan::startWith($kab->nama_kab, 'KOTA') || Keuangan::startWith($kab->nama_kab, 'KAB')) {
            $nama_kec .= ' ' . ucwords(strtolower($kab->nama_kab));
        } else {
            $nama_kec .= ' Kabupaten ' . ucwords(strtolower($kab->nama_kab));
        }

        Session::put('lokasi', $kec->id);
        $title = 'Pelaporan ' . $kec->sebutan_kec . ' ' . $kec->nama_kec;
        return view('kabupaten.kecamatan')->with(compact('title', 'kec', 'laporan', 'nama_kec'));
    }
}
