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
                <td>Nama Lembaga</td>
                <td>:</td>
                <td>{{ $nama_lembaga }}</td>
            </tr>
        </table>
    </main>

</body>

</html>
