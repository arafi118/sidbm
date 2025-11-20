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
                'ekuitas' => 0
            ];
        }

        $rekening = Rekening::where('lev1', '<', '4')->with([
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
                if ($rek->lev1 == '1') {
                    $saldo = $debit - $kredit;
                }

                if ($rek->lev1 == '1') {
                    $bulan[intval($kom_saldo->bulan)]['aset'] += $saldo;
                }

                if ($rek->lev1 == '2') {
                    $bulan[intval($kom_saldo->bulan)]['liabilitas'] += $saldo;
                }

                if ($rek->lev1 == '3') {
                    $bulan[intval($kom_saldo->bulan)]['ekuitas'] += $saldo;
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

            if ($key > 0) {
                $saldo_aset = $value['aset'] - $bulan[$key - 1]['aset'];
                $saldo_liabilitas = $value['liabilitas'] - $bulan[$key - 1]['liabilitas'];
                $saldo_ekuitas = $value['ekuitas'] - $bulan[$key - 1]['ekuitas'];
            }

            $aset[$key] = $saldo_aset;
            $liabilitas[$key] = $saldo_liabilitas;
            $ekuitas[$key] = $saldo_ekuitas;

            if ($key == 0) {
                $nama_bulan[$key] = 'Awal Tahun';
            } else {
                $tanggal = date('Y-m-d', strtotime(date('Y') . '-' . $key . '-01'));
                $nama_bulan[$key] = Tanggal::namaBulan($tanggal);
            }
        }

        $saldo = [
            'nama_bulan' => $nama_bulan,
            'aset' => $aset,
            'liabilitas' => $liabilitas,
            'ekuitas' => $ekuitas
        ];

        return $saldo;
    }
}
