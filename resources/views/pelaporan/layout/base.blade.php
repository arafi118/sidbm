<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ ucwords(str_replace('_',' ', $laporan)) }} ({{ ucwords($tgl) }})</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        html {
            margin-left: 90px;
        }

        header {
            position: fixed;
            top: -10px;
            left: 0px;
            right: 0px;
        }

        table tr td {
            padding-left: 4px;
            padding-right: 4px;
        }

    </style>
</head>

<body>
    <header>
        @if ($laporan == 'surat_pengantar')
        <table width="100%" style="border-bottom: 1px double #000; border-width: 4px;">
            <tr>
                <td width="70">
                    <img src="../storage/app/public/logo/{{ $logo }}" width="80" alt="{{ $logo }}">
                </td>
                <td align="center">
                    <div>{{ strtoupper($nama_lembaga) }}</div>
                    <div>
                        <b>{{ strtoupper($nama_kecamatan) }}</b>
                    </div>
                    <div style="font-size: 10px; color: grey;">
                        <i>{{ $nomor_usaha }}</i>
                    </div>
                    <div style="font-size: 10px; color: grey;">
                        <i>{{ $info }}</i>
                    </div>
                    <div style="font-size: 10px; color: grey;">
                        <i>{{ $email }}</i>
                    </div>
                </td>
            </tr>
        </table>
        @else
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
        @endif
    </header>

    @php
    $style = 'position: relative; top: 30px; font-size: 10px;';
    if ($laporan == 'surat_pengantar') {
    $style = 'margin-top: 75px; font-size: 10px;';
    }
    @endphp

    <main style="{{ $style }}">
        @yield('content')
    </main>
</body>

</html>
