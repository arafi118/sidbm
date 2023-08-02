@extends('pelaporan.layout.base')

@section('content')
@php
$saldo_akhir = 0;
@endphp

<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td height="20">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" align="center">
            <div style="font-size: 18px;">
                <b>NERACA</b>
            </div>
            <div style="font-size: 16px;">
                <b>{{ strtoupper($sub_judul) }}</b>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="3" height="5"></td>
    </tr>
    <tr style="background: #000; color: #fff;">
        <td width="30">Kode</td>
        <td width="300">Nama Akun</td>
        <td align="right">Saldo</td>
    </tr>
    <tr>
        <td colspan="3" height="2"></td>
    </tr>

    @foreach ($neraca as $lev1)
    <tr style="background: rgb(74, 74, 74); color: #fff;">
        <td height="20" colspan="3" align="center">
            <b>{{ $lev1['kode_akun'] }}. {{ $lev1['nama_akun'] }}</b>
        </td>
    </tr>

    @foreach ($lev1['lev2'] as $lev2)
    <tr style="background: rgb(167, 167, 167); font-weight: bold;">
        <td>{{ $lev2['kode_akun'] }}.</td>
        <td colspan="2">{{ $lev2['nama_akun'] }}</td>
    </tr>

    @foreach ($lev2['lev3'] as $lev3)

    @php
    $bg = 'rgb(230, 230, 230)';
    if ($loop->iteration % 2 == 0) {
    $bg = 'rgba(255, 255, 255)';
    }
    @endphp

    <tr style="background: {{ $bg }};">
        <td>{{ $lev3['kode_akun'] }}.</td>
        <td>{{ $lev3['nama_akun'] }}</td>
        <td align="right">{{ number_format($lev3['saldo'], 2) }}</td>
    </tr>

    @endforeach

    @endforeach

    <tr style="background: rgb(167, 167, 167); font-weight: bold;">
        <td height="20" colspan="2" align="left">
            <b>Jumlah {{ $lev1['nama_akun'] }}</b>
        </td>
        <td align="right">{{ number_format($lev1['saldo'], 2) }}</td>
    </tr>
    <tr>
        <td colspan="3" height="2"></td>
    </tr>

    @php
    if ($lev1['kode_akun'] != '1.0.00.00') {
    $saldo_akhir += $lev1['saldo'];
    }
    @endphp

    @endforeach

    <tr style="background: rgb(167, 167, 167); font-weight: bold;">
        <td height="20" colspan="2" align="left">
            <b>Jumlah Liabilitas + Ekuitas </b>
        </td>
        <td align="right">{{ number_format($saldo_akhir, 2) }}</td>
    </tr>

</table>
@endsection
