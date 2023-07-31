<title>COVER</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style>
    * {
        font-family: Arial, Helvetica, sans-serif;
    }

    html {
        margin-left: 90px;
    }

</style>

<body>
    <table style="border: 1px solid #000;" width="100%">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td align="center">
                <h1 style="margin: 0px;">{{ strtoupper($judul) }}</h1>
                <div style="margin: 0px; font-size: 24px;">{{ strtoupper($sub_judul) }}</div>
            </td>
        </tr>
        <tr>
            <td height="140">&nbsp;</td>
        </tr>
        <tr>
            <td align="center">
                <img src="../storage/app/public/logo/{{ $logo }}" width="300" alt="{{ $logo }}">
            </td>
        </tr>
        <tr>
            <td height="260">&nbsp;</td>
        </tr>
    </table>
    <table style="border: 1px solid #000;" width="100%">
        <tr>
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
                <div style="font-size: 10px; color: grey; margin-top: 10px;">
                    <i>Tahun {{ date('Y') }}</i>
                </div>
            </td>
        </tr>
    </table>
</body>
