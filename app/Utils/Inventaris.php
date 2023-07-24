<?php

namespace App\Utils;

use App\Models\Inventaris as ModelsInventaris;

class Inventaris
{
    public static function nilaiBuku($tgl, $id)
    {
        $inv = ModelsInventaris::where('id', $id)->first();

        $tgl_beli = $inv->tgl_beli;
        $unit = $inv->unit;
        $harga_satuan = $inv->harga_satuan;
        $umur = $inv->umur_ekonomis;

        $penyusutan = $harga_satuan / $umur;
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
        $diff = date_diff($start, $end);

        switch ($periode) {
            case 'tahun':
                return $diff->y;
                break;
            case 'bulan':
                return $diff->m;
                break;
            case 'hari':
                return $diff->d;
                break;
        }
    }
}
