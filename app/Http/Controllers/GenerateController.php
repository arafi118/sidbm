<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\PinjamanAnggota;
use App\Models\PinjamanIndividu;
use App\Models\PinjamanKelompok;
use App\Models\RealAngsuran;
use App\Models\RealAngsuranI;
use App\Models\RencanaAngsuran;
use App\Models\RencanaAngsuranI;
use App\Utils\Keuangan;
use Illuminate\Http\Request;
use Session;
use URL;

class GenerateController extends Controller
{
    public function index()
    {
        $kec = Kecamatan::where('web_kec', explode('//', URL::to('/'))[1])
            ->orWhere('web_alternatif', explode('//', URL::to('/'))[1])
            ->first();

        Session::put('lokasi', $kec->id);

        $logo = '/assets/img/icon/favicon.png';
        if ($kec->logo) {
            $logo = '/storage/logo/' . $kec->logo;
        }

        return view('generate.index')->with(compact('logo'));
    }

    public function individu()
    {
        $database = env('DB_DATABASE', 'siupk_dbm');
        $table = 'pinjaman_anggota_' . Session::get('lokasi');

        $strukturTabel = \DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = '$table' AND TABLE_SCHEMA='$database'
            ORDER BY ORDINAL_POSITION;
        ");

        $struktur = array_map(function ($kolom) {
            return $kolom->COLUMN_NAME;
        }, $strukturTabel);

        return response()->json([
            'view' => view('generate.partials.individu')->with(compact('struktur'))->render()
        ]);
    }

    public function kelompok()
    {
        $database = env('DB_DATABASE', 'siupk_dbm');
        $table = 'pinjaman_kelompok_' . Session::get('lokasi');

        $strukturTabel = \DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = '$table' AND TABLE_SCHEMA='$database'
            ORDER BY ORDINAL_POSITION;
        ");

        $struktur = array_map(function ($kolom) {
            return $kolom->COLUMN_NAME;
        }, $strukturTabel);

        return response()->json([
            'view' => view('generate.partials.kelompok')->with(compact('struktur'))->render()
        ]);
    }

