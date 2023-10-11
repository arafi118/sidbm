<?php

namespace App\Http\Controllers;

use App\Models\AkunLevel1;
use App\Models\Kecamatan;
use App\Models\PinjamanAnggota;
use App\Models\PinjamanKelompok;
use App\Models\RealAngsuran;
use App\Models\Rekening;
use App\Models\RencanaAngsuran;
use App\Models\Saldo;
use App\Models\Transaksi;
use App\Utils\Keuangan;
use App\Utils\Tanggal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Cookie;
use DB;
use Session;

class DashboardController extends Controller
{
    public function index()
    {
        if (Session::get('_previous')['url'] == url('')) {
            $this->piutang();
            $this->sync(auth()->user()->lokasi);
        }

        $tgl = date('Y-m-d');
        $pinj_anggota = PinjamanAnggota::where([
            ['status', 'A'],
            ['tgl_cair', '<=', $tgl]
        ])->count();

        $pinkel = PinjamanKelompok::where([
            ['status', 'A'],
            ['tgl_cair', '<=', $tgl]
        ])->count();

        $data['pinjaman_anggota'] = $pinj_anggota;
        $data['pinjaman_kelompok'] = $pinkel;

        $tb = 'pinjaman_kelompok_' . auth()->user()->lokasi;
        $pinj = PinjamanKelompok::select([
            DB::raw("(SELECT count(*) FROM $tb WHERE status='P') as p"),
            DB::raw("(SELECT count(*) FROM $tb WHERE status='V') as v"),
            DB::raw("(SELECT count(*) FROM $tb WHERE status='W') as w"),
        ])->first();

        $data['proposal'] = $pinj->p;
        $data['verifikasi'] = $pinj->v;
        $data['waiting'] = $pinj->w;

        $tb = 'transaksi_' . auth()->user()->lokasi;
        $trx = Transaksi::select([
            DB::raw("(SELECT SUM(jumlah) as j FROM $tb WHERE rekening_debit LIKE '1.1.01.%' AND rekening_kredit='1.1.03.01' AND tgl_transaksi='$tgl') as pokok_spp"),
            DB::raw("(SELECT SUM(jumlah) as j FROM $tb WHERE rekening_debit LIKE '1.1.01.%' AND rekening_kredit='1.1.03.02' AND tgl_transaksi='$tgl') as pokok_uep"),
            DB::raw("(SELECT SUM(jumlah) as j FROM $tb WHERE rekening_debit LIKE '1.1.01.%' AND rekening_kredit='1.1.03.03' AND tgl_transaksi='$tgl') as pokok_pl"),
            DB::raw("(SELECT SUM(jumlah) as j FROM $tb WHERE rekening_debit LIKE '1.1.01.%' AND rekening_kredit='4.1.01.01' AND tgl_transaksi='$tgl') as jasa_spp"),
            DB::raw("(SELECT SUM(jumlah) as j FROM $tb WHERE rekening_debit LIKE '1.1.01.%' AND rekening_kredit='4.1.01.02' AND tgl_transaksi='$tgl') as jasa_uep"),
            DB::raw("(SELECT SUM(jumlah) as j FROM $tb WHERE rekening_debit LIKE '1.1.01.%' AND rekening_kredit='4.1.01.03' AND tgl_transaksi='$tgl') as jasa_pl"),
        ])->first();

        $data['pokok_spp'] = $trx->pokok_spp;
        $data['pokok_uep'] = $trx->pokok_uep;
        $data['pokok_pl'] = $trx->pokok_pl;
        $data['jasa_spp'] = $trx->jasa_spp;
        $data['jasa_uep'] = $trx->jasa_uep;
        $data['jasa_pl'] = $trx->jasa_pl;

        $data['saldo'] = $this->_saldo($tgl);

        $data['title'] = "Dashboard";
        return view('dashboard.index')->with($data);
    }

