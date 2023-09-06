@php
    $array_saldo = [];
    $j_saldo = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
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
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr style="background: rgb(200, 200, 200)">
            <th colspan="2">Nama Akun</th>
            <th width="100">Jumlah</th>
        </tr>

        @foreach ($arus_kas as $ak)
            @php
                $dot = substr($ak->nama_akun, 1, 1);
                if ($dot == '.') {
                    $bg = '150, 150, 150';
                } else {
                    $bg = '100, 100, 100';
                }
                
                $section = false;
            @endphp
            <tr>
                <td colspan="3" height="3"></td>
            </tr>
            <tr style="background: rgb({{ $bg }})">
                <td width="30" align="center">{{ $keuangan->romawi($ak->super_sub) }}</td>
                <td>{{ $ak->nama_akun }}</td>
                <td></td>
            </tr>

            @foreach ($ak->child as $child)
                @php
                    $arus_kas = $keuangan->arus_kas($child->rekening, $tgl_kondisi, $jenis);
                    if ($loop->iteration % 2 == 0) {
                        $bg = '240, 240, 240';
                    } else {
                        $bg = '200, 200, 200';
                    }
                    
                    $section = true;
                    $j_saldo += $arus_kas;
                @endphp
                <tr style="background: rgb({{ $bg }})">
                    <td width="30" align="center">&nbsp;</td>
                    <td>{{ $child->nama_akun }}</td>
                    <td align="right">{{ number_format($arus_kas) }}</td>
                </tr>
            @endforeach
            @if ($ak->id == 1 or $ak->id == 16 or $ak->id == 46 or $ak->id == 61)
            @else
                <tr style="background: rgb(150, 150, 150); font-weight: bold;">
                    <td width="30" align="center">&nbsp;</td>
                    <td>Jumlah {{ $ak->nama_akun }}</td>
                    <td align="right">{{ number_format($j_saldo) }}</td>
                </tr>
                @php
                    $array_saldo[] = $j_saldo;
                    $j_saldo = 0;
                @endphp
            @endif

            @if ($ak['id'] == 22)
                <tr style="background: rgb(100, 100, 100)">
                    <td width="30" align="center">&nbsp;</td>
                    <td>Kas Bersih yang diperoleh dari aktivitas Operasi (A-B-C)</td>
                    <td align="right">{{ number_format($array_saldo[0] - ($array_saldo[1] + $array_saldo[2])) }}</td>
                </tr>
            @endif

            @if ($ak['id'] == 52)
                <tr style="background: rgb(100, 100, 100)">
                    <td width="30" align="center">&nbsp;</td>
                    <td>Kas Bersih yang diperoleh dari aktivitas Investasi (A-B)</td>
                    <td align="right">{{ number_format($array_saldo[3] - $array_saldo[4]) }}</td>
                </tr>
            @endif

            @if ($ak['id'] == 66)
                <tr style="background: rgb(100, 100, 100)">
                    <td width="30" align="center">&nbsp;</td>
                    <td>Kas Bersih yang diperoleh dari aktivitas Pendanaan (A-B)</td>
                    <td align="right">{{ number_format($array_saldo[5] - $array_saldo[6]) }}</td>
                </tr>
            @endif
        @endforeach
    </table>
@endsection
