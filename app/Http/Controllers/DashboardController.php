<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\PinjamanKelompok;
use App\Models\RealAngsuran;
use App\Models\Rekening;
use App\Models\RencanaAngsuran;
use App\Models\Saldo;
use App\Models\Transaksi;
use App\Utils\Keuangan;
use Cookie;
use Session;

class DashboardController extends Controller
{
    public function index()
    {
        if (Session::get('_previous')['url'] == url('')) {
            $this->piutang();
        }

        $title = "Dashboard";
        return view('dashboard.index')->with(compact('title'));
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
}
