@php
    $debit = 0;
    $kredit = 0;
@endphp

@extends('admin.kabupaten.laporan.layouts.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td align="center">
                <div style="font-size: 18px;">
                    <b>NERACA BUMDESMA Lkd</b>
                </div>
                <div style="font-size: 18px;">
                    <b>REKAP KABUPATEN {{ strtoupper($kab->nama_kab) }}</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: #000; color: #fff;">
            <td width="10%">Kode</td>
            <td width="70%">Nama Akun</td>
            <td align="right" width="20%">Saldo</td>
        </tr>
        <tr>
            <td colspan="3" height="1"></td>
        </tr>

        @foreach ($neraca as $nc)
            @php
                $sum_akun1 = 0;
            @endphp

            <tr style="background: rgb(74, 74, 74); color: #fff;">
                <td height="20" colspan="3" align="center">
                    <b>{{ $nc['kode_akun'] }}. {{ $nc['nama_akun'] }}</b>
                </td>
            </tr>

            @foreach ($nc['child'] as $lev2)
                <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                    <td>{{ $lev2['kode_akun'] }}.</td>
                    <td colspan="2">{{ $lev2['nama_akun'] }}</td>
                </tr>

                @foreach ($lev2['child'] as $lev3)
                    @php
                        $sum_saldo = 0;
                        foreach ($lev3['child'] as $rek) {
                            $sum_saldo += $rek['saldo'];
                        }

                        $bg = 'rgb(230, 230, 230)';
                        if ($loop->iteration % 2 == 0) {
                            $bg = 'rgba(255, 255, 255)';
                        }

                        $lev1 = explode('.', $nc['kode_akun'])[0];
                        if ($lev1 == '1') {
                            $debit += $sum_saldo;
                        } else {
                            $kredit += $sum_saldo;
                        }

                        $sum_akun1 += $sum_saldo;
                    @endphp

                    <tr style="background: {{ $bg }};">
                        <td>{{ $lev3['kode_akun'] }}.</td>
                        <td>{{ $lev3['nama_akun'] }}</td>
                        @if ($sum_saldo < 0)
                            <td align="right">({{ number_format($sum_saldo * -1, 2) }})</td>
                        @else
                            <td align="right">{{ number_format($sum_saldo, 2) }}</td>
                        @endif
                    </tr>
                @endforeach
            @endforeach

            <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                <td height="15" colspan="2" align="left">
                    <b>Jumlah {{ $nc['nama_akun'] }}</b>
                </td>
                <td align="right">{{ number_format($sum_akun1, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" height="1"></td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3" style="padding: 0px !important;">
                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px;">
                    <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                        <td height="15" width="80%" align="left">
                            <b>Jumlah Liabilitas + Ekuitas </b>
                        </td>
                        <td align="right" width="20%">{{ number_format($kredit, 2) }}</td>
                    </tr>
                </table>

                <div style="margin-top: 16px;"></div>
                {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kab->tanda_tangan), true) !!}
            </td>
        </tr>
    </table>
@endsection