    public function generate(Request $request, $offset = 0)
    {
        $real = [];
        $rencana = [];
        $is_pinkel = ($request->pinjaman == 'kelompok') ? true : false;
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();

        $where = [];
        $whereIn = [];
        $whereNotIn = [];
        foreach ($request->all() as $key => $val) {
            if ($key == '_token' || $key == 'pinjaman') {
                continue;
            }

            $opt = '=';
            $value = $val;
            if (is_array($val)) {
                $opt = $val['operator'];
                $value = $val['value'];
                if (!$value) {
                    continue;
                }

                if ($opt == 'IN') {
                    $values = explode(',', $value);

                    $value = [];
                    foreach ($values as $v) {
                        $whereIn[$key][] = $v;
                    }

                    continue;
                }

                if ($opt == 'NOT IN') {
                    $values = explode(',', $value);

                    $value = [];
                    foreach ($values as $v) {
                        $whereNotIn[$key][] = $v;
                    }

                    continue;
                }
            }

            $where[] = [$key, $opt, $value];
        }

        $limit = 30;
        $pinjaman = PinjamanKelompok::whereNotIn('status', ['H', 'R'])->where(function ($query) use ($where, $whereIn, $whereNotIn) {
            $query->where($where);
            if (count($whereIn) > 0) {
                foreach ($whereIn as $key => $value) {
                    $query->whereIn($key, $value);
                }
            }

            if (count($whereNotIn) > 0) {
                foreach ($whereNotIn as $key => $value) {
                    $query->whereNotIn($key, $value);
                }
            }
        })->with([
            'pinjaman' => function ($query) {
                $query->where('status', 'H');
            },
            'sis_pokok',
            'sis_jasa',
            'trx' => function ($query) {
                $query->where('idtp', '!=', '0');
            },
            'trx.tr_idtp',
            'kelompok',
            'kelompok.d'
        ]);

        $pinjaman = $pinjaman->limit($limit)->offset($offset)->orderBy('id', 'ASC')->get();

        $data_pinj_H = [];
        $data_id_pinj = [];
        $data_id_real = [];
        foreach ($pinjaman as $pinkel) {
            if ($pinkel->pinjaman) {
                $data_pinj_H[] = $pinkel->id;
                continue;
            }

            $data_id_pinj[] = $pinkel->id;

            if ($pinkel->status == 'P') {
                $alokasi = $pinkel->proposal;
                $tgl_cair = $pinkel->tgl_proposal;
            } elseif ($pinkel->status == 'V') {
                $alokasi = $pinkel->verifikasi;
                $tgl_cair = $pinkel->tgl_verifikasi;
            } elseif ($pinkel->status == 'W') {
                $alokasi = $pinkel->alokasi;
                $tgl_cair = $pinkel->tgl_cair;

                if ($tgl_cair == "0000-00-00") {
                    $tgl_cair = $pinkel->tgl_tunggu;
                }
            } else {
                $alokasi = $pinkel->alokasi;
                $tgl_cair = $pinkel->tgl_cair;

                if ($tgl_cair == "0000-00-00") {
                    $tgl_cair = $pinkel->tgl_tunggu;
                }
            }

            $tgl_angsur = $tgl_cair;
            $desa = $pinkel->kelompok->d;
            $tanggal_cair = date('d', strtotime($tgl_cair));

            if ($desa->jadwal_angsuran_desa > 0) {
                $angsuran_desa = $desa->jadwal_angsuran_desa;
                if ($angsuran_desa > 0) {
                    $tgl_pinjaman = date('Y-m', strtotime($tgl_cair));
                    $tgl_cair = $tgl_pinjaman . '-' . $angsuran_desa;
                }
            }

            if ($kec->batas_angsuran > 0) {
                $batas_tgl_angsuran = $kec->batas_angsuran;
                if ($tanggal_cair >= $batas_tgl_angsuran) {
                    $tgl_cair = date('Y-m-d', strtotime('+1 month', strtotime($tgl_cair)));
                }
            }

            $jenis_jasa = $pinkel->jenis_jasa;
            $jangka = $pinkel->jangka;
            $sa_pokok = $pinkel->sistem_angsuran;
            $sa_jasa = $pinkel->sa_jasa;
            $pros_jasa = $pinkel->pros_jasa;

            $sistem_pokok = ($pinkel->sis_pokok) ? $pinkel->sis_pokok->sistem : '1';
            $sistem_jasa = ($pinkel->sis_jasa) ? $pinkel->sis_jasa->sistem : '1';

            if ($sa_pokok == 11) {
                $tempo_pokok = ($jangka) - 24 / $sistem_pokok;
                $mulai_angsuran_pokok = $jangka - $tempo_pokok;
            } else if ($sa_pokok == 14) {
                $tempo_pokok = ($jangka) - 3 / $sistem_pokok;
                $mulai_angsuran_pokok = $jangka - $tempo_pokok;
            } else if ($sa_pokok == 15) {
                $tempo_pokok = ($jangka) - 2 / $sistem_pokok;
                $mulai_angsuran_pokok = $jangka - $tempo_pokok;
            } else if ($sa_pokok == 25) {
                $tempo_pokok = ($jangka) - 1 / $sistem_pokok;
                $mulai_angsuran_pokok = $jangka - $tempo_pokok;
            } else if ($sa_pokok == 20) {
                $tempo_pokok = ($jangka) - 12 / $sistem_pokok;
                $mulai_angsuran_pokok = $jangka - $tempo_pokok;
            } else {
                $tempo_pokok = floor($jangka / $sistem_pokok);
                $mulai_angsuran_pokok = 0;
            }

            if ($sa_jasa == 11) {
                $tempo_jasa = ($jangka) - 24 / $sistem_jasa;
                $mulai_angsuran_jasa = $jangka - $tempo_jasa;
            } else if ($sa_jasa == 14) {
                $tempo_jasa = ($jangka) - 3 / $sistem_jasa;
                $mulai_angsuran_jasa = $jangka - $tempo_jasa;
            } else if ($sa_jasa == 15) {
                $tempo_jasa = ($jangka) - 2 / $sistem_jasa;
                $mulai_angsuran_jasa = $jangka - $tempo_jasa;
            } else if ($sa_pokok == 25) {
                $tempo_jasa = ($jangka) - 1 / $sistem_jasa;
                $mulai_angsuran_jasa = $jangka - $tempo_jasa;
            } else if ($sa_jasa == 20) {
                $tempo_jasa = ($jangka) - 12 / $sistem_jasa;
                $mulai_angsuran_jasa = $jangka - $tempo_jasa;
            } else {
                $tempo_jasa = floor($jangka / $sistem_jasa);
                $mulai_angsuran_jasa = 0;
            }

            $ra = [];
            $alokasi_pokok = $alokasi;
            if ($jenis_jasa == '1') {
                for ($j = 1; $j <= $jangka; $j++) {
                    $sisa = $j % $sistem_jasa;
                    $ke = $j / $sistem_jasa;

                    $alokasi_jasa = $alokasi_pokok * ($pros_jasa / 100);
                    $wajib_jasa = $alokasi_jasa / $tempo_jasa;
                    $wajib_jasa = Keuangan::pembulatan(intval($wajib_jasa), (string) $kec->pembulatan);
                    $sum_jasa = $wajib_jasa * ($tempo_jasa - 1);

                    if ($sisa == 0 and $ke != $tempo_jasa && $ke > $mulai_angsuran_jasa) {
                        $angsuran_jasa = $wajib_jasa;
                    } elseif ($sisa == 0 and $ke == $tempo_jasa) {
                        $angsuran_jasa = $alokasi_jasa - $sum_jasa;
                    } else {
                        $angsuran_jasa = 0;
                    }

                    if ($jenis_jasa == '2') {
                        $angsuran_jasa = $wajib_jasa;
                        $alokasi_pokok -= $ra[$j]['pokok'];
                    }

                    $ra[$j]['jasa'] = $angsuran_jasa;
                }
            }

            for ($i = 1; $i <= $jangka; $i++) {
                $sisa = $i % $sistem_pokok;
                $ke = $i / $sistem_pokok;

                $wajib_pokok = Keuangan::pembulatan($alokasi / $tempo_pokok, (string) $kec->pembulatan);
                $sum_pokok = $wajib_pokok * ($tempo_pokok - 1);

                if ($sisa == 0 and $ke != $jangka && $ke > $mulai_angsuran_pokok) {
                    $angsuran_pokok = $wajib_pokok;
                } elseif ($sisa == 0 and $ke == $jangka) {
                    $angsuran_pokok = $alokasi - $sum_pokok;
                } else {
                    $angsuran_pokok = 0;
                }

                $ra[$i]['pokok'] = $angsuran_pokok;
            }

            if ($jenis_jasa != '1') {
                for ($j = 1; $j <= $jangka; $j++) {
                    $sisa = $j % $sistem_jasa;
                    $ke = $j / $sistem_jasa;

                    $alokasi_jasa = $alokasi_pokok * ($pros_jasa / 100);
                    $wajib_jasa = $alokasi_jasa / $tempo_jasa;
                    $wajib_jasa = Keuangan::pembulatan(intval($wajib_jasa), (string) $kec->pembulatan);
                    $sum_jasa = $wajib_jasa * ($tempo_jasa - 1);

                    if ($sisa == 0 and $ke != $tempo_jasa && $ke > $mulai_angsuran_jasa) {
                        $angsuran_jasa = $wajib_jasa;
                    } elseif ($sisa == 0 and $ke == $tempo_jasa) {
                        $angsuran_jasa = $alokasi_jasa - $sum_jasa;
                    } else {
                        $angsuran_jasa = 0;
                    }

                    if ($jenis_jasa == '2') {
                        $angsuran_jasa = $wajib_jasa;
                        $alokasi_pokok -= $ra[$j]['pokok'];
                    }

                    $ra[$j]['jasa'] = $angsuran_jasa;
                }
            }

            $ra['alokasi'] = $alokasi;

            $target_pokok = 0;
            $target_jasa = 0;

            $data_rencana = [];
            $data_rencana[strtotime($tgl_cair)] = [
                'loan_id' => $pinkel->id,
                'angsuran_ke' => 0,
                'jatuh_tempo' => $tgl_cair,
                'wajib_pokok' => 0,
                'wajib_jasa' => 0,
                'target_pokok' => $target_pokok,
                'target_jasa' => $target_jasa,
                'lu' => date('Y-m-d H:i:s'),
                'id_user' => 1
            ];
            $rencana[] = $data_rencana[strtotime($tgl_cair)];

            for ($x = 1; $x <= $jangka; $x++) {
                $bulan  = substr($tgl_cair, 5, 2);
                $tahun  = substr($tgl_cair, 0, 4);

                if ($sa_pokok == 12) {
                    $tambah = $x * 7;
                    $penambahan = "+$tambah days";
                } else {
                    $penambahan = "+$x month";
                }

                $tanggal_cair = date('Y-m', strtotime($tgl_cair));
                $jatuh_tempo = date('Y-m', strtotime($penambahan, strtotime($tanggal_cair)));

                if (date('d', strtotime($tgl_cair)) > date('t', strtotime($jatuh_tempo))) {
                    $jatuh_tempo = date('Y-m-t', strtotime($jatuh_tempo));
                } else {
                    $jatuh_tempo = date('Y-m', strtotime($jatuh_tempo)) . '-' . date('d', strtotime($tgl_cair));
                }

                $pokok = $ra[$x]['pokok'];
                $jasa = $ra[$x]['jasa'];

                if ($x == 1) {
                    $target_pokok = $pokok;
                } elseif ($x >= 2) {
                    $target_pokok += $pokok;
                }
                if ($x == 1) {
                    $target_jasa = $jasa;
                } elseif ($x >= 2) {
                    $target_jasa += $jasa;
                }

                $data_rencana[strtotime($jatuh_tempo)] = [
                    'loan_id' => $pinkel->id,
                    'angsuran_ke' => $x,
                    'jatuh_tempo' => $jatuh_tempo,
                    'wajib_pokok' => $pokok,
                    'wajib_jasa' => $jasa,
                    'target_pokok' => $target_pokok,
                    'target_jasa' => $target_jasa,
                    'lu' => date('Y-m-d H:i:s'),
                    'id_user' => 1
                ];
                $rencana[] = $data_rencana[strtotime($jatuh_tempo)];
            }

            $alokasi_pokok = $alokasi;
            $alokasi_jasa = $target_jasa;

            $data_idtp = [];
            $sum_pokok = 0;
            $sum_jasa = 0;

            ksort($data_rencana);
            foreach ($pinkel->trx as $trx) {
                $poko_kredit = ['1.1.03.01', '1.1.03.02', '1.1.03.03'];
                $jasa_kredit = ['1.1.03.04', '1.1.03.05', '1.1.03.06', '4.1.01.01', '4.1.01.02', '4.1.01.03'];
                $dend_kredit = ['4.1.01.04', '4.1.01.05', '4.1.01.06'];

                if (in_array($trx->rekening_kredit, $dend_kredit)) continue;
                if (in_array($trx->idtp, $data_idtp)) continue;

                $tgl_transaksi = $trx->tgl_transaksi;
                $realisasi_pokok = 0;
                $realisasi_jasa = 0;

                foreach ($trx->tr_idtp as $idtp) {
                    if (in_array($idtp->rekening_kredit, $poko_kredit)) {
                        $realisasi_pokok += floatval($idtp->jumlah);
                    }

                    if (in_array($idtp->rekening_kredit, $jasa_kredit)) {
                        $realisasi_jasa += floatval($idtp->jumlah);
                    }
                }

                $sum_pokok += $realisasi_pokok;
                $sum_jasa += $realisasi_jasa;

                $alokasi_pokok -= $realisasi_pokok;
                $alokasi_jasa -= $realisasi_jasa;

                $ra = [];
                $time_transaksi = strtotime($tgl_transaksi);

                foreach ($data_rencana as $key => $value) {
                    if ($key <= $time_transaksi) {
                        $ra = $value;
                    }
                }

                $target_pokok = 0;
                $target_jasa = 0;
                if ($ra) {
                    $target_pokok = $ra['target_pokok'];
                    $target_jasa = $ra['target_jasa'];
                }

                $tunggakan_pokok = $target_pokok - $sum_pokok;
                $tunggakan_jasa = $target_jasa - $sum_jasa;

                if ($tunggakan_pokok < 0) {
                    $tunggakan_pokok = 0;
                }

                if ($tunggakan_jasa < 0) {
                    $tunggakan_jasa = 0;
                }

                if ($realisasi_pokok + $realisasi_jasa != '0') {
                    $real[$trx->idtp] = [
                        'id' => $trx->idtp,
                        'loan_id' => $pinkel->id,
                        'tgl_transaksi' => $tgl_transaksi,
                        'realisasi_pokok' => $realisasi_pokok,
                        'realisasi_jasa' => $realisasi_jasa,
                        'sum_pokok' => $sum_pokok,
                        'sum_jasa' => $sum_jasa,
                        'saldo_pokok' => $alokasi_pokok,
                        'saldo_jasa' => $alokasi_jasa,
                        'tunggakan_pokok' => $tunggakan_pokok,
                        'tunggakan_jasa' => $tunggakan_jasa,
                        'lu' => date('Y-m-d H:i:s'),
                        'id_user' => 1,
                    ];
                }

                $data_id_real[] = $trx->idtp;
                $data_idtp[] = $trx->idtp;
            }
        }

        RencanaAngsuran::whereIn('loan_id', $data_id_pinj)->delete();
        RealAngsuran::whereIn('loan_id', $data_id_pinj)->delete();
        RealAngsuran::whereIn('id', $data_id_real)->delete();

        RencanaAngsuran::insert($rencana);
        RealAngsuran::insert($real);

        array_push($data_id_pinj, $data_pinj_H);
        $data = $request->all();
        $offset = $offset + $limit;
        return view('generate.generate')->with(compact('data_id_pinj', 'data', 'offset', 'limit'));
    }
}
