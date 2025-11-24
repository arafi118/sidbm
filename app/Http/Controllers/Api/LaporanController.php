<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PelaporanController;
use App\Models\JenisLaporan;
use App\Models\Rekening;
use App\Utils\Tanggal;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $charts = $this->_saldo_neraca(date('Y-m-d'));
        $daftarLaporan = JenisLaporan::where('mobile', 1)->orderBy('urut', 'ASC')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'charts' => $charts,
                'daftarLaporan' => $daftarLaporan
            ]
        ], 200);
    }

    public function preview(Request $request)
    {
        $pelaporanController = new PelaporanController();
        return $pelaporanController->preview($request);
    }

    private function _saldo_neraca($tgl)
    {
        $bulan = [];
        for ($i = 0; $i <= date('m'); $i++) {
            $bulan[$i] = [
                'aset' => 0,
                'liabilitas' => 0,
                'ekuitas' => 0,
                'pendapatan' => 0,
                'biaya' => 0
            ];
        }

        $rekening = Rekening::with([
            'kom_saldo' => function ($query) use ($tgl) {
                $tahun = date('Y', strtotime($tgl));
                $query->where([
                    ['tahun', $tahun],
                    ['bulan', '<=', date('m')],
                ])->orderBy('kode_akun', 'ASC')->orderBy('bulan', 'ASC');
            },
        ])->get();

        foreach ($rekening as $rek) {
            $awal_debit = 0;
            $saldo_debit = 0;
            $awal_kredit = 0;
            $saldo_kredit = 0;

            foreach ($rek->kom_saldo as $kom_saldo) {
                if ($kom_saldo->bulan == 0) {
                    $awal_debit = floatval($kom_saldo->debit);
                    $awal_kredit = floatval($kom_saldo->kredit);
                } else {
                    $saldo_debit = floatval($kom_saldo->debit);
                    $saldo_kredit = floatval($kom_saldo->kredit);
                }

                if ($rek->lev1 == '1' || $rek->lev1 == '5') {
                    $saldo_awal = $awal_debit - $awal_kredit;
                    $saldo = $saldo_awal + ($saldo_debit - $saldo_kredit);
                } else {
                    $saldo_awal = $awal_kredit - $awal_debit;
                    $saldo = $saldo_awal + ($saldo_kredit - $saldo_debit);
                }


                if ($rek->lev1 == '1') {
                    $bulan[intval($kom_saldo->bulan)]['aset'] += $saldo;
                }

                if ($rek->lev1 == '2') {
                    $bulan[intval($kom_saldo->bulan)]['liabilitas'] += $saldo;
                }

                if ($rek->lev1 == '3') {
                    if ($rek->kode_akun == '3.2.02.01') {
                        $saldo = 0;
                    }

                    $bulan[intval($kom_saldo->bulan)]['ekuitas'] += $saldo;
                }

                if ($rek->lev1 == '4') {
                    $bulan[intval($kom_saldo->bulan)]['pendapatan'] += $saldo;
                }

                if ($rek->lev1 == '5') {
                    $bulan[intval($kom_saldo->bulan)]['biaya'] += $saldo;
                }
            }
        }

        $nama_bulan = [];
        $aset = [];
        $liabilitas = [];
        $ekuitas = [];
        foreach ($bulan as $key => $value) {
            $saldo_aset = 0;
            $saldo_liabilitas = 0;
            $saldo_ekuitas = 0;
            $saldo_pendapatan = 0;
            $saldo_biaya = 0;

            if ($key > 0) {
                $saldo_aset = $value['aset'];
                $saldo_liabilitas = $value['liabilitas'];
                $saldo_ekuitas = $value['ekuitas'];
                $saldo_pendapatan = $value['pendapatan'];
                $saldo_biaya = $value['biaya'];
            }

            $surplus = $saldo_pendapatan - $saldo_biaya;

            $aset[$key] = $saldo_aset;
            $liabilitas[$key] = $saldo_liabilitas;
            $ekuitas[$key] = $saldo_ekuitas + $surplus;

            if ($key == 0) {
                $nama_bulan[$key] = 'Awl';
            } else {
                $tanggal = date('Y-m-d', strtotime(date('Y') . '-' . $key . '-01'));
                $nama_bulan[$key] = substr(Tanggal::namaBulan($tanggal), 0, 3);
            }
        }

        unset($aset[0]);
        unset($liabilitas[0]);
        unset($ekuitas[0]);
        unset($nama_bulan[0]);

        $saldo = [
            'nama_bulan' => $nama_bulan,
            'aset' => $aset,
            'liabilitas' => $liabilitas,
            'ekuitas' => $ekuitas
        ];

        return $saldo;
    }
}
