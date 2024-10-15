@php
    use App\Utils\Tanggal;

    if ($jenis == 'excel') {
        $nama_file = ucwords(str_replace('_', ' ', $laporan)) . ' (' . ucwords($tgl) . ')';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $nama_file . '.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
    }
@endphp

<!DOCTYPE html>
<html lang="en" translate="no">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        PROYEKSI PENDAPATAN JASA TAHUN {{ $tahun }}
    </title>
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

        header {
            position: fixed;
            top: -10px;
            left: 0px;
            right: 0px;
        }

        table tr th,
        table tr td,
        table tr td table.p tr td {
            padding: 2px 4px !important;
        }

        table tr td table tr td {
            padding: 0 !important;
        }

        table.p0 tr th,
        table.p0 tr td {
            padding: 0px !important;
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

    <style>
        html {
            margin-left: 40px;
            margin-right: 40px;
        }
    </style>
</head>

<body>
    <header>
        <table width="100%" style="border-bottom: 1px solid grey;">
            <tr>
                <td width="30">
                    <img src="../storage/app/public/logo/{{ $logo }}" width="40" alt="{{ $logo }}">
                </td>
                <td>
                    <div style="font-size: 12px;">{{ strtoupper($nama_lembaga) }}</div>
                    <div style="font-size: 12px;">
                        <b>{{ strtoupper($nama_kecamatan) }}</b>
                    </div>
                </td>
            </tr>
        </table>
        <table width="100%" style="position: relative; top: -10px;">
            <tr>
                <td>
                    <span style="font-size: 8px; color: grey;">
                        <i>{{ $nomor_usaha }}</i>
                    </span>
                </td>
                <td align="right">
                    <span style="font-size: 8px; color: grey;">
                        <i>{{ $info }}</i>
                    </span>
                </td>
            </tr>
        </table>
    </header>

    <main style="position: relative; top: 60px; font-size: 12px; padding-bottom: 37.79px;">
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
            <tr>
                <td colspan="3" align="center">
                    <div style="font-size: 18px;">
                        <b>PROYEKSI PENDAPATAN JASA</b>
                    </div>
                    <div style="font-size: 16px;">
                        <b style="text-transform: uppercase;">
                            TAHUN {{ $tahun }}
                        </b>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" height="5"></td>
            </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr style="background: rgb(232, 232, 232); font-weight: bold; font-size: 12px;">
                <th class="t l b" height="20" width="4%">Jenis</th>
                @for ($i = 1; $i <= 12; $i++)
                    @php
                        $tgl = $tahun . '-' . str_pad($i, 2, '0', STR_PAD_LEFT) . '-01';
                        $nama_bulan = Tanggal::namaBulan($tgl);
                    @endphp

                    <th class="t l b {{ $i == 12 ? 'r' : '' }}" width="8%">{{ $nama_bulan }}</th>
                @endfor
            </tr>

            @php
                $total = [];
            @endphp
            @foreach ($pendapatan_jasa as $pj)
                <tr>
                    <td class="t l b">
                        <b>{{ $pj['nama'] }}</b>
                    </td>
                    @foreach ($pj['data'] as $target => $val)
                        @php
                            if (isset($total[$target])) {
                                $total[$target] += $val;
                            } else {
                                $total[$target] = $val;
                            }
                        @endphp
                        <td class="t l b {{ $target == 12 ? 'r' : '' }}" align="right">
                            {{ number_format($val, 2) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            <tr style="background: rgb(150, 150, 150); font-weight: bold;">
                <th class="t l b" height="14">Total</th>
                @for ($i = 1; $i <= 12; $i++)
                    <th class="t l b {{ $i == 12 ? 'r' : '' }}" align="right">{{ number_format($total[$i], 2) }}</th>
                @endfor
            </tr>
        </table>
    </main>
</body>

</html>
