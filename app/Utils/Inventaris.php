<?php

namespace App\Utils;

use App\Models\Inventaris as ModelsInventaris;
use App\Models\Rekening;
use DB;
use Session;

class Inventaris
{
    public static function nilaiBuku($tgl, $inv)
    {
        $tgl_beli = $inv->tgl_beli;

        if (Session::get('lokasi') == '273') {
            $tgl_beli = date('Y-m-d', strtotime('+1 month', strtotime($tgl_beli)));
        }

        $unit = $inv->unit;
        $harga_satuan = $inv->harsat * $unit;
        $umur = $inv->umur_ekonomis;

        if ($inv->kategori == 1 && $inv->jenis == 1) {
            return $harga_satuan;
        }

        $penyusutan = $inv->harsat <= 0 ? 0 : round($harga_satuan / $inv->umur_ekonomis, 2);
        $ak_umur = self::bulan($inv->tgl_beli, $tgl);
        $ak_susut = $penyusutan * $ak_umur;
        $nilai = $harga_satuan - $ak_susut;

        if ($nilai < 0) {
            return 1;
        }

        return $nilai;
    }

    public static function bulan($start, $end, $periode = 'bulan')
    {
        if (Session::get('lokasi') == '273') {
            $start = date('Y-m-d', strtotime('+1 month', strtotime($start)));
        }

        $batasan = date('t');
        $thn_awal    = substr($start, 0, 4);
        $bln_awal    = substr($start, 5, 2);   //12
        $tgl_awal    = 01;   //29

        if ($tgl_awal <= $batasan) {
            $tgl_awal = 01;
            if ($bln_awal == 1) {
                $thn_awal -= 1;
                $bln_awal = 12;
            } else {
                $bln_awal -= 1;
            }
        } else {
            $bln_awal = $bln_awal;
            $tgl_awal = $tgl_awal;
        }

        $start = "$thn_awal-$bln_awal-$tgl_awal";
        $day = 0;
        $month = 0;
        $month_array = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $datestart = strtotime($start);
        $dateend = strtotime($end);
        $month_start = strftime("%m", $datestart);
        $current_year = strftime("%y", $datestart);
        $diff = $dateend - $datestart;
        $date = $diff / (60 * 60 * 24);
        $day = $date;
        $awal = 1;

        while ($date > 0) {
            if ($awal) {
                $loop = $month_start - 1;
                $awal = 0;
            } else {
                $loop = 0;
            }
            for ($i = $loop; $i < 12; $i++) {
                if ($current_year % 4 == 0 && $i == 1)
                    $day_of_month = 29;
                else
                    $day_of_month = $month_array[$i];

                $date -= $day_of_month;

                if ($date <= 0) {
                    if ($date == 0)
                        $month++;
                    break;
                }
                $month++;
            }

            $current_year++;
        }

        switch ($periode) {
            case "hari":
                return $day;
                break;
            case "bulan":
                return $month;
                break;
            case "tahun":
                return (float) ($month / 12);
                break;
        }
    }

