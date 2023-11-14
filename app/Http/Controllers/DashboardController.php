<?php

namespace App\Http\Controllers;

use App\Models\AdminInvoice;
use App\Models\AkunLevel1;
use App\Models\Desa;
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
use Cookie;
use DB;
use Session;

class DashboardController extends Controller
{
    public function index()
    {
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();
        if (Session::get('pesan')) {
            // $this->piutang();
            $this->sync(Session::get('lokasi'));
        }

        if (!Session::get('lokasi')) {
            Session::put('lokasi', Session::get('lokasi'));
        }

        $tgl_pakai = $kec->tgl_pakai;
        $tgl = date('Y-m-d');
        $jumlah = 1 + (date("Y", strtotime($tgl)) - date("Y", strtotime($tgl_pakai))) * 12;
        $jumlah += date("m", strtotime($tgl)) - date("m", strtotime($tgl_pakai));
        $data['jumlah'] = Rekening::count() + $jumlah;
        $data['request'] = '?tahun=' . date('Y', strtotime($tgl_pakai)) . '&bulan=' . date('m');

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
        $data['jumlah_saldo'] = Saldo::where('kode_akun', 'NOT LIKE', $kec->kd_kec . '%')->count();

        $data['title'] = "Dashboard";
        return view('dashboard.index')->with($data);
    }

    public function pinjaman()
    {
        $status = request()->get('status');
        if ($status == 'P') {
            $tgl = 'tgl_proposal';
            $alokasi = 'proposal';
        } else if ($status == 'V') {
            $tgl = 'tgl_verifikasi';
            $alokasi = 'verifikasi';
        } else if ($status == 'W') {
            $tgl = 'tgl_tunggu';
            $alokasi = 'alokasi';
        } else {
            $tgl = 'tgl_cair';
            $alokasi = 'alokasi';
        }

        $table = '';

        $no = 1;
        $pinjaman = PinjamanKelompok::where('status', $status)->with('saldo', 'kelompok', 'jpp', 'sts')->withCount('pinjaman_anggota')->get();
        foreach ($pinjaman as $pinkel) {
            $status = $pinkel->sts->warna_status;

            $table .= '<tr>';
            if ($pinkel->status == 'A') {
                $table .= '<td align="center">' . $no . '</td>';
                $table .= '<td align="center">' . Tanggal::tglIndo($pinkel->tgl_cair) . '</td>';
                $table .= '<td class="text-start d-flex justify-content-between">' . $pinkel->kelompok->nama_kelompok . '(' . $pinkel->jpp->nama_jpp . ') <span class="badge badge-' . $status . '">Loan ID. ' . $pinkel->id . '</span></td>';
                $table .= '<td align="right">' . number_format($pinkel->alokasi) . '</td>';
                if ($pinkel->saldo) {
                    $table .= '<td align="right">' . number_format($pinkel->saldo->saldo_pokok) . '</td>';
                } else {
                    $table .= '<td align="right">' . number_format(0) . '</td>';
                }
                $table .= '<td align="center">' . $pinkel->pinjaman_anggota_count . '</td>';
            } else {
                $table .= '<td align="center">' . $no . '</td>';
                $table .= '<td align="center">' . Tanggal::tglIndo($pinkel->$tgl) . '</td>';
                $table .= '<td class="text-start d-flex justify-content-between">' . $pinkel->kelompok->nama_kelompok . '(' . $pinkel->jpp->nama_jpp . ') <span class="badge badge-' . $status . '">Loan ID. ' . $pinkel->id . '</span></td>';
                $table .= '<td align="right">' . number_format($pinkel->$alokasi) . '</td>';
                $table .= '<td align="center">' . $pinkel->pinjaman_anggota_count . '</td>';
            }
            $table .= '</tr>';

            $no++;
        }

        return response()->json([
            'success' => true,
            'table' => $table
        ]);
    }

