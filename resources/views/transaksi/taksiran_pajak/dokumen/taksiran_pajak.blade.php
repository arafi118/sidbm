@php
    use App\Utils\Tanggal;
@endphp

<!DOCTYPE html>
<html lang="en" translate="no">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Taksiran Pajak {{ $pph }}%</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        html {
            margin: 75.59px;
            margin-left: 94.48px;
        }

        ul,
        ol {
            margin-left: -10px;
            page-break-inside: auto !important;
        }

        footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
        }

        table tr th,
        table tr td {
            padding: 2px 4px;
        }

        table.p tr th,
        table.p tr td {
            padding: 4px 4px;
        }

        table.p0 tr th,
        table.p0 tr td {
            padding: 0px !important;
        }

        table tr td table:not(.padding) tr td {
            padding: 0 !important;
        }

        table tr.m td:first-child {
            margin-left: 24px;
        }

        table tr.m td:last-child {
            margin-right: 24px;
        }

        table tr.vt td,
        table tr.vb td.vt {
            vertical-align: top;
        }

        table tr.vb td,
        table tr.vt td.vb {
            vertical-align: bottom;
        }

        .break {
            page-break-after: always;
        }

        li {
            text-align: justify;
        }

        .l {
            border-left: 1px solid #000;
        }

        .t {
            border-top: 1px solid #000;
        }

        .r {
            border-right: 1px solid #000;
        }

        .b {
            border-bottom: 1px solid #000;
        }
    </style>
</head>

<body>
    <style>
        main {
            position: relative;
            font-size: 12px;
            top: -20px;
        }
    </style>

    <main>
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr class="b">
                <td colspan="3" align="center">
                    <div style="font-size: 18px;">
                        <b>TAKSIRAN PAJAK</b>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" height="5"></td>
            </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td width="28%">Nama Lembaga</td>
                <td width="2%">:</td>
                <td>{{ $nama_lembaga }}</td>
            </tr>
            <tr>
                <td>NPWP</td>
                <td>:</td>
                <td>{{ $npwp }}</td>
            </tr>
            <tr>
                <td>Tanggal NPWP</td>
                <td>:</td>
                <td>{{ Tanggal::tglLatin(Tanggal::tglNasional($tanggal_npwp) ?: date('Y-m-d')) }}</td>
            </tr>
            <tr>
                <td>Tahun Pajak</td>
                <td>:</td>
                <td>{{ $tahun_pajak }}</td>
            </tr>
            <tr>
                <td>Masa Pajak</td>
                <td>:</td>
                <td>{{ $bulan_masa_pajak }}</td>
            </tr>
            <tr>
                <td>Taksiran Pajak ({{ $pph }}%)</td>
                <td>:</td>
                @if ($pph == '11')
                    <td>Rp. {{ $pph_badan }}</td>
                @else
                    <td>Rp. {{ $pph_final }}</td>
                @endif
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
        </table>

        @foreach ($akun2 as $akun)
            @php
                if ($akun->rek->isEmpty()) {
                    continue;
                }
            @endphp
            <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
                <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;">
                    <td colspan="2">{{ $akun->nama_akun }}</td>
                </tr>

                @foreach ($akun->rek as $rek)
                    <tr>
                        <td width="50%">
                            {{ $rek->kode_akun }}. {{ $rek->nama_akun }}
                        </td>
                        <td width="50%" align="right">
                            Rp. {{ number_format($rekening[$rek->kode_akun], 2) }}
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
            </table>
        @endforeach

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;">
                <td width="50%">Total Pendapatan</td>
                <td width="50%" align="right">Rp. {{ number_format($pendapatan, 2) }}</td>
            </tr>
            <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;">
                <td width="50%">Total Biaya (tidak termasuk Pajak)</td>
                <td width="50%" align="right">Rp. {{ number_format($beban, 2) }}</td>
            </tr>
            <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;">
                <td width="50%">Laba Sebelum Taksiran Pajak</td>
                <td width="50%" align="right">Rp. {{ number_format($laba, 2) }}</td>
            </tr>
        </table>
    </main>

</body>

</html>