    public function piutang()
    {
        $thn = date('Y');
        $thn_lalu = ($thn - 1) . "-12-31";
        $thn_awal = $thn . "-01-01";

        $year = date('Y');
        $month = date('m');
        $day = date('d', strtotime('-1 days', strtotime(date('Y-m-d'))));

        $transaksi = Transaksi::where('tgl_transaksi', date('Y-m-d'))
            ->whereRaw("(rekening_debit='1.1.03.04' AND rekening_kredit='4.1.01.01' OR rekening_debit='1.1.03.05' AND rekening_kredit='4.1.01.02' OR rekening_debit='1.1.03.06' AND rekening_kredit='4.1.01.03')");

        if ($transaksi->count() <= 0) {
            $pinjaman_kelompok = PinjamanKelompok::where('status', 'A')->whereDay('tgl_cair', $day)->with('kelompok')->get();
            foreach ($pinjaman_kelompok as $pinkel) {
                $real = RealAngsuran::where([
                    ['loan_id', $pinkel->id],
                    ['tgl_transaksi', '<=', $year . '-' . $month . '-' . $day]
                ])->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC');

                if ($real->count() > 0) {
                    $real_ang = $real->first();
                    $sum_jasa = $real_ang->sum_jasa;
                } else {
                    $sum_jasa = 0;
                }

                $ra = RencanaAngsuran::where([
                    ['loan_id', $pinkel->id],
                    ['jatuh_tempo', '<=', $year . '-' . $month . '-' . $day],
                    ['angsuran_ke', '!=', '0']
                ])->orderBy('id', 'DESC');

                if ($pinkel->jenis_pp == '1') {
                    $piutang = '1.1.03.04';
                    $pendapatan = '4.1.01.01';
                }

                if ($pinkel->jenis_pp == '2') {
                    $piutang = '1.1.03.05';
                    $pendapatan = '4.1.01.02';
                }

                if ($pinkel->jenis_pp == '3') {
                    $piutang = '1.1.03.06';
                    $pendapatan = '4.1.01.03';
                }

                if ($ra->count() > 0) {
                    $rencana = $ra->first();

                    $target_jasa = $rencana->target_jasa;
                    $nunggak_jasa = $target_jasa - $sum_jasa;

                    $insert = [
                        'tgl_transaksi' => date('Y-m-d'),
                        'rekening_debit' => $piutang,
                        'rekening_kredit' => $pendapatan,
                        'idtp' => 0,
                        'id_pinj' => $pinkel->id,
                        'id_pinj_i' => 0,
                        'keterangan_transaksi' => 'Hutang jasa loan_id ' . $pinkel->id . ' angsuran ke ' . $rencana->angsuran_ke,
                        'relasi' => $pinkel->kelompok->nama_kelompok,
                        'jumlah' => $nunggak_jasa,
                        'urutan' => 0,
                        'id_user' => auth()->user()->id,
                    ];

                    if ($nunggak_jasa > 0) {
                        Transaksi::create($insert);
                    }
                }
            }
        }

        echo '<script>window.close()</script>';
    }

    private function _piutang()
    {
        $thn = date('Y');
        $thn_lalu = ($thn - 1) . "-12-31";
        $thn_awal = $thn . "-01-01";

        $piutang_jasa = [];
        $piutang_jasa['1.1.03.04'] = 0;
        $piutang_jasa['1.1.03.05'] = 0;
        $piutang_jasa['1.1.03.06'] = 0;

        $piutang_jasa['4.1.01.01'] = 0;
        $piutang_jasa['4.1.01.02'] = 0;
        $piutang_jasa['4.1.01.03'] = 0;

        $pinjaman_kelompok = PinjamanKelompok::where('status', 'A')->orderBy('tgl_proposal', 'ASC')->get();
        foreach ($pinjaman_kelompok as $pinkel) {

            if ($pinkel->jenis_pp == '1') {
                $piutang = '1.1.03.04';
                $pendapatan = '4.1.01.01';
            }

            if ($pinkel->jenis_pp == '2') {
                $piutang = '1.1.03.05';
                $pendapatan = '4.1.01.02';
            }

            if ($pinkel->jenis_pp == '3') {
                $piutang = '1.1.03.06';
                $pendapatan = '4.1.01.03';
            }

            $ra = RencanaAngsuran::where([
                ['loan_id', '=', $pinkel->id],
                ['jatuh_tempo', '<=', $thn_lalu],
                ['angsuran_ke', '!=', '0']
            ])->orderBy('jatuh_tempo', 'DESC');

            $real = RealAngsuran::where([
                ['loan_id', '=', $pinkel->id],
                ['tgl_transaksi', '<=', $thn_lalu]
            ])->orderBy('tgl_transaksi', 'DESC');

            if ($real->count() > 0) {
                $real_ang = $real->first();
                $sum_jasa = $real_ang->sum_jasa;
            } else {
                $sum_jasa = 0;
            }

            if ($ra->count() > 0) {
                $rencana = $ra->first();

                $piutang_jasa[$piutang] += ($rencana->target_jasa - $sum_jasa);
                $piutang_jasa[$pendapatan] += ($rencana->target_jasa - $sum_jasa);
            }
        }

        foreach ($piutang_jasa as $key => $val) {
            $rek = Rekening::where('kode_akun', $key)->first();

            if (Keuangan::startWith($key, '4.1.01')) {
                $update = [
                    'tbk' . (date('Y') - 1) => $rek->tbk2022 + $val
                ];

                $kd_rek = $rek->tbk2022;
            } else {
                $update = [
                    'tb' . (date('Y') - 1) => $rek->tb_2022 + $val
                ];
                $kd_rek = $rek->tb2022;
            }

            if ($kd_rek < $val) {
                Rekening::where('kode_akun', $key)->update($update);
            }
        }
    }