    public function pemanfaat()
    {
        $status = request()->get('status');
        if ($status == 'P') {
            $tgl = 'tgl_proposal';
            $alokasi = 'proposal';
        } else if ($status == 'V') {
            $tgl = 'tgl_verifikasi';
            $alokasi = 'verifikasi';
        } else if ($status == 'W') {
            $tgl = 'tgl_tunggu';
            $alokasi = 'alokasi';
        } else {
            $tgl = 'tgl_cair';
            $alokasi = 'alokasi';
        }

        $table = '';

        $no = 1;
        $pinjaman = PinjamanAnggota::where('status', $status)->with([
            'anggota',
            'anggota.d',
            'anggota.d.sebutan_desa',
            'kelompok'
        ])->get();
        foreach ($pinjaman as $pinkel) {
            $table .= '<tr>';

            $table .= '<td align="center">' . $no . '</td>';
            $table .= '<td>' . $pinkel->kelompok->nama_kelompok . ' - Loan ID. ' . $pinkel->id_pinkel . '</td>';
            $table .= '<td align="center">' . $pinkel->anggota->nik . '</td>';
            $table .= '<td>' . $pinkel->anggota->namadepan . '</td>';
            $table .= '<td>' . $pinkel->anggota->d->sebutan_desa->sebutan_desa . ' ' . $pinkel->anggota->d->nama_desa . ' ' . $pinkel->anggota->alamat . '</td>';
            $table .= '<td align="center">' . Tanggal::tglIndo($pinkel->$tgl) . '</td>';
            $table .= '<td align="right">' . number_format($pinkel->$alokasi) . '</td>';

            $table .= '</tr>';

            $no++;
        }

        return response()->json([
            'success' => true,
            'table' => $table
        ]);
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
            },
            'kelompok',
            'kelompok.d'
        ])->get();

        $table = '';
        $no = 1;
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

                    $table .= '<tr>';

                    $table .= '<td align="center">' . $no++ . '</td>';
                    $table .= '<td>' . $pinkel->kelompok->nama_kelompok . ' [' . $pinkel->kelompok->ketua . '][' . $pinkel->kelompok->d->nama_desa . '] - ' . $pinkel->id . '</td>';
                    $table .= '<td>' . Tanggal::tglIndo($pinkel->tgl_cair) . '</td>';
                    $table .= '<td align="right">' . number_format($pinkel->alokasi) . '</td>';
                    $table .= '<td align="right">' . number_format($nunggak_pokok) . '</td>';
                    $table .= '<td align="right">' . number_format($nunggak_jasa) . '</td>';

                    $table .= '</tr>';
                }
            }
        }

        return response()->json([
            'success' => true,
            'jatuh_tempo' => $jatuh_tempo,
            'hari_ini' => $table
        ]);
    }

    public function nunggak(Request $request)
    {
        $tgl = Tanggal::tglNasional($request->tgl);
        $pinjaman = PinjamanKelompok::where('status', 'A')->whereDay('tgl_cair', '<=', $tgl)->with([
            'target' => function ($query) use ($tgl) {
                $query->where([
                    ['jatuh_tempo', '<=', $tgl],
                    ['angsuran_ke', '!=', '0']
                ]);
            },
            'saldo' => function ($query) use ($tgl) {
                $query->where('tgl_transaksi', '<=', $tgl);
            },
            'kelompok',
            'kelompok.d'
        ])->orderBy('tgl_cair', 'ASC')->orderBy('id', 'ASC')->get();

        $nunggak = "00";
        $table = '';
        $no = 1;
        foreach ($pinjaman as $pinkel) {
            $real_pokok = 0;
            $real_jasa = 0;
            $sum_pokok = 0;
            $sum_jasa = 0;
            $saldo_pokok = $pinkel->alokasi;
            $saldo_jasa = $pinkel->pros_jasa == 0 ? 0 : $pinkel->alokasi / $pinkel->pros_jasa;
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
                $table .= '<tr>';

                $table .= '<td align="center">' . $no++ . '</td>';
                $table .= '<td>' . $pinkel->kelompok->nama_kelompok . ' [' . $pinkel->kelompok->ketua . '][' . $pinkel->kelompok->d->nama_desa . '] - ' . $pinkel->id . '</td>';
                $table .= '<td>' . Tanggal::tglIndo($pinkel->tgl_cair) . '</td>';
                $table .= '<td align="right">' . number_format($pinkel->alokasi) . '</td>';
                $table .= '<td align="right">' . number_format($tunggakan_pokok) . '</td>';
                $table .= '<td align="right">' . number_format($tunggakan_jasa) . '</td>';

                $table .= '</tr>';
            }
        }

        return response()->json([
            'success' => true,
            'nunggak' => $nunggak,
            'table' => $table
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
        $bulan = request()->get('bulan') ?: 12;

        if (Saldo::count() <= 0) {
            $saldo_desa = [];
            $kec = Kecamatan::where('id', auth()->user()->lokasi)->with('desa')->first();
            foreach ($kec->desa as $desa) {
                $saldo_desa[] = [
                    'id' => $desa->kd_desa,
                    'kode_akun' => $desa->kode_desa,
                    'tahun' => $tahun,
                    'bulan' => 0,
                    'debit' => 0,
                    'kredit' => 0
                ];
            }

            $saldo_desa[] = [
                'id' => 1,
                'kode_akun' => $kec->kd_kec,
                'tahun' => $tahun,
                'bulan' => 0,
                'debit' => 0,
                'kredit' => 0
            ];
            $saldo_desa[] = [
                'id' => 2,
                'kode_akun' => $kec->kd_kec,
                'tahun' => $tahun,
                'bulan' => 0,
                'debit' => 0,
                'kredit' => 0
            ];
            $saldo_desa[] = [
                'id' => 3,
                'kode_akun' => $kec->kd_kec,
                'tahun' => $tahun,
                'bulan' => 0,
                'debit' => 0,
                'kredit' => 0
            ];
            $saldo_desa[] = [
                'id' => 4,
                'kode_akun' => $kec->kd_kec,
                'tahun' => $tahun,
                'bulan' => 0,
                'debit' => 0,
                'kredit' => 0
            ];
            $saldo_desa[] = [
                'id' => 5,
                'kode_akun' => $kec->kd_kec,
                'tahun' => $tahun,
                'bulan' => 0,
                'debit' => 0,
                'kredit' => 0
            ];
            $saldo_desa[] = [
                'id' => 6,
                'kode_akun' => $kec->kd_kec,
                'tahun' => $tahun,
                'bulan' => 0,
                'debit' => 0,
                'kredit' => 0
            ];

            Saldo::insert($saldo_desa);
        }

        $data_id = [];
        // for ($i = 1; $i <= $bulan; $i++) {
        //     $date = $tahun . '-' . $i . '-01';
        //     $tgl_kondisi = date('Y-m-t', strtotime($date));
        //     $rekening = Rekening::withSum([
        //         'trx_debit' => function ($query) use ($tgl_kondisi, $tahun) {
        //             $query->whereBetween('tgl_transaksi', [$tahun . '-01-01', $tgl_kondisi]);
        //         }
        //     ], 'jumlah')->withSum([
        //         'trx_kredit' => function ($query) use ($tgl_kondisi, $tahun) {
        //             $query->whereBetween('tgl_transaksi', [$tahun . '-01-01', $tgl_kondisi]);
        //         }
        //     ], 'jumlah')->orderBy('kode_akun', 'ASC')->get();

        //     $saldo = [];
        //     foreach ($rekening as $rek) {
        //         $id = str_replace('.', '', $rek->kode_akun) . $tahun . str_pad($i, 2, "0", STR_PAD_LEFT);
        //         if (!in_array($id, $data_id)) {
        //             $saldo[] = [
        //                 'id' => $id,
        //                 'kode_akun' => $rek->kode_akun,
        //                 'tahun' => $tahun,
        //                 'bulan' => $i,
        //                 'debit' => $rek->trx_debit_sum_jumlah,
        //                 'kredit' => $rek->trx_kredit_sum_jumlah
        //             ];

        //             $data_id[] = $id;
        //         }
        //     }

        //     $query = Saldo::insert($saldo);
        // }
    }

    private function _saldo($tgl)
    {
        $data = [
            '4' => [
                '1' => 0,
                '2' => 0,
                '3' => 0,
                '4' => 0,
                '5' => 0,
                '6' => 0,
                '7' => 0,
                '8' => 0,
                '9' => 0,
                '10' => 0,
                '11' => 0,
                '12' => 0,
            ],
            '5' => [
                '1' => 0,
                '2' => 0,
                '3' => 0,
                '4' => 0,
                '5' => 0,
                '6' => 0,
                '7' => 0,
                '8' => 0,
                '9' => 0,
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
            '1' => $kom_saldo['4']['1'] - $kom_saldo['5']['1'],
            '2' => $kom_saldo['4']['2'] - $kom_saldo['5']['2'],
            '3' => $kom_saldo['4']['3'] - $kom_saldo['5']['3'],
            '4' => $kom_saldo['4']['4'] - $kom_saldo['5']['4'],
            '5' => $kom_saldo['4']['5'] - $kom_saldo['5']['5'],
            '6' => $kom_saldo['4']['6'] - $kom_saldo['5']['6'],
            '7' => $kom_saldo['4']['7'] - $kom_saldo['5']['7'],
            '8' => $kom_saldo['4']['8'] - $kom_saldo['5']['8'],
            '9' => $kom_saldo['4']['9'] - $kom_saldo['5']['9'],
            '10' => $kom_saldo['4']['10'] - $kom_saldo['5']['10'],
            '11' => $kom_saldo['4']['11'] - $kom_saldo['5']['11'],
            '12' => $kom_saldo['4']['12'] - $kom_saldo['5']['12'],
        ];

        return $kom_saldo;
    }

    public function unpaid()
    {
        $invoice = AdminInvoice::where([
            ['lokasi', auth()->user()->lokasi],
            ['status', 'UNPAID']
        ])->orderBy('tgl_invoice', 'DESC');

        $jumlah = 0;
        if ($invoice->count() > 0) {
            $jumlah = $invoice->count();
            $inv = $invoice->first();
        }

        return response()->json([
            'success' => true,
            'invoice' => $jumlah
        ]);
    }

    public function simpanSaldo()
    {
        $tahun = request()->get('tahun') ?: date('Y');
        $bulan = request()->get('bulan') ?: date('m');

        $date = $tahun . '-' . $bulan . '-01';
        $tgl_kondisi = date('Y-m-t', strtotime($date));
        $rekening = Rekening::withSum([
            'trx_debit' => function ($query) use ($tgl_kondisi, $tahun) {
                $query->whereBetween('tgl_transaksi', [$tahun . '-01-01', $tgl_kondisi]);
            }
        ], 'jumlah')->withSum([
            'trx_kredit' => function ($query) use ($tgl_kondisi, $tahun) {
                $query->whereBetween('tgl_transaksi', [$tahun . '-01-01', $tgl_kondisi]);
            }
        ], 'jumlah')->orderBy('kode_akun', 'ASC')->get();

        $saldo = [];
        foreach ($rekening as $rek) {
            $id = str_replace('.', '', $rek->kode_akun) . $tahun . str_pad($bulan, 2, "0", STR_PAD_LEFT);
            $saldo[] = [
                'id' => $id,
                'kode_akun' => $rek->kode_akun,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'debit' => $rek->trx_debit_sum_jumlah,
                'kredit' => $rek->trx_kredit_sum_jumlah
            ];

            $data_id[] = $id;
        }

        Saldo::whereIn('id', $data_id)->delete();
        $query = Saldo::insert($saldo);

        $link = request()->url('');
        $query = request()->query();

        if (isset($query['bulan'])) {
            $query['bulan'] += 1;
        } else {
            $query['bulan'] = date('m') + 1;
        }
        if (isset($query['tahun'])) {
            $query['tahun'] += 1;
        } else {
            $query['tahun'] = date('Y');
        }

        $query['bulan'] = str_pad($query['bulan'], 2, '0', STR_PAD_LEFT);
        $next = $link . '?' . http_build_query($query);

        if ($query['bulan'] < 13) {
            echo '<a href="' . $next . '" id="next"></a><script>document.querySelector("#next").click()</script>';
            exit;
        } else {
            echo '<script>window.opener.postMessage("closed", "*"); window.close();</script>';
            exit;
        }
    }
}
