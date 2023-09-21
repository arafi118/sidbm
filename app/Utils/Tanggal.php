<?php

namespace App\Utils;

use Carbon\Carbon;
use App\Utils\Keuangan;

class Tanggal
{
    public static function tglIndo($tanggal)
    {
        $array_tgl = explode('-', $tanggal);
        $tahun = $array_tgl[0];
        $bulan = $array_tgl[1];
        $hari = $array_tgl[2];

        if (strlen($hari) > 0 && strlen($bulan) > 0) {
            $tanggal = $tahun . '-' . $bulan . '-' . $hari;
        } elseif (strlen($bulan) > 0) {
            $tanggal = $tahun . '-' . $bulan . '-01';
        } else {
            $tanggal = $tahun . '-12-31';
        }
        $tgl = new Carbon($tanggal);

        return $tgl->isoFormat('DD/MM/YYYY');
    }

    public static function tglNasional($tanggal)
    {
        $tanggal = explode('/', $tanggal);

        $tanggal_baru = "$tanggal[2]-$tanggal[1]-$tanggal[0]";
        $tgl = new Carbon($tanggal_baru);

        return $tgl->isoFormat('YYYY-MM-DD');
    }

    public static function tglRomawi($tanggal)
    {
        $keuangan = new Keuangan;
        $array_tgl = explode('-', $tanggal);
        $tahun = $array_tgl[0];
        $bulan = $array_tgl[1];
        $hari = $array_tgl[2];

        $bulan_rom = $keuangan->romawi($bulan);
        $hari_rom = $keuangan->romawi($hari);

        return $hari . '/' . $bulan_rom . '/' . $tahun;
    }

    public static function tglLatin($tanggal)
    {
        $tgl = explode('-', $tanggal);

        return $tgl[2] . ' ' . self::namaBulan($tanggal) . ' ' . $tgl[0];
    }

    public static function tahun($tanggal)
    {
        $tgl = explode('-', $tanggal);
        $thn = $tgl[0];

        return $thn;
    }

    public static function namaBulan($tanggal)
    {
        $tgl = explode('-', $tanggal);
        $bln = $tgl[1];

        switch ($bln) {
            case '01':
                $bulan = 'Januari';
                break;
            case '02':
                $bulan = 'Februari';
                break;
            case '03':
                $bulan = 'Maret';
                break;
            case '04':
                $bulan = 'April';
                break;
            case '05':
                $bulan = 'Mei';
                break;
            case '06':
                $bulan = 'Juni';
                break;
            case '07':
                $bulan = 'Juli';
                break;
            case '08':
                $bulan = 'Agustus';
                break;
            case '09':
                $bulan = 'September';
                break;
            case '10':
                $bulan = 'Oktober';
                break;
            case '11':
                $bulan = 'November';
                break;
            case '12':
                $bulan = 'Desember';
                break;
        }

        return $bulan;
    }

    public static function hari($tanggal)
    {
        $tgl = explode('-', $tanggal);
        $hari = $tgl[2];

        return $hari;
    }
}
