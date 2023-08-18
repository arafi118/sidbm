@extends('pelaporan.layout.base')

@section('content')
    @php
        $saldo1 = 0;
        $saldo_bln_lalu1 = 0;
        
        $saldo2 = 0;
        $saldo_bln_lalu2 = 0;
    @endphp

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <td colspan="4" align="center">
                <div style="font-size: 18px;">
                    <b>LAPORAN LABA RUGI</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4" height="5"></td>
        </tr>
        <tr style="background: rgb(232, 232, 232); font-weight: bold; font-size: 12px;">
            <td align="center" width="255" height="16">Rekening</td>
            <td align="center" width="70">s.d. {{ $header_lalu }}</td>
            <td align="center" width="70">{{ $header_sekarang }}</td>
            <td align="center" width="70">s.d. {{ $header_sekarang }}</td>
        </tr>
        <tr style="background: rgb(200, 200, 200); font-weight: bold; text-transform: uppercase;">
            <td colspan="4" height="14">4. Pendapatan</td>
        </tr>

        @foreach ($pendapatan as $p)
            <tr style="background: rgb(150, 150, 150); font-weight: bold;">
                <td colspan="4" height="14">{{ $p['kode_akun'] }}. {{ $p['nama_akun'] }}</td>
            </tr>

            @foreach ($p['rek'] as $rek)
                @php
                    $bg = 'rgb(230, 230, 230)';
                    if ($loop->iteration % 2 == 0) {
                        $bg = 'rgb(255, 255, 255)';
                    }
                @endphp

                <tr style="background: {{ $bg }}">
                    <td align="left">{{ $rek['kode_akun'] }}. {{ $rek['nama_akun'] }}</td>
                    <td align="right">{{ number_format($rek['saldo_bln_lalu'], 2) }}</td>
                    <td align="right">{{ number_format($rek['saldo'] - $rek['saldo_bln_lalu'], 2) }}</td>
                    <td align="right">{{ number_format($rek['saldo'], 2) }}</td>
                </tr>

                @php
                    $saldo_bln_lalu1 += $rek['saldo_bln_lalu'];
                    $saldo1 += $rek['saldo'];
                @endphp
            @endforeach
        @endforeach

        <tr>
            <td colspan="4" height="2"></td>
        </tr>
        <tr style="background: rgb(200, 200, 200); font-weight: bold; text-transform: uppercase;">
            <td colspan="4" height="14">5. Beban</td>
        </tr>

        @foreach ($beban as $b)
            <tr style="background: rgb(150, 150, 150); font-weight: bold;">
                <td colspan="4" height="14">{{ $b['kode_akun'] }}. {{ $b['nama_akun'] }}</td>
            </tr>

            @foreach ($b['rek'] as $rek)
                @php
                    $bg = 'rgb(230, 230, 230)';
                    if ($loop->iteration % 2 == 0) {
                        $bg = 'rgb(255, 255, 255)';
                    }
                @endphp

                <tr style="background: {{ $bg }}">
                    <td align="left">{{ $rek['kode_akun'] }}. {{ $rek['nama_akun'] }}</td>
                    <td align="right">{{ number_format($rek['saldo_bln_lalu'], 2) }}</td>
                    <td align="right">{{ number_format($rek['saldo'] - $rek['saldo_bln_lalu'], 2) }}</td>
                    <td align="right">{{ number_format($rek['saldo'], 2) }}</td>
                </tr>

                @php
                    $saldo_bln_lalu1 -= $rek['saldo_bln_lalu'];
                    $saldo1 -= $rek['saldo'];
                @endphp
            @endforeach
        @endforeach

        <tr style="background: rgb(200, 200, 200); font-weight: bold;">
            <td align="left">A. Laba Rugi OPERASIONAL (Kode Akun 4.1 - 5.1 - 5.2) </td>
            <td align="right">{{ number_format($saldo_bln_lalu1, 2) }}</td>
            <td align="right">{{ number_format($saldo1 - $saldo_bln_lalu1, 2) }}</td>
            <td align="right">{{ number_format($saldo1, 2) }}</td>
        </tr>

        <tr>
            <td colspan="4" height="2"></td>
        </tr>

        @foreach ($pendapatanNOP as $pNOP)
            <tr style="background: rgb(150, 150, 150); font-weight: bold;">
                <td colspan="4" height="14">{{ $pNOP['kode_akun'] }}. {{ $pNOP['nama_akun'] }}</td>
            </tr>

            @foreach ($pNOP['rek'] as $rek)
                @php
                    $bg = 'rgb(230, 230, 230)';
                    if ($loop->iteration % 2 == 0) {
                        $bg = 'rgb(255, 255, 255)';
                    }
                @endphp

                <tr style="background: {{ $bg }}">
                    <td align="left">{{ $rek['kode_akun'] }}. {{ $rek['nama_akun'] }}</td>
                    <td align="right">{{ number_format($rek['saldo_bln_lalu'], 2) }}</td>
                    <td align="right">{{ number_format($rek['saldo'] - $rek['saldo_bln_lalu'], 2) }}</td>
                    <td align="right">{{ number_format($rek['saldo'], 2) }}</td>
                </tr>

                @php
                    $saldo_bln_lalu2 += $rek['saldo_bln_lalu'];
                    $saldo2 += $rek['saldo'];
                @endphp
            @endforeach
        @endforeach

        @foreach ($bebanNOP as $bNOP)
            <tr style="background: rgb(150, 150, 150); font-weight: bold;">
                <td colspan="4" height="14">{{ $bNOP['kode_akun'] }}. {{ $bNOP['nama_akun'] }}</td>
            </tr>

            @foreach ($bNOP['rek'] as $rek)
                @php
                    $bg = 'rgb(230, 230, 230)';
                    if ($loop->iteration % 2 == 0) {
                        $bg = 'rgb(255, 255, 255)';
                    }
                @endphp

                <tr style="background: {{ $bg }}">
                    <td align="left">{{ $rek['kode_akun'] }}. {{ $rek['nama_akun'] }}</td>
                    <td align="right">{{ number_format($rek['saldo_bln_lalu'], 2) }}</td>
                    <td align="right">{{ number_format($rek['saldo'] - $rek['saldo_bln_lalu'], 2) }}</td>
                    <td align="right">{{ number_format($rek['saldo'], 2) }}</td>
                </tr>

                @php
                    $saldo_bln_lalu2 -= $rek['saldo_bln_lalu'];
                    $saldo2 -= $rek['saldo'];
                @endphp
            @endforeach
        @endforeach

        <tr style="background: rgb(200, 200, 200); font-weight: bold;">
            <td align="left">B. Laba Rugi OPERASIONAL (Kode Akun 4.2 - 5.3) </td>
            <td align="right">{{ number_format($saldo_bln_lalu2, 2) }}</td>
            <td align="right">{{ number_format($saldo2 - $saldo_bln_lalu2, 2) }}</td>
            <td align="right">{{ number_format($saldo2, 2) }}</td>
        </tr>

        <tr>
            <td colspan="4" height="2"></td>
        </tr>

        <tr style="background: rgb(200, 200, 200); font-weight: bold;">
            <td align="left">C. Laba Rugi Sebelum Taksiran Pajak (A + B) </td>
            <td align="right">{{ number_format($saldo_bln_lalu1 + $saldo_bln_lalu2, 2) }}</td>
            <td align="right">{{ number_format($saldo1 - $saldo_bln_lalu1 + ($saldo2 - $saldo_bln_lalu2), 2) }}</td>
            <td align="right">{{ number_format($saldo1 + $saldo2, 2) }}</td>
        </tr>

        <tr>
            <td colspan="4" height="2"></td>
        </tr>

        <tr style="background: rgb(150, 150, 150); font-weight: bold;">
            <td colspan="4" height="14">5.4 Beban Pajak</td>
        </tr>
        <tr style="background: rgb(230, 230, 230)">
            <td align="left">5.4.01.01. Taksiran PPh (0.5%) </td>
            <td align="right">{{ number_format($pph['bulan_lalu'], 2) }}</td>
            <td align="right">{{ number_format($pph['sekarang'] - $pph['bulan_lalu'], 2) }}</td>
            <td align="right">{{ number_format($pph['sekarang'], 2) }}</td>
        </tr>

        <tr>
            <td colspan="4" height="2"></td>
        </tr>

        <tr style="background: rgb(200, 200, 200); font-weight: bold;">
            <td align="left">C. Laba Rugi Setelah Taksiran Pajak (A + B) </td>
            <td align="right">{{ number_format($saldo_bln_lalu1 + $saldo_bln_lalu2 - $pph['bulan_lalu'], 2) }}</td>
            <td align="right">
                {{ number_format($saldo1 - $saldo_bln_lalu1 + ($saldo2 - $saldo_bln_lalu2) - ($pph['sekarang'] - $pph['bulan_lalu']), 2) }}
            </td>
            <td align="right">{{ number_format($saldo1 + $saldo2 - $pph['sekarang'], 2) }}</td>
        </tr>

    </table>
@endsection
