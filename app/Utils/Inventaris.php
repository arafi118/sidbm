<?php

namespace App\Utils;

use App\Models\Inventaris as ModelsInventaris;

class Inventaris
{
    public static function nilaiBuku($tgl, $inv)
    {
        $tgl_beli = $inv->tgl_beli;
        $unit = $inv->unit;
        $harga_satuan = $inv->harsat * $unit;
        $umur = $inv->umur_ekonomis;

        $penyusutan = intval($harga_satuan) / $umur;
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
        $batasan = date('t');
        $thn_awal    = substr($start, 0, 4);
        $bln_awal    = substr($start, 5, 2);   //12
        $tgl_awal    = substr($start, 8, 2);   //29

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
}
