<?php

namespace App\Http\Controllers;

use App\Models\AkunLevel1;
use App\Models\AkunLevel2;
use App\Models\AkunLevel3;
use App\Models\JenisLaporan;
use App\Models\JenisLaporanPinjaman;
use App\Models\Kecamatan;
use App\Models\Rekening;
use App\Models\Transaksi;
use App\Models\User;
use App\Utils\Keuangan;
use App\Utils\Tanggal;
use Dompdf\Dompdf;
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
        $kec = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten', 'desa')->first();
        $kab = $kec->kabupaten;
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
            $laporan = explode('_', $request->sub_laporan);
            $file = $laporan[0];

            $data['kode_akun'] = $laporan[1];
            $data['laporan'] = 'buku_besar ' . $laporan[1];
            return $this->$file($data);
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
        $keuangan = new Keuangan;

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

        $data['debit'] = 0;
        $data['kredit'] = 0;

        $data['akun1'] = AkunLevel1::where('lev1', '<=', '3')->with('akun2.akun3.rek')->orderBy('kode_akun', 'ASC')->get();

        $view = view('pelaporan.view.neraca', $data);
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    private function laba_rugi(array $data)
    {
        $keuangan = new Keuangan;

        $thn = $data['tahun'];
        $bln = $data['bulan'];
        $hari = $data['hari'];
        $awal_tahun = $thn . '-01-01';

        $tgl = $thn . '-' . $bln . '-' . $hari;
        if (strlen($hari) > 0 && strlen($bln) > 0) {
            $data['sub_judul'] = 'Periode ' . Tanggal::tglLatin($awal_tahun) . ' S.D ' . Tanggal::tglLatin($data['tgl_kondisi']);
            $data['tgl'] = Tanggal::tglLatin($tgl);
            $data['bulan_lalu'] = date('Y-m-d', strtotime('-1 day', strtotime($data['tgl_kondisi'])));
            $data['header_lalu'] = 'Kemarin';
            $data['header_sekarang'] = 'Hari Ini';
        } elseif (strlen($bln) > 0) {
            $data['sub_judul'] = 'Periode ' . Tanggal::tglLatin($awal_tahun) . ' S.D ' . Tanggal::tglLatin($data['tgl_kondisi']);
            $data['tgl'] = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['bulan_lalu'] = date('Y-m-d', strtotime('-1 month', strtotime($data['tgl_kondisi'])));
            $data['header_lalu'] = 'Bulan Lalu';
            $data['header_sekarang'] = 'Bulan Ini';
        } else {
            $data['sub_judul'] = 'Periode ' . Tanggal::tglLatin($awal_tahun) . ' S.D ' . Tanggal::tglLatin($data['tgl_kondisi']);
            $data['tgl'] = Tanggal::tahun($tgl);
            $data['bulan_lalu'] = ($thn - 1) . '-12-31';
            $data['header_lalu'] = 'Tahun Lalu';
            $data['header_sekarang'] = 'Tahun Ini';
        }

        $pendapatan = AkunLevel2::where([
            ['lev1', '4'],
            ['lev2', '1']
        ])->with('rek')->orderBy('kode_akun', 'ASC')->get();

        $beban = AkunLevel2::where('lev1', '5')->where(function ($query) {
            $query->where('lev2', '1')->orwhere('lev2', '2');
        })->with('rek')->orderBy('kode_akun', 'ASC')->get();

        $pendapatanNOP = AkunLevel2::where('lev1', '4')->where(function ($query) {
            $query->where('lev2', '2')->orwhere('lev2', '3');
        })->with('rek')->orderBy('kode_akun', 'ASC')->get();

        $bebanNOP = AkunLevel2::where([
            ['lev1', '5'],
            ['lev2', '3']
        ])->with('rek')->orderBy('kode_akun', 'ASC')->get();

        // Pendapatan
        $pend_lev1 = [];
        foreach ($pendapatan as $pend) {
            $pend_lev1[$pend->lev2] = [
                'kode_akun' => $pend->kode_akun,
                'nama_akun' => $pend->nama_akun
            ];

            $pend_rek = [];
            foreach ($pend->rek as $rek) {
                $saldo = $keuangan->Saldo($data['tgl_kondisi'], $rek->kode_akun);
                $saldo_bln_lalu = $keuangan->Saldo($data['bulan_lalu'], $rek->kode_akun);

                $pend_rek[$rek->kode_akun] = [
                    'kode_akun' => $rek->kode_akun,
                    'nama_akun' => $rek->nama_akun,
                    'saldo' => $saldo,
                    'saldo_bln_lalu' => $saldo_bln_lalu
                ];
            }

            $pend_lev1[$pend->lev2]['rek'] = $pend_rek;
        }

        // Beban
        $beb_lev1 = [];
        foreach ($beban as $beb) {
            $beb_lev1[$beb->lev2] = [
                'kode_akun' => $beb->kode_akun,
                'nama_akun' => $beb->nama_akun
            ];

            $beb_rek = [];
            foreach ($beb->rek as $rek) {
                $saldo = $keuangan->Saldo($data['tgl_kondisi'], $rek->kode_akun);
                $saldo_bln_lalu = $keuangan->Saldo($data['bulan_lalu'], $rek->kode_akun);

                $beb_rek[$rek->kode_akun] = [
                    'kode_akun' => $rek->kode_akun,
                    'nama_akun' => $rek->nama_akun,
                    'saldo' => $saldo,
                    'saldo_bln_lalu' => $saldo_bln_lalu
                ];
            }

            $beb_lev1[$beb->lev2]['rek'] = $beb_rek;
        }

        // Pendapatan Non Operasional
        $pendNOP_lev1 = [];
        foreach ($pendapatanNOP as $pendNOP) {
            $pendNOP_lev1[$pendNOP->lev2] = [
                'kode_akun' => $pendNOP->kode_akun,
                'nama_akun' => $pendNOP->nama_akun
            ];

            $pendNOP_rek = [];
            foreach ($pendNOP->rek as $rek) {
                $saldo = $keuangan->Saldo($data['tgl_kondisi'], $rek->kode_akun);
                $saldo_bln_lalu = $keuangan->Saldo($data['bulan_lalu'], $rek->kode_akun);

                $pendNOP_rek[$rek->kode_akun] = [
                    'kode_akun' => $rek->kode_akun,
                    'nama_akun' => $rek->nama_akun,
                    'saldo' => $saldo,
                    'saldo_bln_lalu' => $saldo_bln_lalu
                ];
            }

            $pendNOP_lev1[$pendNOP->lev2]['rek'] = $pendNOP_rek;
        }

        // Beban Non Operasional
        $bebNOP_lev1 = [];
        foreach ($bebanNOP as $bebNOP) {
            $bebNOP_lev1[$bebNOP->lev2] = [
                'kode_akun' => $bebNOP->kode_akun,
                'nama_akun' => $bebNOP->nama_akun
            ];

            $bebNOP_rek = [];
            foreach ($bebNOP->rek as $rek) {
                $saldo = $keuangan->Saldo($data['tgl_kondisi'], $rek->kode_akun);
                $saldo_bln_lalu = $keuangan->Saldo($data['bulan_lalu'], $rek->kode_akun);

                $bebNOP_rek[$rek->kode_akun] = [
                    'kode_akun' => $rek->kode_akun,
                    'nama_akun' => $rek->nama_akun,
                    'saldo' => $saldo,
                    'saldo_bln_lalu' => $saldo_bln_lalu
                ];
            }

            $bebNOP_lev1[$bebNOP->lev2]['rek'] = $bebNOP_rek;
        }

        $data['pph'] = [
            'bulan_lalu' => $keuangan->Saldo($data['bulan_lalu'], '5.4.01.01'),
            'sekarang' => $keuangan->Saldo($data['tgl_kondisi'], '5.4.01.01')
        ];

        $data['pendapatan'] = $pend_lev1;
        $data['beban'] = $beb_lev1;
        $data['pendapatanNOP'] = $pendNOP_lev1;
        $data['bebanNOP'] = $bebNOP_lev1;

        $view = view('pelaporan.view.laba_rugi', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    private function arus_kas(array $data)
    {
        return 'b';
    }

    private function CALK(array $data)
    {
        $keuangan = new Keuangan;

        $thn = $data['tahun'];
        $bln = $data['bulan'];
        $hari = $data['hari'];

        $tgl = $thn . '-' . $bln . '-' . $hari;
        if (strlen($hari) > 0 && strlen($bln) > 0) {
            $data['tgl'] = Tanggal::tglLatin($tgl);
            $data['nama_tgl'] = 'Tanggal ' . $hari . ' Bulan ' . Tanggal::namaBulan($bln) . ' Tahun ' . $thn;
        } elseif (strlen($bln) > 0) {
            $data['tgl'] = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['nama_tgl'] = 'Bulan ' . Tanggal::namaBulan($tgl) . ' Tahun ' . $thn;
        } else {
            $data['tgl'] = Tanggal::tahun($tgl);
            $data['nama_tgl'] = 'Tahun ' . $thn;
        }

        $data['sub_judul'] = 'Tahun ' . $thn;
        $data['debit'] = 0;
        $data['kredit'] = 0;

        $data['akun1'] = AkunLevel1::where('lev1', '<=', '3')->with('akun2.akun3.rek')->orderBy('kode_akun', 'ASC')->get();

        $view = view('pelaporan.view.calk', $data);
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    private function jurnal_transaksi(array $data)
    {
        $thn = $data['tahun'];
        $bln = $data['bulan'];
        $hari = $data['hari'];

        $tgl = $thn . '-' . $bln . '-' . $hari;
        if (strlen($hari) > 0 && strlen($bln) > 0) {
            $data['sub_judul'] = 'Tanggal ' . $hari . ' Bulan ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl'] = Tanggal::tglLatin($tgl);
            $data['transaksi'] = Transaksi::where('tgl_transaksi', $tgl)->withSum('angs', 'jumlah')->with('user', 'rek_debit', 'rek_kredit', 'angs')->withCount('angs')->orderBy('tgl_transaksi', 'ASC')->get();
        } elseif (strlen($bln) > 0) {
            $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl'] = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['transaksi'] = Transaksi::whereBetween('tgl_transaksi', [
                $thn . '-' . $bln . '-01',
                $thn . '-' . $bln . '-' . date('t', strtotime($tgl))
            ])->withSum('angs', 'jumlah')->with('user', 'rek_debit', 'rek_kredit', 'angs')->withCount('angs')->orderBy('tgl_transaksi', 'ASC')->get();
        } else {
            $data['sub_judul'] = 'Tahun ' . Tanggal::tahun($tgl);
            $data['tgl'] = Tanggal::tahun($tgl);
            $data['transaksi'] = Transaksi::whereBetween('tgl_transaksi', [
                $thn . '-01-01',
                $thn . '-12-31'
            ])->withSum('angs', 'jumlah')->with('user', 'rek_debit', 'rek_kredit', 'angs')->withCount('angs')->orderBy('tgl_transaksi', 'ASC')->get();
        }

        $view = view('pelaporan.view.jurnal_transaksi', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    private function BB(array $data)
    {
        $keuangan = new Keuangan;

        $thn = $data['tahun'];
        $bln = $data['bulan'];
        $hari = $data['hari'];

        $tgl = $thn . '-' . $bln . '-' . $hari;
        if (strlen($hari) > 0 && strlen($bln) > 0) {
            $tgl = $thn . '-' . $bln . '-' . $hari;
            $data['judul'] = 'Laporan Harian';
            $data['tgl'] = Tanggal::tglLatin($tgl);
            $awal_bulan = $tgl;
        } elseif (strlen($bln) > 0) {
            $tgl = $thn . '-' . $bln . '-';
            $data['judul'] = 'Laporan Bulanan';
            $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl'] = Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $bulan_lalu = date('m', strtotime('-1 month', strtotime($tgl . '01')));
            $awal_bulan = $thn . '-' . $bulan_lalu . '-' . date('t', strtotime($thn . '-' . $bulan_lalu));
            if ($bln == 1) {
                $awal_bulan = $thn . '00-00';
            }
        } else {
            $tgl = $thn . '-';
            $data['judul'] = 'Laporan Tahunan';
            $data['sub_judul'] = 'Tahun ' . Tanggal::tahun($tgl);
            $data['tgl'] = Tanggal::tahun($tgl);
            $awal_bulan = ($thn - 1) . '12-31';
        }

        $data['rek'] = Rekening::where('kode_akun', $data['kode_akun'])->first();
        $data['transaksi'] = Transaksi::where('tgl_transaksi', 'LIKE', '%' . $tgl . '%')->where(function ($query) use ($data) {
            $query->where('rekening_debit', $data['kode_akun'])->orwhere('rekening_kredit', $data['kode_akun']);
        })->with('user')->get();

        $data['saldo'] = $keuangan->saldoAwal($tgl, $data['kode_akun']);
        $data['d_bulan_lalu'] = $keuangan->saldoD($awal_bulan, $data['kode_akun']);
        $data['k_bulan_lalu'] = $keuangan->saldoK($awal_bulan, $data['kode_akun']);

        $view = view('pelaporan.view.buku_besar', $data);
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }
}
