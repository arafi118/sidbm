<?php

namespace App\Http\Controllers;

use App\Models\AkunLevel1;
use App\Models\AkunLevel2;
use App\Models\AkunLevel3;
use App\Models\JenisLaporan;
use App\Models\JenisLaporanPinjaman;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Rekening;
use App\Models\User;
use App\Utils\Keuangan;
use App\Utils\Tanggal;
use Illuminate\Http\Request;
use PDF;

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

    public function preview(Request $request)
    {
        $data = $request->only([
            'tahun',
            'bulan',
            'hari',
            'laporan',
            'sub_laporan'
        ]);

        $request->hari = ($request->hari) ?: 31;
        $kec = Kecamatan::where('id', auth()->user()->lokasi)->first();
        $kab = Kabupaten::where('kd_kab', $kec->kd_kab)->first();
        $dir = User::where([
            ['lokasi', auth()->user()->lokasi],
            ['jabatan', '1'],
            ['level', '1'],
            ['sejak', '<=', $request->tahun . '-' . $request->bulan . '-' . $request->hari]
        ])->first();

        $data['logo'] = $kec->logo;
        $data['nama_lembaga'] = $kec->nama_lembaga_sort;
        $data['nama_kecamatan'] = $kec->sebutan_kec . ' ' . $kec->nama_kec;

        if (Keuangan::startWith($kab->nama_kab, 'KOTA') || Keuangan::startWith($kab->nama_kab, 'KAB')) {
            $data['nama_kecamatan'] .= ' ' . ucwords(strtolower($kab->nama_kab));
            $data['nama_kabupaten'] = ucwords(strtolower($kab->nama_kab));
        } else {
            $data['nama_kecamatan'] .= ' Kabupaten ' . ucwords(strtolower($kab->nama_kab));
            $data['nama_kabupaten'] = ' Kabupaten ' . ucwords(strtolower($kab->nama_kab));
        }

        $data['nomor_usaha'] = 'SK Kemenkumham RI No.' . $kec->nomor_bh;
        $data['info'] = $kec->alamat_kec . ', Telp.' . $kec->telpon_kec;
        $data['email'] = $kec->email_kec;
        $data['kec'] = $kec;
        $data['kab'] = $kab;
        $data['dir'] = $dir;

        if (strlen($data['hari']) > 0 && strlen($data['bulan']) > 0) {
            $data['tgl_kondisi'] = $data['tahun'] . '-' . $data['bulan'] . '-' . $data['hari'];
        } elseif (strlen($data['bulan']) > 0) {
            $data['tgl_kondisi'] = $data['tahun'] . '-' . $data['bulan'] . '-' . date('t', strtotime($data['tahun'] . '-' . $data['bulan']));
        } else {
            $data['tgl_kondisi'] = $data['tahun'] . '-12-31';
        }

        $file = $request->laporan;
        if ($file == 3) {
            //
        } elseif ($file == 5) {
            //
        } else {
            return $this->$file($data);
        }
    }

    private function cover(array $data)
    {
        $thn = $data['tahun'];
        $bln = $data['bulan'];
        $hari = $data['hari'];

        $tgl = $thn . '-' . $bln . '-' . $hari;
        if (strlen($hari) > 0 && strlen($bln) > 0) {
            $data['judul'] = 'Laporan Harian';
            $data['sub_judul'] = 'Tanggal ' . Tanggal::tglLatin($tgl);
        } elseif (strlen($bln) > 0) {
            $data['judul'] = 'Laporan Bulanan';
            $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
        } else {
            $data['judul'] = 'Laporan Tahunan';
            $data['sub_judul'] = 'Tahun ' . Tanggal::tahun($tgl);
        }

        $view = view('pelaporan.view.cover', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    private function surat_pengantar(array $data)
    {
        $thn = $data['tahun'];
        $bln = $data['bulan'];
        $hari = $data['hari'];

        if (strlen($hari) > 0 && strlen($bln) > 0) {
            $tgl = $thn . '-' . $bln . '-' . $hari;
            $data['judul'] = 'Laporan Harian';
            $data['sub_judul'] = 'Tanggal ' . Tanggal::tglLatin($tgl);
            $data['tgl'] = Tanggal::tglLatin($tgl);
        } elseif (strlen($bln) > 0) {
            $tgl = $thn . '-' . $bln . '-' . $hari;
            $data['judul'] = 'Laporan Bulanan';
            $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl'] = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
        } else {
            $tgl = $thn . '-' . $bln . '-' . $hari;
            $data['judul'] = 'Laporan Tahunan';
            $data['sub_judul'] = 'Tahun ' . Tanggal::tahun($tgl);
            $data['tgl'] = Tanggal::tahun($tgl);
        }

        $view = view('pelaporan.view.surat_pengantar', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    private function neraca(array $data)
    {
        $thn = $data['tahun'];
        $bln = $data['bulan'];
        $hari = $data['hari'];

        $tgl = $thn . '-' . $bln . '-' . $hari;
        if (strlen($hari) > 0 && strlen($bln) > 0) {
            $data['sub_judul'] = 'Per ' . $hari . ' ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl'] = Tanggal::tglLatin($tgl);
        } elseif (strlen($bln) > 0) {
            $data['sub_judul'] = 'Per ' . date('t', strtotime($tgl)) . ' ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl'] = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
        } else {
            $data['sub_judul'] = 'Per 31 Desember' . ' ' . Tanggal::tahun($tgl);
            $data['tgl'] = Tanggal::tahun($tgl);
        }

        $akun_lev1 = [];
        $akun1 = AkunLevel1::where('lev1', '<=', '3')->with('akun2')->orderBy('kode_akun', 'ASC')->get();
        foreach ($akun1 as $lev1) {
            $sum_akun1 = 0;
            $akun_lev1[$lev1->lev1] = [
                'kode_akun' => $lev1->kode_akun,
                'nama_akun' => $lev1->nama_akun
            ];

            $akun_lev2 = [];
            foreach ($lev1->akun2 as $lev2) {
                $akun_lev2[$lev2->lev2] = [
                    'kode_akun' => $lev2->kode_akun,
                    'nama_akun' => $lev2->nama_akun
                ];

                $akun_lev3 = [];
                foreach ($lev2->akun3 as $lev3) {
                    $sum_saldo = 0;

                    foreach ($lev3->rek as $rek) {
                        $saldo = Keuangan::Saldo($data['tgl_kondisi'], $rek->kode_akun);
                        if ($rek->kode_akun == '3.2.02.01') {
                            $saldo = Keuangan::surplus($data['tgl_kondisi']);
                        }

                        $sum_saldo += $saldo;
                    }

                    $sum_akun1 += $sum_saldo;
                    $akun_lev3[$lev3->lev3] = [
                        'kode_akun' => $lev3->kode_akun,
                        'nama_akun' => $lev3->nama_akun,
                        'saldo' => $sum_saldo
                    ];
                }
                $akun_lev2[$lev2->lev2]['lev3'] = $akun_lev3;
            }

            $akun_lev1[$lev1->lev1]['saldo'] = $sum_akun1;
            $akun_lev1[$lev1->lev1]['lev2'] = $akun_lev2;
        }

        $data['neraca'] = $akun_lev1;

        $view = view('pelaporan.view.neraca', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    private function laba_rugi(array $data)
    {
        $thn = $data['tahun'];
        $bln = $data['bulan'];
        $hari = $data['hari'];
        $awal_tahun = $thn . '-01-01';

        $tgl = $thn . '-' . $bln . '-' . $hari;
        if (strlen($hari) > 0 && strlen($bln) > 0) {
            $data['sub_judul'] = 'Periode ' . Tanggal::tglLatin($awal_tahun) . ' S.D ' . Tanggal::tglLatin($data['tgl_kondisi']);
            $data['tgl'] = Tanggal::tglLatin($tgl);
            $data['bulan_lalu'] = date('Y-m-d', strtotime('-1 day', strtotime($data['tgl_kondisi'])));
        } elseif (strlen($bln) > 0) {
            $data['sub_judul'] = 'Periode ' . Tanggal::tglLatin($awal_tahun) . ' S.D ' . Tanggal::tglLatin($data['tgl_kondisi']);
            $data['tgl'] = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['bulan_lalu'] = date('Y-m-d', strtotime('-1 month', strtotime($data['tgl_kondisi'])));
        } else {
            $data['sub_judul'] = 'Periode ' . Tanggal::tglLatin($awal_tahun) . ' S.D ' . Tanggal::tglLatin($data['tgl_kondisi']);
            $data['tgl'] = Tanggal::tahun($tgl);
            $data['bulan_lalu'] = ($thn - 1) . '-12-31';
        }

        $data['pendapatan'] = AkunLevel2::where([
            ['lev1', '4'],
            ['lev2', '1']
        ])->with('rek')->orderBy('kode_akun', 'ASC')->get();

        $data['beban'] = AkunLevel2::where('lev1', '5')->where(function ($query) {
            $query->where('lev2', '1')->orwhere('lev2', '2');
        })->with('rek')->orderBy('kode_akun', 'ASC')->get();

        $data['pendapatan_NOP'] = AkunLevel2::where('lev1', '4')->where(function ($query) {
            $query->where('lev2', '2')->orwhere('lev2', '3');
        })->with('rek')->orderBy('kode_akun', 'ASC')->get();

        $data['biaya_NOP'] = AkunLevel2::where([
            ['lev1', '5'],
            ['lev2', '3']
        ])->with('rek')->orderBy('kode_akun', 'ASC')->get();

        $view = view('pelaporan.view.laba_rugi', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }
}
