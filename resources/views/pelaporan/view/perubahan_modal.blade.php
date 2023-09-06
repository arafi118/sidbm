@php
    $t_saldo = 0;
@endphp
@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>LAPORAN PERUBAHAN MODAL</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>

    </table>

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr style="background: rgb(232, 232, 232)">
            <th width="30" height="20">No</th>
            <th>Rekening Modal</th>
            <th width="100">&nbsp;</th>
            <th width="100">&nbsp;</th>
        </tr>

        @foreach ($rekening as $rek)
            @php
                if ($rek->kode_akun == '3.2.02.01') {
                    $saldo = $keuangan->surplus($tgl_kondisi);
                } else {
                    $saldo = $keuangan->Saldo($tgl_kondisi, $rek->kode_akun);
                }
                
                $t_saldo += $saldo;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rek->nama_akun }}</td>
                <td align="right">{{ number_format($saldo) }}</td>
                <td>&nbsp;</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="2">&nbsp;</td>
            <td align="right">{{ number_format($t_saldo) }}</td>
            <td>&nbsp;</td>
        </tr>
    </table>
@endsection
