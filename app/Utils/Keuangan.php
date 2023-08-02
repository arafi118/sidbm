<?php

namespace App\Utils;

use App\Models\Kecamatan;
use App\Models\Rekening;
use App\Models\Transaksi;

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

    public static function Saldo($tgl_kondisi, $kode_akun)
    {
        $saldo_awal = self::saldoAwal($tgl_kondisi, $kode_akun);
        $debit = self::saldoD($tgl_kondisi, $kode_akun);
        $kredit = self::saldoK($tgl_kondisi, $kode_akun);

        $lev1 = explode('.', $kode_akun)[0];
        $jenis_mutasi = 'kredit';
        if ($lev1 == '1' || $lev1 == '5') $jenis_mutasi = 'debet';

        if (strtolower($jenis_mutasi) == 'debet') {
            $saldo = ($saldo_awal['debit'] - $saldo_awal['kredit']) + $debit - $kredit;
        } elseif (strtolower($jenis_mutasi) == 'kredit') {
            $saldo = ($saldo_awal['kredit'] - $saldo_awal['debit']) + $kredit - $debit;
        }

        return $saldo;
    }

    public static function SaldoTrx()
    {
        //
    }

    public static function saldoAwal($tgl_kondisi, $kode_akun)
    {
        $thn_kondisi = explode('-', $tgl_kondisi)[0];
        $thn_lalu = $thn_kondisi - 1;

        $rek = Rekening::where('kode_akun', $kode_akun);
        return [
            'debit' => $rek->sum('tb' . $thn_lalu),
            'kredit' => $rek->sum('tbk' . $thn_lalu)
        ];
    }

    // Sum Saldo Debit
    public static function saldoD($tgl_kondisi, $kode_akun)
    {
        $thn_kondisi = explode('-', $tgl_kondisi)[0];
        $awal_tahun = $thn_kondisi . '-01-01';

        $trx = Transaksi::where('rekening_debit', $kode_akun)->whereBetween('tgl_transaksi', [$tgl_kondisi, $awal_tahun])->sum('jumlah');
        return $trx;
    }

    // Sum Saldo Kredit
    public static function saldoK($tgl_kondisi, $kode_akun)
    {
        $thn_kondisi = explode('-', $tgl_kondisi)[0];
        $awal_tahun = $thn_kondisi . '-01-01';

        $trx = Transaksi::where('rekening_kredit', $kode_akun)->whereBetween('tgl_transaksi', [$tgl_kondisi, $awal_tahun])->sum('jumlah');
        return $trx;
    }

    public static function pendapatan($tgl_kondisi)
    {
        $saldo = 0;
        $rekening = Rekening::where('lev1', '4')->get();
        foreach ($rekening as $rek) {
            $saldo += self::Saldo($tgl_kondisi, $rek->kode_akun);
        }

        return $saldo;
    }

    public static function biaya($tgl_kondisi)
    {
        $saldo = 0;
        $rekening = Rekening::where('lev1', '5')->get();
        foreach ($rekening as $rek) {
            $saldo += self::Saldo($tgl_kondisi, $rek->kode_akun);
        }

        return $saldo;
    }

    public static function surplus($tgl_kondisi)
    {
        $pendapatan = self::pendapatan($tgl_kondisi);
        $biaya = self::biaya($tgl_kondisi);

        return ($pendapatan - $biaya);
    }
}
