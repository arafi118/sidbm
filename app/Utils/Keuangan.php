<?php

namespace App\Utils;

use App\Models\Kecamatan;
use App\Models\PinjamanKelompok;
use App\Models\Rekening;
use App\Models\Transaksi;
use DB;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Session;

class Keuangan
{
    public static function bulatkan($angka)
    {
        $angka = round($angka);

        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();
        $pembulatan    = number_format($kec->pembulatan, 0, '', '');
        $ratusan = substr($angka, -3);
        $nilai_tengah = $pembulatan / 2;
        if ($ratusan < $nilai_tengah) {
            $akhir = $angka - $ratusan;
        } else {
            $akhir = $angka + ($pembulatan - $ratusan);
        }

        return $akhir;
    }

    public static function startWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    public function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->penyebut($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = $this->penyebut($nilai / 10) . " puluh" . $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->penyebut($nilai / 100) . " ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->penyebut($nilai / 1000) . " ribu" . $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->penyebut($nilai / 1000000) . " juta" . $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->penyebut($nilai / 1000000000) . " milyar" . $this->penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->penyebut($nilai / 1000000000000) . " trilyun" . $this->penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    public function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim($this->penyebut($nilai));
        } else {
            $hasil = trim($this->penyebut($nilai));
        }
        return ucwords($hasil);
    }

    public function Saldo($tgl_kondisi, $kode_akun)
    {
        $thn_kondisi = explode('-', $tgl_kondisi)[0];
        $awal_tahun = $thn_kondisi . '-01-01';
        $thn_lalu = $thn_kondisi - 1;

        $rekening = Rekening::select(
            DB::raw("SUM(tb$thn_lalu) as debit"),
            DB::raw("SUM(tbk$thn_lalu) as kredit"),
            DB::raw('(SELECT sum(jumlah) as dbt FROM 
            transaksi_' . Session::get('lokasi') . ' as td WHERE 
            td.rekening_debit=rekening_' . Session::get('lokasi') . '.kode_akun AND 
            td.tgl_transaksi BETWEEN "' . $awal_tahun . '" AND "' . $tgl_kondisi . '"
            ) as saldo_debit'),
            DB::raw('(SELECT sum(jumlah) as dbt FROM 
            transaksi_' . Session::get('lokasi') . ' as td WHERE 
            td.rekening_kredit=rekening_' . Session::get('lokasi') . '.kode_akun AND 
            td.tgl_transaksi BETWEEN "' . $awal_tahun . '" AND "' . $tgl_kondisi . '"
            ) as saldo_kredit'),
            'kode_akun'
        )
            ->groupBy(DB::raw("kode_akun", "jenis_mutasi"))->where('kode_akun', $kode_akun)->first();

        $lev1 = explode('.', $kode_akun)[0];
        $jenis_mutasi = 'kredit';
        if ($lev1 == '1' || $lev1 == '5') $jenis_mutasi = 'debet';

        if (strtolower($jenis_mutasi) == 'debet') {
            $saldo = ($rekening->debit - $rekening->kredit) + $rekening->saldo_debit - $rekening->saldo_kredit;
        } elseif (strtolower($jenis_mutasi) == 'kredit') {
            $saldo = ($rekening->kredit - $rekening->debit) + $rekening->saldo_kredit - $rekening->saldo_debit;
        }

        return $saldo;
    }

    public function saldoKas($tgl_kondisi)
    {
        $saldo = 0;
        $rekening = Rekening::where('kode_akun', 'like', '1.1.01%')->orwhere('kode_akun', 'like', '1.1.02%')->get();
        foreach ($rekening as $rek) {
            $saldo += $this->Saldo($tgl_kondisi, $rek->kode_akun);
        }

        return $saldo;
    }

    public function saldoAwal($tgl_kondisi, $kode_akun)
    {
        $thn_kondisi = explode('-', $tgl_kondisi)[0];
        $thn_lalu = $thn_kondisi - 1;

        $rek = Rekening::select(
            DB::raw("SUM(tb$thn_lalu) as debit"),
            DB::raw("SUM(tbk$thn_lalu) as kredit")
        )->where('kode_akun', $kode_akun)->first();

        return [
            'debit' => $rek->debit,
            'kredit' => $rek->kredit
        ];
    }

    // Sum Saldo Debit
    public function saldoD($tgl_kondisi, $kode_akun)
    {
        $thn_kondisi = explode('-', $tgl_kondisi)[0];
        $awal_tahun = $thn_kondisi . '-01-01';

        $trx = Transaksi::where('rekening_debit', $kode_akun)->whereBetween('tgl_transaksi', [$awal_tahun, $tgl_kondisi])->sum('jumlah');
        return $trx;
    }

    // Sum Saldo Kredit
    public function saldoK($tgl_kondisi, $kode_akun)
    {
        $thn_kondisi = explode('-', $tgl_kondisi)[0];
        $awal_tahun = $thn_kondisi . '-01-01';

        $trx = Transaksi::where('rekening_kredit', $kode_akun)->whereBetween('tgl_transaksi', [$awal_tahun, $tgl_kondisi])->sum('jumlah');
        return $trx;
    }

    public function pendapatan($tgl_kondisi)
    {
        $saldo = 0;
        $rekening = Rekening::where('lev1', '4')->get();
        foreach ($rekening as $rek) {
            $saldo += $this->Saldo($tgl_kondisi, $rek->kode_akun);
        }

        return $saldo;
    }

    public function biaya($tgl_kondisi)
    {
        $saldo = 0;
        $rekening = Rekening::where('lev1', '5')->get();
        foreach ($rekening as $rek) {
            $saldo += $this->Saldo($tgl_kondisi, $rek->kode_akun);
        }

        return $saldo;
    }

    public function surplus($tgl_kondisi)
    {
        $pendapatan = $this->pendapatan($tgl_kondisi);
        $biaya = $this->biaya($tgl_kondisi);

        return ($pendapatan - $biaya);
    }

    public function laba_rugi($tgl_kondisi)
    {
        $array_tgl = explode('-', $tgl_kondisi);
        $tahun = $array_tgl[0];
        $bulan = $array_tgl[1];
        $hari = $array_tgl[2];

        $surplus = Rekening::where([
            ['lev1', '>=', '4']
        ])->with([
            'saldo' => function ($query) use ($tahun, $bulan) {
                $query->where([
                    ['tahun', $tahun],
                    ['bulan', $bulan]
                ]);
            }
        ])->orderBy('kode_akun', 'ASC')->get();

        $pendapatan = 0;
        $biaya = 0;
        foreach ($surplus as $sp) {
            if ($sp->lev1 == '5') {
                $biaya += ($sp->saldo->debit - $sp->saldo->kredit);
            } else {
                $pendapatan += ($sp->saldo->kredit - $sp->saldo->debit);
            }
        }

        return $pendapatan - $biaya;
    }

    public function tingkat_kesehatan($tgl_kondisi, $data = [])
    {
        $tgl = explode('-', $tgl_kondisi);
        $data['tahun'] = $tgl[0];
        $data['bulan'] = $tgl[1];
        $data['tanggal'] = $tgl[2];
        $data['lokasi'] = Session::get('lokasi');
        $data['tgl_kondisi'] = $tgl_kondisi;

        $sum_nunggak_pokok = 0;
        $sum_nunggak_jasa = 0;
        $sum_saldo_pokok = 0;
        $sum_saldo_jasa = 0;
        $sum_kolek1 = 0;
        $sum_kolek2 = 0;
        $sum_kolek3 = 0;

        $pinjaman_kelompok = PinjamanKelompok::where('sistem_angsuran', '!=', '12')
            ->where(function ($query) use ($data) {
                $query->where([
                    ['status', 'A'],
                    ['tgl_cair', '<=', $data['tgl_kondisi']]
                ])->orwhere([
                    ['status', 'L'],
                    ['tgl_cair', '<=', $data['tgl_kondisi']],
                    ['tgl_lunas', '>=', "$data[tahun]-01-01"]
                ])->orwhere([
                    ['status', 'L'],
                    ['tgl_lunas', '<=', $data['tgl_kondisi']],
                    ['tgl_lunas', '>=', "$data[tahun]-01-01"]
                ])->orwhere([
                    ['status', 'R'],
                    ['tgl_cair', '<=', $data['tgl_kondisi']],
                    ['tgl_lunas', '>=', "$data[tahun]-01-01"]
                ])->orwhere([
                    ['status', 'R'],
                    ['tgl_lunas', '<=', $data['tgl_kondisi']],
                    ['tgl_lunas', '>=', "$data[tahun]-01-01"]
                ])->orwhere([
                    ['status', 'H'],
                    ['tgl_cair', '<=', $data['tgl_kondisi']],
                    ['tgl_lunas', '>=', "$data[tahun]-01-01"]
                ])->orwhere([
                    ['status', 'H'],
                    ['tgl_lunas', '<=', $data['tgl_kondisi']],
                    ['tgl_lunas', '>=', "$data[tahun]-01-01"]
                ]);
            })->with([
                'saldo' => function ($query) use ($data) {
                    $query->where('tgl_transaksi', '<=', $data['tgl_kondisi']);
                },
                'target' => function ($query) use ($data) {
                    $query->where('jatuh_tempo', '<=', $data['tgl_kondisi']);
                }
            ])->get();

        foreach ($pinjaman_kelompok as $pinkel) {
            $real_pokok = 0;
            $real_jasa = 0;
            $sum_pokok = 0;
            $sum_jasa = 0;
            $saldo_pokok = $pinkel->alokasi;
            $saldo_jasa = 0;
            if ($pinkel->pros_jasa > 0) {
                $saldo_jasa = $pinkel->alokasi / $pinkel->pros_jasa;
            }
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
            $wajib_pokok = 0;
            $wajib_jasa = 0;
            $angsuran_ke = 0;
            if ($pinkel->target) {
                $target_pokok = $pinkel->target->target_pokok;
                $target_jasa = $pinkel->target->target_jasa;
                $wajib_pokok = $pinkel->target->wajib_pokok;
                $wajib_jasa = $pinkel->target->wajib_jasa;
                $angsuran_ke = $pinkel->target->angsuran_ke;
            }

            $tunggakan_pokok = $target_pokok - $sum_pokok;
            if ($tunggakan_pokok < 0) {
                $tunggakan_pokok = 0;
            }
            $tunggakan_jasa = $target_jasa - $sum_jasa;
            if ($tunggakan_jasa < 0) {
                $tunggakan_jasa = 0;
            }

            if ($pinkel->tgl_lunas <= $data['tgl_kondisi'] && $pinkel->status == 'L') {
                $tunggakan_pokok = 0;
                $tunggakan_jasa = 0;
                $saldo_pokok = 0;
                $saldo_jasa = 0;
            } elseif ($pinkel->tgl_lunas <= $data['tgl_kondisi'] && $pinkel->status == 'R') {
                $tunggakan_pokok = 0;
                $tunggakan_jasa = 0;
                $saldo_pokok = 0;
                $saldo_jasa = 0;
            } elseif ($pinkel->tgl_lunas <= $data['tgl_kondisi'] && $pinkel->status == 'H') {
                $tunggakan_pokok = 0;
                $tunggakan_jasa = 0;
                $saldo_pokok = 0;
                $saldo_jasa = 0;
            }

            $tgl_cair = explode('-', $pinkel->tgl_cair);
            $th_cair = $tgl_cair[0];
            $bl_cair = $tgl_cair[1];
            $tg_cair = $tgl_cair[2];

            $selisih_tahun = ($data['tahun'] - $th_cair) * 12;
            $selisih_bulan = $data['bulan'] - $bl_cair;

            $selisih = $selisih_bulan + $selisih_tahun;

            $_kolek = 0;
            if ($wajib_pokok != '0') {
                $_kolek = floor($tunggakan_pokok / $wajib_pokok);
            }
            $kolek = $_kolek + ($selisih - $angsuran_ke);
            if ($kolek <= 3) {
                $kolek1 = $saldo_pokok;
                $kolek2 = 0;
                $kolek3 = 0;
            } elseif ($kolek <= 5) {
                $kolek1 = 0;
                $kolek2 = $saldo_pokok;
                $kolek3 = 0;
            } else {
                $kolek1 = 0;
                $kolek2 = 0;
                $kolek3 = $saldo_pokok;
            }

            $sum_nunggak_pokok += $tunggakan_pokok;
            $sum_nunggak_jasa += $tunggakan_jasa;
            $sum_saldo_pokok += $saldo_pokok;
            $sum_saldo_jasa += $saldo_jasa;
            $sum_kolek1 += $kolek1;
            $sum_kolek2 += $kolek2;
            $sum_kolek3 += $kolek3;
        }

        $kolek_1 = $sum_kolek1 * 0 / 100;
        $kolek_2 = $sum_kolek2 * 50 / 100;
        $kolek_3 = $sum_kolek3 * 100 / 100;

        return [
            'nunggak_pokok' => $sum_nunggak_pokok,
            'nunggak_jasa' => $sum_nunggak_jasa,
            'saldo_pokok' => $sum_saldo_pokok,
            'saldo_jasa' => $sum_saldo_jasa,
            'sum_kolek' => ($kolek_1 + $kolek_2 + $kolek_3)
        ];
    }

    public function aset($tgl_kondisi)
    {
        $aset_produktif = 0;
        $aset_ekonomi = 0;
        $cadangan_piutang = 0;
        $rekening = Rekening::where('lev1', '1')->where('lev2', '1')->get();
        foreach ($rekening as $rek) {
            $saldo = $this->Saldo($tgl_kondisi, $rek->kode_akun);
            $aset_produktif += $saldo;
            if ($rek->lev3 < '6') {
                $aset_ekonomi += $saldo;
            }
            if ($rek->lev3 == '4') {
                $cadangan_piutang += $saldo;
            }
        }

        return [
            'aset_ekonomi' => $aset_ekonomi,
            'aset_produktif' => $aset_produktif,
            'cadangan_piutang' => $cadangan_piutang * -1
        ];
    }

    public function modal_awal($tgl_kondisi)
    {
        $modalawal = ($this->Saldo($tgl_kondisi, "3.1.01.01")) + ($this->Saldo($tgl_kondisi, "3.1.01.02")) + ($this->Saldo($tgl_kondisi, "3.1.01.03"));
        return $modalawal;
    }

    public function romawi(int $angka)
    {
        if ($angka < 1) {
            return '';
        }

        $angka = intval($angka);
        $result = '';

        $lookup = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );

        foreach ($lookup as $roman => $value) {
            $matches = intval($angka / $value);
            $result .= str_repeat($roman, $matches);
            $angka = $angka % $value;
        }

        return $result;
    }

    public function arus_kas($kode, $tgl_kondisi, $jenis = 'Bulanan')
    {
        $tanggal = explode('-', $tgl_kondisi);
        $thn = $tanggal[0];
        $bln = $tanggal[1];
        $tgl = $tanggal[2];

        if ($jenis == 'Tahunan') {
            $tgl_awal = $thn . '-01-01';
        } elseif ($jenis == 'Bulanan') {
            $tgl_awal = $thn . '-' . $bln . '-01';
        } else {
            $tgl_awal = $tgl_kondisi;
        }

        $jumlah = 0;
        $kode_akun = explode('#', $kode);
        foreach ($kode_akun as $val => $ka) {
            $kode_rek = explode('~', $ka);
            $debit = $kode_rek[0];
            $kredit = end($kode_rek);

            $trx = Transaksi::where([
                ['rekening_debit', 'like', "$debit"],
                ['rekening_kredit', 'like', "$kredit"]
            ])->where([
                ['tgl_transaksi', '>=', $tgl_awal],
                ['tgl_transaksi', '<=', $tgl_kondisi]
            ])->sum('jumlah');

            $jumlah += $trx;
        }

        return $jumlah;
    }
}
