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

        return view('generate.index')->with(compact('logo', 'struktur'));
    }

    public function generate(Request $request, $offset = 0)
    {
        $real = [];
        $rencana = [];
        $data_id_real = [];
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();

        $kode_pokok = ['1.1.03.01', '1.1.03.02', '1.1.03.03'];
        $kode_jasa = ['1.1.03.04', '1.1.03.05', '1.1.03.06', '4.1.01.01', '4.1.01.02', '4.1.01.03'];
        $kode_denda = ['4.1.01.04', '4.1.01.05', '4.1.01.06'];

        $kondisi = $request->except('_token', 'pinjaman', 'generate_version');
        $where = $this->where($kondisi);

        $limit = 30;
        $pinjaman = PinjamanKelompok::where(function ($query) use ($where) {
            $query->where($where['where']);
            if (count($where['whereIn']) > 0) {
                foreach ($where['whereIn'] as $key => $value) {
                    $query->whereIn($key, $value);
                }
            }

            if (count($where['whereNotIn']) > 0) {
                foreach ($where['whereNotIn'] as $key => $value) {
                    $query->whereNotIn($key, $value);
                }
            }
        })->with([
            'pinjaman_anggota',
            'sis_pokok',
            'sis_jasa',
            'trx' => function ($query) use ($kode_denda) {
                $query->where('idtp', '!=', '0')->whereNotIn('rekening_kredit', $kode_denda);
            },
            'trx.tr_idtp' => function ($query) use ($kode_denda) {
                $query->whereNotIn('rekening_kredit', $kode_denda);
            },
            'trx_penghapusan',
            'trx_penghapusan.tr_idtp',
            'kelompok',
            'kelompok.d'
        ]);

        $data_pinjaman = [];
        $data_pinjaman_H = [];

        $pinjaman = $pinjaman->limit($limit)->offset($offset)->orderBy('id', 'ASC')->get();
        foreach ($pinjaman as $pinkel) {
            if ($pinkel->status == 'H') {
                $data_pinjaman_H[] = $pinkel->id;
                continue;
            }

            $data_pinjaman[] = $pinkel->id;
            $desa = $pinkel->kelompok->d;

            $jangka = $pinkel->jangka;
            $sistem_angsuran_pokok = $pinkel->sistem_angsuran;
            $sistem_angsuran_jasa = $pinkel->sa_jasa;
            $sistem_pokok = ($pinkel->sis_pokok) ? $pinkel->sis_pokok->sistem : '1';
            $sistem_jasa = ($pinkel->sis_jasa) ? $pinkel->sis_jasa->sistem : '1';

            $angsuran_pokok = $this->sistem($sistem_angsuran_pokok, $jangka, $sistem_pokok);
            $angsuran_jasa = $this->sistem($sistem_angsuran_jasa, $jangka, $sistem_jasa);

            if (count($pinkel->pinjaman_anggota) > 0) {
                $alokasi = 0;
                $rencana_pokok = [];
                $rencana_jasa = [];
                $alokasi_jasa_anggota = [];
                foreach ($pinkel->pinjaman_anggota as $pinjaman_anggota) {
                    $detail_pinjaman = $this->detail_pinjaman($pinjaman_anggota, $desa, $kec->batas_angsuran);
                    $alokasi_anggota = $detail_pinjaman['alokasi'];
                    $alokasi_jasa_anggota[$pinjaman_anggota->id] = $alokasi_anggota * ($pinjaman_anggota->pros_jasa / 100);
                    $tgl_cair = $detail_pinjaman['tgl_cair'];

                    $rencana_angsuran = $this->rencana_angsuran($pinjaman_anggota, $angsuran_pokok, $angsuran_jasa, $alokasi_anggota, $kec->pembulatan);
                    $pokok = $rencana_angsuran['pokok'];
                    $jasa = $rencana_angsuran['jasa'];

                    if (count($rencana_pokok) <= 0) {
                        $rencana_pokok = $pokok;
                    } else {
                        foreach ($pokok as $key => $value) {
                            $rencana_pokok[$key] = ($rencana_pokok[$key] ?? 0) + $value;
                        }
                    }

                    if (count($rencana_jasa) <= 0) {
                        $rencana_jasa = $jasa;
                    } else {
                        foreach ($jasa as $key => $value) {
                            $rencana_jasa[$key] = ($rencana_jasa[$key] ?? 0) + $value;
                        }
                    }

                    $alokasi += $alokasi_anggota;
                }
            } else {
                $detail_pinjaman = $this->detail_pinjaman($pinkel, $desa, $kec->batas_angsuran);
                $alokasi = $detail_pinjaman['alokasi'];
                $tgl_cair = $detail_pinjaman['tgl_cair'];

                $rencana_angsuran = $this->rencana_angsuran($pinkel, $angsuran_pokok, $angsuran_jasa, $alokasi, $kec->pembulatan, 'pokok');
                $rencana_pokok = $rencana_angsuran['pokok'];
                $rencana_jasa = $rencana_angsuran['jasa'];
            }

            $nomor = 1;
            $data_penghapusan = [];
            $data_idtp_penghapusan = [];
            foreach ($pinkel->trx_penghapusan as $trx_penghapusan) {
                if (in_array($trx_penghapusan->idtp, $data_idtp_penghapusan)) continue;

                $penghapusan_pokok = 0;
                $penghapusan_jasa = 0;
                foreach ($trx_penghapusan->tr_idtp as $idtp) {
                    if (in_array($idtp->rekening_kredit, $kode_pokok)) {
                        $penghapusan_pokok += floatval($idtp->jumlah);
                    }

                    if (in_array($idtp->rekening_kredit, $kode_jasa)) {
                        $penghapusan_jasa += floatval($idtp->jumlah);
                    }
                }

                $data_idtp_penghapusan[] = $trx_penghapusan->idtp;
                $data_penghapusan[$nomor] = [
                    'tgl_transaksi' => $trx_penghapusan->tgl_transaksi,
                    'penghapusan_pokok' => $penghapusan_pokok,
                    'penghapusan_jasa' => $penghapusan_jasa,
                    'id_pinjaman_anggota' => $trx_penghapusan->id_pinj_i,
                    'alokasi_pokok_pinjaman' => 0,
                    'alokasi_jasa_pinjaman' => 0
                ];

                $nomor++;
            }

            $target_pokok = 0;
            $target_jasa = 0;

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

            $alokasi_jasa = ($alokasi * ($pinkel->pros_jasa / 100));
            for ($i = 1; $i <= $jangka; $i++) {
                $angsuran_ke = $i;
                $jatuh_tempo = $this->jatuh_tempo($i, $sistem_angsuran_pokok, $tgl_cair);

                $sisa_pokok = $i % $angsuran_pokok['sistem'];
                $sisa_jasa = $i % $angsuran_jasa['sistem'];
                $ke_pokok = $i / $angsuran_pokok['sistem'];
                $ke_jasa = $i / $angsuran_jasa['sistem'];

                if (count($data_penghapusan) > 0) {
                    foreach ($data_penghapusan as $key => $value) {
                        if (strtotime($jatuh_tempo) < strtotime($value['tgl_transaksi'])) {
                            $pokok = $rencana_pokok[$i] ?: 0;
                            $jasa = $rencana_jasa[$i] ?: 0;
                        } else {
                            if ($value['alokasi_pokok_pinjaman'] == 0 && $value['alokasi_jasa_pinjaman'] == 0) {
                                $alokasi_jasa -= $alokasi_jasa_anggota[$value['id_pinjaman_anggota']];
                                $data_rencana[strtotime($value['tgl_transaksi'])] = [
                                    'loan_id' => $pinkel->id,
                                    'angsuran_ke' => $angsuran_ke,
                                    'jatuh_tempo' => $value['tgl_transaksi'],
                                    'wajib_pokok' => $value['penghapusan_pokok'],
                                    'wajib_jasa' => $value['penghapusan_jasa'],
                                    'target_pokok' => $target_pokok + $value['penghapusan_pokok'],
                                    'target_jasa' => $target_jasa + $value['penghapusan_jasa'],
                                    'lu' => date('Y-m-d H:i:s'),
                                    'id_user' => 1
                                ];
                                $rencana[] = $data_rencana[strtotime($value['tgl_transaksi'])];

                                $alokasi_pokok_pinjaman = $alokasi - ($target_pokok + $value['penghapusan_pokok']);
                                $alokasi_jasa_pinjaman = $alokasi_jasa - ($target_jasa + $value['penghapusan_jasa']);

                                $_tempo_pokok = floor(($jangka - ($i - $key)) / $sistem_pokok);
                                $_tempo_jasa = floor(($jangka - ($i - $key)) / $sistem_jasa);

                                $target_pokok += $value['penghapusan_pokok'];
                                $target_jasa += $value['penghapusan_jasa'];

                                $data_penghapusan[$key]['alokasi_pokok_pinjaman'] = $alokasi_pokok_pinjaman;
                                $data_penghapusan[$key]['alokasi_jasa_pinjaman'] = $alokasi_jasa_pinjaman;
                            }

                            $pokok = Keuangan::bulatkan($alokasi_pokok_pinjaman / $_tempo_pokok);
                            $jasa = Keuangan::bulatkan($alokasi_jasa_pinjaman / $_tempo_jasa);
                            $angsuran_ke = $i + $key;
                        }
                    }

                    if ($sisa_pokok == 0 and $ke_pokok != $angsuran_pokok['tempo']) {
                        $pokok = $pokok;
                    } elseif ($sisa_pokok == 0 and $ke_pokok == $angsuran_pokok['tempo']) {
                        $pokok = $alokasi - $target_pokok;
                    } else {
                        $pokok = 0;
                    }

                    if ($sisa_jasa == 0 and $ke_jasa != $angsuran_jasa['tempo']) {
                        $jasa = $jasa;
                    } elseif ($sisa_jasa == 0 and $ke_jasa == $angsuran_jasa['tempo']) {
                        $jasa = $alokasi_jasa - $target_jasa;
                    } else {
                        $jasa = 0;
                    }
                } else {
                    $pokok = $rencana_pokok[$i] ?: 0;
                    $jasa = $rencana_jasa[$i] ?: 0;
                }

                $target_jasa += $jasa;
                $target_pokok += $pokok;
                if ($i == 1) {
                    $target_pokok = $pokok;
                    $target_jasa = $jasa;
                }

                $data_rencana[strtotime($jatuh_tempo)] = [
                    'loan_id' => $pinkel->id,
                    'angsuran_ke' => $angsuran_ke,
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
            // $alokasi_jasa = $target_jasa;
            $alokasi_jasa = ($alokasi * ($pinkel->pros_jasa / 100));
            $sum_pokok = 0;
            $sum_jasa = 0;

            $data_idtp = [];
            ksort($data_rencana);
            foreach ($pinkel->trx as $trx) {
                if (in_array($trx->idtp, $data_idtp)) continue;

                $realisasi_pokok = 0;
                $realisasi_jasa = 0;
                foreach ($trx->tr_idtp as $idtp) {
                    if (in_array($idtp->rekening_kredit, $kode_pokok)) {
                        $realisasi_pokok += floatval($idtp->jumlah);
                    }

                    if (in_array($idtp->rekening_kredit, $kode_jasa)) {
                        $realisasi_jasa += floatval($idtp->jumlah);
                    }
                }

                $sum_pokok += $realisasi_pokok;
                $sum_jasa += $realisasi_jasa;

                $alokasi_pokok -= $realisasi_pokok;
                $alokasi_jasa -= $realisasi_jasa;

                $target_pokok = 0;
                $target_jasa = 0;
                $tgl_transaksi = $trx->tgl_transaksi;
                foreach ($data_rencana as $key => $value) {
                    if ($key <= strtotime($tgl_transaksi)) {
                        $target_pokok = $value['target_pokok'];
                        $target_jasa = $value['target_jasa'];
                    }
                }

                $tunggakan_pokok = $target_pokok - $sum_pokok;
                $tunggakan_jasa = $target_jasa - $sum_jasa;

                if ($tunggakan_pokok < 0) {
                    $tunggakan_pokok = 0;
                }

                if ($tunggakan_jasa < 0) {
                    $tunggakan_jasa = 0;
                }

                if (!($realisasi_pokok == '0' && $realisasi_jasa == '0')) {
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

        RencanaAngsuran::whereIn('loan_id', $data_pinjaman)->delete();
        RealAngsuran::whereIn('loan_id', $data_pinjaman)->delete();
        RealAngsuran::whereIn('id', $data_id_real)->delete();

        RencanaAngsuran::insert($rencana);
        RealAngsuran::insert($real);

        array_push($data_pinjaman, $data_pinjaman_H);
        $data = $request->all();
        $offset = $offset + $limit;
        return view('generate.generate')->with(compact('data_pinjaman', 'data', 'offset', 'limit'));
    }

    private function where($kondisi)
    {
        $where = [];
        $whereIn = [];
        $whereNotIn = [];
        foreach ($kondisi as $key => $val) {
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

        return [
            'where' => $where,
            'whereIn' => $whereIn,
            'whereNotIn' => $whereNotIn
        ];
    }

    private function sistem($sistem_angsuran, $jangka_pinjaman, $sistem)
    {
        if ($sistem_angsuran == 11) {
            $tempo = ($jangka_pinjaman) - 24 / $sistem;
            $mulai_angsuran = $jangka_pinjaman - $tempo;
        } else if ($sistem_angsuran == 14) {
            $tempo = ($jangka_pinjaman) - 3 / $sistem;
            $mulai_angsuran = $jangka_pinjaman - $tempo;
        } else if ($sistem_angsuran == 15) {
            $tempo = ($jangka_pinjaman) - 2 / $sistem;
            $mulai_angsuran = $jangka_pinjaman - $tempo;
        } else if ($sistem_angsuran == 25) {
            $tempo = ($jangka_pinjaman) - 1 / $sistem;
            $mulai_angsuran = $jangka_pinjaman - $tempo;
        } else if ($sistem_angsuran == 20) {
            $tempo = ($jangka_pinjaman) - 12 / $sistem;
            $mulai_angsuran = $jangka_pinjaman - $tempo;
        } else {
            $tempo = floor($jangka_pinjaman / $sistem);
            $mulai_angsuran = 0;
        }

        return [
            'tempo' => $tempo,
            'sistem' => $sistem,
            'mulai_angsuran' => $mulai_angsuran,
        ];
    }

    private function detail_pinjaman($pinjaman, $desa, $batas_angsuran)
    {
        if ($pinjaman->status == 'P') {
            $alokasi = $pinjaman->proposal;
            $tgl_cair = $pinjaman->tgl_proposal;
        } elseif ($pinjaman->status == 'V') {
            $alokasi = $pinjaman->verifikasi;
            $tgl_cair = $pinjaman->tgl_verifikasi;
        } elseif ($pinjaman->status == 'W') {
            $alokasi = $pinjaman->alokasi;
            $tgl_cair = $pinjaman->tgl_cair;

            if ($tgl_cair == "0000-00-00") {
                $tgl_cair = $pinjaman->tgl_tunggu;
            }
        } else {
            $alokasi = $pinjaman->alokasi;
            $tgl_cair = $pinjaman->tgl_cair;

            if ($tgl_cair == "0000-00-00") {
                $tgl_cair = $pinjaman->tgl_tunggu;
            }
        }

        $tanggal_cair = date('d', strtotime($tgl_cair));
        if ($desa->jadwal_angsuran_desa > 0) {
            $angsuran_desa = $desa->jadwal_angsuran_desa;
            if ($angsuran_desa > 0) {
                $tgl_pinjaman = date('Y-m', strtotime($tgl_cair));
                $tgl_cair = $tgl_pinjaman . '-' . $angsuran_desa;
            }
        }

        if ($batas_angsuran > 0) {
            $batas_tgl_angsuran = $batas_angsuran;
            if ($tanggal_cair >= $batas_tgl_angsuran) {
                $tgl_cair = date('Y-m-d', strtotime('+1 month', strtotime($tgl_cair)));
            }
        }

        return [
            'alokasi' => $alokasi,
            'tgl_cair' => $tgl_cair
        ];
    }

    private function rencana_angsuran($pinkel, $angsuran_pokok, $angsuran_jasa, $alokasi, $pembulatan = '500')
    {
        $rencana_angsuran['pokok'] = [];
        $rencana_angsuran['jasa'] = [];
        $alokasi_pokok = $alokasi;
        for ($j = 1; $j <= $pinkel->jangka; $j++) {
            $sisa_pokok = $j % $angsuran_pokok['sistem'];
            $sisa_jasa = $j % $angsuran_jasa['sistem'];
            $ke_pokok = $j / $angsuran_pokok['sistem'];
            $ke_jasa = $j / $angsuran_jasa['sistem'];

            $alokasi_jasa = $alokasi * ($pinkel->pros_jasa / 100);

            $wajib_angsuran_pokok = $alokasi_pokok / $angsuran_pokok['tempo'];
            $wajib_angsuran_pokok = Keuangan::pembulatan(intval($wajib_angsuran_pokok), (string) $pembulatan);
            $sum_angsuran = $wajib_angsuran_pokok * ($angsuran_pokok['tempo'] - 1);

            if ($sisa_pokok == 0 and $ke_pokok != $angsuran_pokok['tempo'] && $ke_pokok > $angsuran_pokok['mulai_angsuran']) {
                $pokok = $wajib_angsuran_pokok;
            } elseif ($sisa_pokok == 0 and $ke_pokok == $angsuran_pokok['tempo']) {
                $pokok = $alokasi_pokok - $sum_angsuran;
            } else {
                $pokok = 0;
            }
            $rencana_angsuran['pokok'][$j] = $pokok;

            $wajib_angsuran_jasa = $alokasi_jasa / $angsuran_jasa['tempo'];
            $wajib_angsuran_jasa = Keuangan::pembulatan(intval($wajib_angsuran_jasa), (string) $pembulatan);
            $sum_angsuran = $wajib_angsuran_jasa * ($angsuran_jasa['tempo'] - 1);

            if ($sisa_jasa == 0 and $ke_jasa != $angsuran_jasa['tempo'] && $ke_jasa > $angsuran_jasa['mulai_angsuran']) {
                $jasa = $wajib_angsuran_jasa;
            } elseif ($sisa_jasa == 0 and $ke_jasa == $angsuran_jasa['tempo']) {
                $jasa = $alokasi_jasa - $sum_angsuran;
            } else {
                $jasa = 0;
            }

            if ($pinkel->jenis_jasa == '2') {
                $jasa = $wajib_angsuran_jasa;
                $alokasi -= $rencana_angsuran['pokok'][$j];
            }

            $rencana_angsuran['jasa'][$j] = $jasa;
        }

        return $rencana_angsuran;
    }

    private function jatuh_tempo($index, $sistem_angsuran_pokok, $tanggal)
    {
        if ($sistem_angsuran_pokok == 12) {
            $tambah = $index * 7;
            $penambahan = "+$tambah days";
        } else {
            $penambahan = "+$index month";
        }

        $tanggal_cair = date('Y-m', strtotime($tanggal));
        $jatuh_tempo = date('Y-m', strtotime($penambahan, strtotime($tanggal_cair)));

        if (date('d', strtotime($tanggal)) > date('t', strtotime($jatuh_tempo))) {
            $jatuh_tempo = date('Y-m-t', strtotime($jatuh_tempo));
        } else {
            $jatuh_tempo = date('Y-m', strtotime($jatuh_tempo)) . '-' . date('d', strtotime($tanggal));
        }

        return $jatuh_tempo;
    }

    // GENERATE V1 || INACTIVE
    public function _generate(Request $request, $offset = 0)
    {
        $real = [];
        $rencana = [];
        $is_pinkel = ($request->pinjaman == 'kelompok') ? true : false;
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();

        $where = [];
        $whereIn = [];
        $whereNotIn = [];
        foreach ($request->all() as $key => $val) {
            if ($key == '_token' || $key == 'pinjaman' || $key == 'generate_version') {
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
        $pinjaman = PinjamanKelompok::whereNotIn('status', ['H'])->where(function ($query) use ($where, $whereIn, $whereNotIn) {
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

                if (!($realisasi_pokok == '0' && $realisasi_jasa == '0')) {
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

        $data_pinjaman = $data_id_pinj;
        return view('generate.generate')->with(compact('data_pinjaman', 'data', 'offset', 'limit'));
    }
}
