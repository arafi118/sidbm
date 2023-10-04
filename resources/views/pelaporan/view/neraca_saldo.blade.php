@php
    $sum_nc_debit = 0;
    $sum_nc_kredit = 0;
    $sum_rl_debit = 0;
    $sum_rl_kredit = 0;
    $sum_ns_debit = 0;
    $sum_ns_kredit = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
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
    </table>

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: rgb(230, 230, 230); font-weight: bold;">
            <th rowspan="2" width="250" height="30">Rekening</th>
            <th colspan="2">Necaca Saldo</th>
            <th colspan="2">Laba Rugi</th>
            <th colspan="2">Neraca</th>
        </tr>
        <tr style="background: rgb(230, 230, 230); font-weight: bold;">
            <th>Debit</th>
            <th>Kredit</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Debit</th>
            <th>Kredit</th>
        </tr>
        @foreach ($rekening as $rek)
            @php
                $saldo = $keuangan->Saldo($tgl_kondisi, $rek->kode_akun);
                
                $debit = 0;
                $kredit = $saldo;
                
                if ($rek->jenis_mutasi == 'debet') {
                    $debit = $saldo;
                    $kredit = 0;
                }
                
                $sum_ns_debit += $debit;
                $sum_ns_kredit += $kredit;
            @endphp

            <tr>
                <th align="left" style="padding: 2px 4px;">
                    {{ $rek->kode_akun . '. ' . $rek->nama_akun }}
                </th>
                <td align="right">{{ number_format($debit) }}</td>
                <td align="right">{{ number_format($kredit) }}</td>

                @if ($rek->lev1 <= 3)
                    @php
                        $sum_nc_debit += $debit;
                        $sum_nc_kredit += $kredit;
                    @endphp
                    <td align="right">{{ number_format(0) }}</td>
                    <td align="right">{{ number_format(0) }}</td>
                    <td align="right">{{ number_format($debit) }}</td>
                    <td align="right">{{ number_format($kredit) }}</td>
                @else
                    @php
                        $sum_rl_debit += $debit;
                        $sum_rl_kredit += $kredit;
                    @endphp
                    <td align="right">{{ number_format($debit) }}</td>
                    <td align="right">{{ number_format($kredit) }}</td>
                    <td align="right">{{ number_format(0) }}</td>
                    <td align="right">{{ number_format(0) }}</td>
                @endif

            </tr>
        @endforeach
        <tr style="background: rgb(167, 167, 167); font-weight: bold;">
            <td align="center">Surplus/Devisit</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">{{ number_format($sum_rl_kredit - $sum_rl_debit) }}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">{{ number_format($sum_rl_kredit - $sum_rl_debit) }}</td>
        </tr>
        <tr style="background: rgb(242, 242, 242); font-weight: bold;">
            <td align="center">Jumlah</td>
            <td align="right">{{ number_format($sum_ns_debit) }}</td>
            <td align="right">{{ number_format($sum_ns_kredit) }}</td>
            <td align="right">{{ number_format($sum_rl_debit + ($sum_rl_kredit - $sum_rl_debit)) }}</td>
            <td align="right">{{ number_format($sum_rl_kredit) }}</td>
            <td align="right">{{ number_format($sum_nc_debit) }}</td>
            <td align="right">{{ number_format($sum_nc_kredit + ($sum_rl_kredit - $sum_rl_debit)) }}</td>
        </tr>
    </table>

    <div style="margin-top: 24px;"></div>
    {!! json_decode($kec->ttd->tanda_tangan_pelaporan, true) !!}
@endsection
