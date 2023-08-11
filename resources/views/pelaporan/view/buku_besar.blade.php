@php
use App\Utils\Tanggal;
$total_saldo = 0;

if ($rek->jenis_mutasi == 'debet') {
$saldo_awal_tahun = $saldo['debit'] - $saldo['kredit'];
$saldo_awal_bulan = ($d_bulan_lalu - $k_bulan_lalu);
$total_saldo = $saldo_awal_tahun + $saldo_awal_bulan;
} else {
$saldo_awal_tahun = $saldo['kredit'] - $saldo['debit'];
$saldo_awal_bulan = ($k_bulan_lalu - $d_bulan_lalu);
$total_saldo = $saldo_awal_tahun + $saldo_awal_bulan;
}

$total_debit = 0;
$total_kredit = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
    <tr>
        <td colspan="4" align="center">
            <div style="font-size: 18px;">
                <b>BUKU BESAR {{ strtoupper($rek->nama_akun) }}</b>
            </div>
            <div style="font-size: 16px;">
                <b>{{ strtoupper($sub_judul) }}</b>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4" height="5"></td>
    </tr>
</table>

<div style="width: 100%; text-align: right;">Kode Akun : {{ $rek->kode_akun }}</div>
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
    <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;">
        <td height="15" align="center" width="10">No</td>
        <td align="center" width="35">Tanggal</td>
        <td align="center" width="35">Ref ID.</td>
        <td align="center" width="175">Keterangan</td>
        <td align="center">Debit</td>
        <td align="center">Kredit</td>
        <td align="center">Saldo</td>
        <td align="center">Ins</td>
    </tr>

    <tr>
        <td class="l r b" align="center"></td>
        <td class="l r b" align="center">{{ Tanggal::tglIndo($tahun . '-01-01') }}</td>
        <td class="l r b" align="center"></td>
        <td class="l r b">Komulatif Transaksi Awal Tahun {{ $tahun }}</td>
        <td class="l r b" align="right">{{ number_format($saldo['debit']) }}</td>
        <td class="l r b" align="right">{{ number_format($saldo['kredit']) }}</td>
        <td class="l r b" align="right">{{ number_format($saldo_awal_tahun) }}</td>
        <td class="l r b" align="center"></td>
    </tr>
    <tr>
        <td class="l r b t" align="center"></td>
        <td class="l r b t" align="center">{{ Tanggal::tglIndo($tahun . '-' . $bulan . '-01') }}</td>
        <td class="l r b t" align="center"></td>
        <td class="l r b t">Komulatif Transaksi s/d Bulan Lalu</td>
        <td class="l r b t" align="right">{{ number_format($d_bulan_lalu) }}</td>
        <td class="l r b t" align="right">{{ number_format($k_bulan_lalu) }}</td>
        <td class="l r b t" align="right">{{ number_format($total_saldo) }}</td>
        <td class="l r b t" align="center"></td>
    </tr>

    @foreach ($transaksi as $trx)

    @php
    if ($trx->rekening_debit == $rek->kode_akun) {
    $ref = substr($trx->rekening_kredit, 0, 3);
    $debit = $trx->jumlah;
    $kredit = 0;
    } else {
    $ref = substr($trx->rekening_debit, 0, 3);
    $debit = 0;
    $kredit = $trx->jumlah;
    }

    if ($rek->jenis_mutasi == 'debet') {
    $_saldo = $debit - $kredit;
    } else {
    $_saldo = $kredit - $debit;
    }

    $total_saldo += $_saldo;
    $total_debit += $debit;
    $total_kredit += $kredit;
    @endphp

    <tr>
        <td class="l r b t" align="center">{{ $loop->iteration }}</td>
        <td class="l r b t" align="center">{{ Tanggal::tglIndo($trx->tgl_transaksi) }}</td>
        <td class="l r b t" align="center">{{ $ref . '-' . $trx->idt }}</td>
        <td class="l r b t">{{ $trx->keterangan_transaksi }}</td>
        <td class="l r b t" align="right">{{ number_format($debit) }}</td>
        <td class="l r b t" align="right">{{ number_format($kredit) }}</td>
        <td class="l r b t" align="right">{{ number_format($total_saldo) }}</td>
        <td class="l r b t" align="center">{{ $trx->user->ins }}</td>
    </tr>
    @endforeach

    <tr>
        <td class="l r b t" colspan="4">
            <b>Total Transaksi {{ ucwords($sub_judul) }}</b>
        </td>
        <td class="l r b t" align="right">
            <b>{{ number_format($total_debit) }}</b>
        </td>
        <td class="l r b t" align="right">
            <b>{{ number_format($total_kredit) }}</b>
        </td>
        <td class="l r b t" colspan="2" rowspan="3" align="center">
            <b>{{ number_format($total_saldo) }}</b>
        </td>
    </tr>

    <tr>
        <td class="l r b t" colspan="4">
            <b>Total Transaksi sampai dengan {{ ucwords($sub_judul) }}</b>
        </td>
        <td class="l r b t" align="right">
            <b>{{ number_format($d_bulan_lalu + $total_debit) }}</b>
        </td>
        <td class="l r b t" align="right">
            <b>{{ number_format($k_bulan_lalu + $total_kredit) }}</b>
        </td>
    </tr>

    <tr>
        <td class="l r b t" colspan="4">
            <b>Total Transaksi Komulatif sampai dengan Tahun {{ $tahun }}</b>
        </td>
        <td class="l r b t" align="right">
            <b>{{ number_format($saldo['debit'] + $d_bulan_lalu + $total_debit) }}</b>
        </td>
        <td class="l r b t" align="right">
            <b>{{ number_format($saldo['kredit'] + $k_bulan_lalu + $total_kredit) }}</b>
        </td>
    </tr>

</table>
@endsection
