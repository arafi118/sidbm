<?php

namespace App\Http\Controllers;

use App\Models\AkunLevel3;
use App\Models\JenisLaporan;
use App\Models\JenisLaporanPinjaman;
use App\Models\Kecamatan;
use App\Models\Rekening;
use Illuminate\Http\Request;

class PelaporanController extends Controller
{
    public function index()
    {
        $title = 'Pelaporan';
        $kec = Kecamatan::where('id', auth()->user()->lokasi)->first();
        $laporan = JenisLaporan::where('file', '!=', '0')->orderBy('urut', 'ASC')->get();
        return view('pelaporan.index')->with(compact('title', 'kec', 'laporan'));
    }

    public function subLaporan($file)
    {
        if ($file == 3) {
            $rekening = Rekening::where('lev1', '<=', '3')->orwhere(function ($query) {
                $query->where([
                    ['lev1', '4'],
                    ['lev2', '<', '2']
                ]);
            })->orderBy('kode_akun', 'ASC')->get();
            $akun = AkunLevel3::where('lev1', '5')->orwhere(function ($query) {
                $query->where([
                    ['lev1', '4'],
                    ['lev2', '>=', '2']
                ]);
            })->orderBy('kode_akun', 'ASC')->get();

            return view('pelaporan.partials.sub_laporan')->with(compact('file', 'rekening', 'akun'));
        }

        if ($file == 5) {
            $jenis_laporan = JenisLaporanPinjaman::where('file', '!=', '0')->orderBy('urut', 'ASC')->get();

            return view('pelaporan.partials.sub_laporan')->with(compact('file', 'jenis_laporan'));
        }

        return view('pelaporan.partials.sub_laporan')->with(compact('file'));
    }
}