    public static function penyusutan($tgl_kondisi, $kategori)
    {
        $ymd = explode('-', $tgl_kondisi);
        $tahun = $ymd[0];
        $bulan = $ymd[1];
        $hari = $ymd[2];
        $th_lalu = $tahun - 1;

        $t_unit = 0;
        $t_harga = 0;
        $t_penyusutan = 0;
        $t_akum_susut = 0;
        $t_nilai_buku = 0;

        $j_unit = 0;
        $j_harga = 0;
        $j_penyusutan = 0;
        $j_akum_susut = 0;
        $j_nilai_buku = 0;

        $inventaris = ModelsInventaris::where([
            ['jenis', '1'],
            ['kategori', $kategori],
            ['status', '!=', '0'],
            ['lokasi', Session::get('lokasi')],
            ['tgl_beli', '<=', $tgl_kondisi]
        ])->orderBy('tgl_beli', 'ASC')->get();

        foreach ($inventaris as $inv) {
            if ($kategori == '1') {
                $t_unit += $inv->unit;
                $t_harga += $inv->harsat * $inv->unit;
                $t_nilai_buku += $inv->harsat * $inv->unit;

                $nilai_buku = $inv->harsat * $inv->unit;
                if ($inv->status == 'Dijual' || $inv->status == 'Hapus') {
                    $nilai_buku = '0';
                }

                if ($inv->status == 'Dijual' || $inv->status == 'Hilang' || $inv->status == 'Dihapus') {
                    $j_unit += $inv->unit;
                    $j_harga += $inv->harsat * $inv->unit;
                    $j_nilai_buku += $inv->harsat * $inv->unit;
                }
            } else {
                $satuan_susut = $inv->harsat <= 0 ? 0 : round(($inv->harsat / $inv->umur_ekonomis) * $inv->unit, 2);
                $pakai_lalu = Inventaris::bulan($inv->tgl_beli, $tahun - 1 . '-12-31');
                $nilai_buku = Inventaris::nilaiBuku($tgl_kondisi, $inv);

                if (!($inv->status == 'Baik') && $tgl_kondisi >= $inv->tgl_validasi) {
                    $umur = Inventaris::bulan($inv->tgl_beli, $inv->tgl_validasi);
                } else {
                    $umur = Inventaris::bulan($inv->tgl_beli, $tgl_kondisi);
                }

                $_satuan_susut = $satuan_susut;
                if ($umur >= $inv->umur_ekonomis) {
                    $harga = $inv->harsat * $inv->unit;
                    $_susut = $satuan_susut * ($inv->umur_ekonomis - 1);
                    $satuan_susut = $harga - $_susut - 1;
                }

                $susut = $satuan_susut * $umur;
                if ($umur >= $inv->umur_ekonomis && $inv->harsat * $inv->unit > 0) {
                    $akum_umur = $inv->umur_ekonomis;
                    $_akum_susut = $inv->harsat * $inv->unit;
                    $akum_susut = $_akum_susut - 1;
                    $nilai_buku = 1;
                } else {
                    $akum_umur = $umur;
                    $akum_susut = $susut;

                    if ($nilai_buku < 0) {
                        $nilai_buku = 1;
                    }
                }

                $umur_pakai = $akum_umur - $pakai_lalu;
                $penyusutan = $satuan_susut * $umur_pakai;

                if (($inv->status == 'Hilang' and $tgl_kondisi >= $inv->tgl_validasi) || ($inv->status == 'Dijual' && $tgl_kondisi >= $inv->tgl_validasi) || ($inv->status == 'Hapus' && $tgl_kondisi >= $inv->tgl_validasi)) {
                    $akum_susut = $inv->harsat * $inv->unit;
                    $nilai_buku = 0;
                    $penyusutan = 0;
                    $umur_pakai = 0;
                }

                if ($inv->status == 'Rusak' and $tgl_kondisi >= $inv->tgl_validasi) {
                    $akum_susut = $inv->harsat * $inv->unit - 1;
                    $nilai_buku = 1;
                    $penyusutan = 0;
                    $umur_pakai = 0;
                }

                if ($umur_pakai >= 0 && $inv->harsat * $inv->unit > 0) {
                    $penyusutan = $penyusutan;
                } else {
                    $umur_pakai = 0;
                    $penyusutan = 0;
                }

                if ($akum_umur == $inv->umur_ekonomis && $umur_pakai > '0') {
                    $penyusutan = $_satuan_susut * ($umur_pakai - 1) + $satuan_susut;
                }

                $t_unit += $inv->unit;
                $t_harga += $inv->harsat * $inv->unit;
                $t_penyusutan += $penyusutan;
                $t_akum_susut += $akum_susut;
                $t_nilai_buku += $nilai_buku;

                if ($inv->status == 'Dijual' || $inv->status == 'Hilang' || $inv->status == 'Dihapus') {
                    $j_unit += $inv->unit;
                    $j_harga += $inv->harsat * $inv->unit;
                    $j_penyusutan += $penyusutan;
                    $j_akum_susut += $akum_susut;
                    $j_nilai_buku += $nilai_buku;
                }
            }
        }

        return $t_akum_susut;
    }

    public static function saldoSusut($tanggal, $kode_akun)
    {
        $tanggal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal)));
        $ymd = explode('-', $tanggal);
        $y = $ymd[0];
        $m = $ymd[1];
        $d = $ymd[2];
        $th_lalu = $y - 1;
        $awal_tahun = $y . '-01-01';
        $akhir_hari = $y . '-' . $m . '-' . date('t', strtotime("$y-$m-01"));

        $rekening = Rekening::where('kode_akun', $kode_akun)->with([
            'kom_saldo' => function ($query) use ($y, $m) {
                $query->where('tahun', $y)->where(function ($query) use ($m) {
                    $query->where('bulan', $m)->orWhere('bulan', '0');
                });
            }
        ])->first();

        $saldo = 0;
        $awal_tahun = 0;
        foreach ($rekening->kom_saldo as $kom_saldo) {
            if ($kom_saldo->bulan == '0') {
                $awal_tahun += $kom_saldo->kredit;
            } else {
                $saldo += $kom_saldo->kredit;
            }
        }
        $lev1 = explode('.', $kode_akun)[0];
        $jenis_mutasi = 'kredit';

        $saldo = $awal_tahun + $saldo;

        return $saldo;
    }
}
