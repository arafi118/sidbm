<?php

namespace App\Utils;

use App\Models\Kecamatan;
use App\Models\Rekening;
use App\Models\Transaksi;
use DB;
use Illuminate\Contracts\Database\Eloquent\Builder;

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

    public function Saldo($tgl_kondisi, $kode_akun)
    {
        $thn_kondisi = explode('-', $tgl_kondisi)[0];
        $awal_tahun = $thn_kondisi . '-01-01';
        $thn_lalu = $thn_kondisi - 1;

        $rekening = Rekening::select(
            DB::raw("SUM(tb$thn_lalu) as debit"),
            DB::raw("SUM(tbk$thn_lalu) as kredit"),
            DB::raw('(SELECT sum(jumlah) as dbt FROM 
            transaksi_' . auth()->user()->lokasi . ' as td WHERE 
            td.rekening_debit=rekening_' . auth()->user()->lokasi . '.kode_akun AND 
            td.tgl_transaksi BETWEEN "' . $awal_tahun . '" AND "' . $tgl_kondisi . '"
            ) as saldo_debit'),
            DB::raw('(SELECT sum(jumlah) as dbt FROM 
            transaksi_' . auth()->user()->lokasi . ' as td WHERE 
            td.rekening_kredit=rekening_' . auth()->user()->lokasi . '.kode_akun AND 
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
}
