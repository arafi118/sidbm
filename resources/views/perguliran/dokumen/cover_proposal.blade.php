@php
    use App\Utils\Keuangan;
    use App\Utils\Tanggal;
    if (Keuangan::startWith($kec->kabupaten->nama_kab, 'KOTA') || Keuangan::startWith($kec->kabupaten->nama_kab, 'KAB')) {
        $nama_kab = ucwords(strtolower($kec->kabupaten->nama_kab));
    } else {
        $nama_kab = ' Kabupaten ' . ucwords(strtolower($kec->kabupaten->nama_kab));
    }
@endphp

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
                <div style="margin: 0px; font-size: 24px;">
                    {{ strtoupper('Pinjaman Kelompok ' . $pinkel->jpp->nama_jpp) }}
                </div>
            </td>
        </tr>
        <tr>
            <td height="140">&nbsp;</td>
        </tr>
        <tr>
            <td align="center">
                <img src="../storage/app/public/logo/{{ $kec->logo }}" width="300" alt="{{ $kec->logo }}">
            </td>
        </tr>
        <tr>
            <td align="center">
                <div style="margin-top: 10px; font-size: 24px;">
                    Kelompok {{ $pinkel->kelompok->nama_kelompok }}
                </div>
                <div style="font-size: 20px;">
                    {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }} {{ $pinkel->kelompok->d->nama_desa }}
                </div>
            </td>
        </tr>
        <tr>
            <td height="160" align="center"></td>
        </tr>
        <tr>
            <td align="center">
                <div style="font-weight: bold;">Pengajuan Rp. {{ number_format($pinkel->proposal) }}</div>
                <div style="font-weight: bold;">Tanggal Proposal {{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</div>
                <div style="font-weight: bold;">Tenor {{ $pinkel->jangka }} Bulan</div>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid #000;" width="100%">
        <tr>
            <td align="center">
                <div style="margin-top: 10px;">
                    <b>{{ strtoupper($kec->nama_lembaga_sort) }}</b>
                </div>
                <div>
                    {{ strtoupper($kec->sebutan_kec . ' ' . $kec->nama_kec . ' ' . $nama_kab) }}
                </div>
                <div style="font-size: 11px; color: grey;">
                    <i>SK Kemenkumham RI No. {{ $kec->nomor_bh }}</i>
                </div>
                <div style="font-size: 11px; color: grey;">
                    <i></i>
                </div>
                <div style="font-size: 11px; color: grey;">
                    <i>Alamat {{ $kec->alamat_kec . ', Telp.' . $kec->telpon_kec }}</i>
                </div>
                <div style="font-size: 11px; color: grey; margin-top: 11px;">
                </div>
            </td>
        </tr>
    </table>
</body>
