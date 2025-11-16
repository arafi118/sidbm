<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rekening;
use App\Utils\Tanggal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $charts = $this->_saldo(date('Y-m-d'));

        return response()->json([
            'success' => true,
            'data' => $charts
        ], 200);
    }

    private function _saldo($tgl)
    {
        $bulan = [];
        for ($i = 0; $i <= date('m'); $i++) {
            $bulan[$i] = [
                'pendapatan' => 0,
                'beban' => 0
            ];
        }

        $rekening = Rekening::where('lev1', '>=', '4')->with([
            'kom_saldo' => function ($query) use ($tgl) {
                $tahun = date('Y', strtotime($tgl));
                $query->where([
                    ['tahun', $tahun],
                    ['bulan', '<=', date('m')],
                ])->orderBy('kode_akun', 'ASC')->orderBy('bulan', 'ASC');
            },
        ])->get();

        foreach ($rekening as $rek) {
            foreach ($rek->kom_saldo as $kom_saldo) {
                $debit = 0;
                if ($kom_saldo->debit) {
                    $debit = $kom_saldo->debit;
                }
                $kredit = 0;
                if ($kom_saldo->kredit) {
                    $kredit = $kom_saldo->kredit;
                }

                $saldo = $kredit - $debit;
                if ($rek->lev1 > '4') {
                    $saldo = $debit - $kredit;
                }

                if ($rek->lev1 == '4') {
                    $bulan[intval($kom_saldo->bulan)]['pendapatan'] += $saldo;
                } else {
                    $bulan[intval($kom_saldo->bulan)]['beban'] += $saldo;
                }
            }
        }


        $nama_bulan = [];
        $pendapatan = [];
        $beban = [];
        $surplus = [];
        foreach ($bulan as $key => $value) {
            $saldo_pendapatan = 0;
            $saldo_beban = 0;
            if ($key > 0) {
                $saldo_pendapatan = $value['pendapatan'] - $bulan[$key - 1]['pendapatan'];
                $saldo_beban = $value['beban'] - $bulan[$key - 1]['beban'];
            }

            $pendapatan[$key] = $saldo_pendapatan;
            $beban[$key] = $saldo_beban;
            $surplus[$key] = $saldo_pendapatan - $saldo_beban;

            if ($key == 0) {
                $nama_bulan[$key] = 'Awal Tahun';
            } else {
                $tanggal = date('Y-m-d', strtotime(date('Y') . '-' . $key . '-01'));
                $nama_bulan[$key] = Tanggal::namaBulan($tanggal);
            }
        }

        $saldo = [
            'nama_bulan' => $nama_bulan,
            'pendapatan' => $pendapatan,
            'beban' => $beban,
            'surplus' => $surplus
        ];

        return $saldo;
    }
}
