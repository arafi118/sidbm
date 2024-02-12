@php
    $array_saldo = [];
    $j_saldo = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>ARUS KAS</b>
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
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: rgb(200, 200, 200)">
            <th colspan="2">Nama Akun</th>
            <th>Jumlah</th>
        </tr>

        @foreach ($arus_kas as $ak)
            @php
                $dot = substr($ak->nama_akun, 1, 1);
                if ($dot == '.') {
                    $bg = '150, 150, 150';
                } else {
                    $bg = '128, 128, 128';
                }

                $section = false;
            @endphp
            <tr>
                <td colspan="3" height="3"></td>
            </tr>
            <tr style="background: rgb({{ $bg }})">
                <td width="5%" align="center">{{ $keuangan->romawi($ak->super_sub) }}</td>
                <td width="80%">{{ $ak->nama_akun }}</td>
                <td width="15%" align="right">
                    @if ($ak->id == 1)
                        {{ number_format($saldo_bulan_lalu, 2) }}
                    @else
                    @endif
                </td>
            </tr>

            @foreach ($ak->child as $child)
                @php
                    $arus_kas = $keuangan->arus_kas($child->rekening, $tgl_kondisi, $jenis);
                    // dd($child->rekening, $tgl_kondisi, $jenis, $arus_kas);
                    if ($loop->iteration % 2 == 0) {
                        $bg = '240, 240, 240';
                    } else {
                        $bg = '200, 200, 200';
                    }

                    $section = true;
                    $j_saldo += $arus_kas;
                @endphp
                <tr style="background: rgb({{ $bg }})">
                    <td align="center">&nbsp;</td>
                    <td>{{ $child->nama_akun }}</td>
                    <td align="right">{{ number_format($arus_kas, 2) }}</td>
                </tr>
            @endforeach
            @if ($ak->id == 1 or $ak->id == 16 or $ak->id == 46 or $ak->id == 61)
            @else
                @if ($ak->id == 64)
                    <tr>
                        <td colspan="3" style="padding: 0px !important;">
                            <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                                style="font-size: 11px;">
                                <tr style="background: rgb(150, 150, 150); font-weight: bold;">
                                    <td width="5%" align="center">&nbsp;</td>
                                    <td width="80%">Jumlah {{ $ak->nama_akun }}</td>
                                    <td width="15%" align="right">{{ number_format($j_saldo, 2) }}</td>
                                </tr>
                            </table>

                            <div style="margin-top: 24px;"></div>
                            {!! json_decode($kec->ttd->tanda_tangan_pelaporan, true) !!}
                        </td>
                    </tr>
                @else
                    <tr style="background: rgb(150, 150, 150); font-weight: bold;">
                        <td align="center">&nbsp;</td>
                        <td>Jumlah {{ $ak->nama_akun }}</td>
                        <td align="right">{{ number_format($j_saldo, 2) }}</td>
                    </tr>
                @endif
                @php
                    $array_saldo[] = $j_saldo;
                    $j_saldo = 0;
                @endphp
            @endif

            @if ($ak->id == 22)
                @php
                    $total1 = $array_saldo[0] - ($array_saldo[1] + $array_saldo[2]);
                @endphp
                <tr style="background: rgb(128, 128, 128)">
                    <td align="center">&nbsp;</td>
                    <td>Kas Bersih yang diperoleh dari aktivitas Operasi (A-B-C)</td>
                    <td align="right">{{ number_format($array_saldo[0] - ($array_saldo[1] + $array_saldo[2]), 2) }}</td>
                </tr>
            @endif

            @if ($ak->id == 52)
                @php
                    $total2 = $array_saldo[3] - $array_saldo[4];
                @endphp
                <tr style="background: rgb(128, 128, 128)">
                    <td align="center">&nbsp;</td>
                    <td>Kas Bersih yang diperoleh dari aktivitas Investasi (A-B)</td>
                    <td align="right">{{ number_format($array_saldo[3] - $array_saldo[4], 2) }}</td>
                </tr>
            @endif

            @if ($ak->id == 66)
                @php
                    $total3 = $array_saldo[5] - $array_saldo[6];
                @endphp
                <tr style="background: rgb(128, 128, 128)">
                    <td align="center">&nbsp;</td>
                    <td>Kas Bersih yang diperoleh dari aktivitas Pendanaan (A-B)</td>
                    <td align="right">{{ number_format($array_saldo[5] - $array_saldo[6], 2) }}</td>
                </tr>
            @endif
        @endforeach

        <tr>
            <td colspan="3" style="padding: 0px !important;">
                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px;">
                    <tr style="background: rgb(128, 128, 128)">
                        <td width="5%" align="center">&nbsp;</td>
                        <td width="80%">Kenaikan (Penurunan) Kas</td>
                        <td width="15%" align="right">{{ number_format($total1 + $total2 + $total3, 2) }}</td>
                    </tr>
                    <tr style="background: rgb(128, 128, 128)">
                        <td align="center">&nbsp;</td>
                        <td>SALDO AKHIR KAS SETARA KAS</td>
                        <td align="right">{{ number_format($total1 + $total2 + $total3 + $saldo_bulan_lalu, 2) }}</td>
                    </tr>
                </table>

                <div style="margin-top: 16px;"></div>
                {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kec->ttd->tanda_tangan_pelaporan), true) !!}
            </td>
        </tr>
    </table>
@endsection
