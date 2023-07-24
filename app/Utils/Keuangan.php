<?php

namespace App\Utils;

use App\Models\Kecamatan;

class Keuangan
{
    public static function bulatkan($angka)
    {
        $kec = Kecamatan::where('id', auth()->user()->lokasi)->first();
        $pembulatan    = number_format($kec->pembulatan, 0, '', '');
        $length        = strlen($pembulatan);
        $uang        = floor($angka);
        $bulat         = substr($pembulatan, 1);
        if ($length <= 3) {
            $pecahan         = substr($uang, -2);
            $pengali        = $bulat * 2;
        } else {
            $pecahan         = substr($uang, -3);
            $pengali        = 1000;
        }
        if ($pembulatan > 0) {
            if ($pecahan < $bulat and $pecahan > 0) {
                $pembulatan = $uang + ($bulat - $pecahan);
            } else if ($pecahan > $bulat) {
                $pembulatan = $uang + $pengali - $pecahan;
            } else if ($pecahan == 0 or $pecahan == $bulat) {
                $pembulatan = $uang;
            }
        } else  if ($pembulatan < 0) {
            if ($pecahan < $bulat and $pecahan > 0) {
                $pembulatan = $uang - $pecahan;
            } else if ($pecahan > $bulat) {
                $pembulatan = $uang + (($bulat * 2) - $pecahan);
            } else if ($pecahan == 0 or $pecahan == $bulat) {
                $pembulatan = $uang;
            }
        } else {
            $pembulatan = $uang;
        }

        return $pembulatan;
    }

    public static function startWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}