    public function jatuhTempo(Request $request)
    {
        $tgl = Tanggal::tglNasional($request->tgl);

        $jatuh_tempo = '00';
        $pinjaman = PinjamanKelompok::where('status', 'A')->whereDay('tgl_cair', date('d', strtotime($tgl)))->with([
            'target' => function ($query) use ($tgl) {
                $query->where([
                    ['jatuh_tempo', $tgl],
                    ['angsuran_ke', '!=', '0']
                ]);
            },
            'saldo' => function ($query) use ($tgl) {
                $query->where('tgl_transaksi', '<=', $tgl);
            }
        ])->get();

        foreach ($pinjaman as $pinkel) {
            if ($pinkel->target) {
                $sum_pokok = 0;
                $sum_jasa = 0;

                if ($pinkel->saldo) {
                    $sum_pokok = $pinkel->saldo->sum_pokok;
                    $sum_jasa = $pinkel->saldo->sum_jasa;
                }

                $nunggak_pokok = $pinkel->target->target_pokok - $sum_pokok;
                $nunggak_jasa = $pinkel->target->target_jasa - $sum_jasa;

                if ($nunggak_pokok > 0 && $nunggak_jasa > 0) {
                    $jatuh_tempo++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'jatuh_tempo' => $jatuh_tempo
        ]);
    }

    public function nunggak(Request $request)
    {
        $tgl = Tanggal::tglNasional($request->tgl);
        $pinjaman = PinjamanKelompok::where('status', 'A')->whereDay('tgl_cair', '<=', $tgl)->with([
            'target' => function ($query) use ($tgl) {
                $query->where([
                    ['jatuh_tempo', '<=', $tgl]
                ]);
            },
            'saldo' => function ($query) use ($tgl) {
                $query->where('tgl_transaksi', '<=', $tgl);
            }
        ])->orderBy('tgl_cair', 'ASC')->orderBy('id', 'ASC')->get();

        $nunggak = "00";
        foreach ($pinjaman as $pinkel) {
            $real_pokok = 0;
            $real_jasa = 0;
            $sum_pokok = 0;
            $sum_jasa = 0;
            $saldo_pokok = $pinkel->alokasi;
            $saldo_jasa = $pinkel->alokasi / $pinkel->pros_jasa;
            if ($pinkel->saldo) {
                $real_pokok = $pinkel->saldo->realisasi_pokok;
                $real_jasa = $pinkel->saldo->realisasi_jasa;
                $sum_pokok = $pinkel->saldo->sum_pokok;
                $sum_jasa = $pinkel->saldo->sum_jasa;
                $saldo_pokok = $pinkel->saldo->saldo_pokok;
                $saldo_jasa = $pinkel->saldo->saldo_jasa;
            }

            $target_pokok = 0;
            $target_jasa = 0;
            if ($pinkel->target) {
                $target_pokok = $pinkel->target->target_pokok;
                $target_jasa = $pinkel->target->target_jasa;
            }

            $tunggakan_pokok = $target_pokok - $sum_pokok;
            if ($tunggakan_pokok < 0) {
                $tunggakan_pokok = 0;
            }
            $tunggakan_jasa = $target_jasa - $sum_jasa;
            if ($tunggakan_jasa < 0) {
                $tunggakan_jasa = 0;
            }

            if ($tunggakan_pokok != 0 && $tunggakan_jasa != 0) {
                $nunggak++;
            }
        }

        return response()->json([
            'success' => true,
            'nunggak' => $nunggak
        ]);
    }

    public function lineChart(Request $request)
    {
        $tgl = Tanggal::tglNasional($request->tgl);
    }

    public function setting(Request $request)
    {
        // Cookie
    }

    public function sync($lokasi)
    {
        $tahun = request()->get('tahun') ?: date('Y');
        $bulan = request()->get('bulan') ?: date('m');

        $cek_saldo = Saldo::where([
            ['bulan', $bulan],
            ['tahun', $tahun],
        ])->count();

        if ($cek_saldo <= 0) {
            $date = $tahun . '-' . $bulan . '-01';
            $tgl_kondisi = date('Y-m-t', strtotime($date));
            $rekening = Rekening::withSum([
                'trx_debit' => function ($query) use ($tgl_kondisi) {
                    $query->where('tgl_transaksi', '<=', $tgl_kondisi);
                }
            ], 'jumlah')->withSum([
                'trx_kredit' => function ($query) use ($tgl_kondisi) {
                    $query->where('tgl_transaksi', '<=', $tgl_kondisi);
                }
            ], 'jumlah')->get();

            foreach ($rekening as $rek) {
                $saldo = [
                    'id' => str_replace('.', '', $rek->kode_akun) . $lokasi . $tahun . $bulan,
                    'kode_akun' => $rek->kode_akun,
                    'lokasi' => $lokasi,
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'debit' => $rek->trx_debit_sum_jumlah,
                    'kredit' => $rek->trx_kredit_sum_jumlah
                ];

                Saldo::create($saldo);
            }
        }
    }

    private function _saldo($tgl)
    {
        $data = [
            '4' => [
                '01' => 0,
                '02' => 0,
                '03' => 0,
                '04' => 0,
                '05' => 0,
                '06' => 0,
                '07' => 0,
                '08' => 0,
                '09' => 0,
                '10' => 0,
                '11' => 0,
                '12' => 0,
            ],
            '5' => [
                '01' => 0,
                '02' => 0,
                '03' => 0,
                '04' => 0,
                '05' => 0,
                '06' => 0,
                '07' => 0,
                '08' => 0,
                '09' => 0,
                '10' => 0,
                '11' => 0,
                '12' => 0,
            ],
        ];

        $akun1 = AkunLevel1::where('lev1', '>=', '4')->with([
            'akun2',
            'akun2.akun3',
            'akun2.akun3.rek',
            'akun2.akun3.rek.kom_saldo' => function ($query) use ($tgl) {
                $tahun = date('Y', strtotime($tgl));
                $query->where('tahun', $tahun)->orderBy('kode_akun', 'ASC')->orderBy('bulan', 'ASC');
            },
        ])->get();

        foreach ($akun1 as $lev1) {
            $kom_saldo[$lev1->lev1] = $data[$lev1->lev1];
            foreach ($lev1->akun2 as $lev2) {
                foreach ($lev2->akun3 as $lev3) {
                    foreach ($lev3->rek as $rek) {
                        foreach ($rek->kom_saldo as $saldo) {
                            if ($lev1->lev1 == '5') {
                                $_saldo = $saldo->debit - $saldo->kredit;
                            } else {
                                $_saldo = $saldo->kredit - $saldo->debit;
                            }

                            $kom_saldo[$lev1->lev1][$saldo->bulan] += $_saldo;
                        }
                    }
                }
            }
        }

        $kom_saldo['surplus'] = [
            '01' => $kom_saldo['4']['01'] - $kom_saldo['5']['01'],
            '02' => $kom_saldo['4']['02'] - $kom_saldo['5']['02'],
            '03' => $kom_saldo['4']['03'] - $kom_saldo['5']['03'],
            '04' => $kom_saldo['4']['04'] - $kom_saldo['5']['04'],
            '05' => $kom_saldo['4']['05'] - $kom_saldo['5']['05'],
            '06' => $kom_saldo['4']['06'] - $kom_saldo['5']['06'],
            '07' => $kom_saldo['4']['07'] - $kom_saldo['5']['07'],
            '08' => $kom_saldo['4']['08'] - $kom_saldo['5']['08'],
            '09' => $kom_saldo['4']['09'] - $kom_saldo['5']['09'],
            '10' => $kom_saldo['4']['10'] - $kom_saldo['5']['10'],
            '11' => $kom_saldo['4']['11'] - $kom_saldo['5']['11'],
            '12' => $kom_saldo['4']['12'] - $kom_saldo['5']['12'],
        ];

        return $kom_saldo;
    }
}
