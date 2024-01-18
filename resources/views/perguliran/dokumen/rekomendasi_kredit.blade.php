@php
    use App\Utils\Tanggal;
@endphp

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ ucwords(str_replace('_', ' ', $judul)) }}</title>
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

        table tr td table tr td {
            padding: 0 !important;
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
    <main>
        <table border="0" width="100%" cellspacing="0" cellpadding="0"
            style="font-size: 11px; position: relative; top: -20px;">
            <tr class="b">
                <td align="center">
                    <img src="../storage/app/public/logo_kab/{{ $kab->id }}.png" width="70"
                        alt="{{ $kab->id }}.png" style="margin-bottom: 8px;">
                </td>
                <td align="center">
                    <div style="font-size: 18px;">
                        PEMERINTAH DAERAH {{ strtoupper($nama_kabupaten) }}
                    </div>
                    <div style="font-size: 18px;">
                        {{ strtoupper($kec->sebutan_kec) }} {{ strtoupper($kec->nama_kec) }}
                    </div>
                    <div style="font-size: 18px;">
                        <b>
                            {{ strtoupper($pinkel->kelompok->d->sebutan_desa->sebutan_desa) }}
                            {{ strtoupper($pinkel->kelompok->d->nama_desa) }}
                        </b>
                    </div>
                </td>
            </tr>
        </table>

        <table border="0" width="85%" align="center"cellspacing="0" cellpadding="0" style="font-size: 12px;">
            <tr>
                <td align="center">
                    <div style="font-size: 18px;">
                        <b>SURAT REKOMENDASI KREDIT {{ $pinkel->jpp->nama_jpp }}</b>
                    </div>
                    <div style="font-size: 12px;">
                        Nomor: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    </div>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
        </table>
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
            <tr>
                <td colspan="3" align="justify">
                    Yang bertanda tangan di bawah ini {{ $pinkel->kelompok->d->sebutan_desa->sebutan_kades }}
                    {{ $pinkel->kelompok->d->nama_desa }} menerangkan bahwa Kelompok dan Pengurus tersebut namanya di
                    bawah ini :
                </td>
            </tr>

            <tr>
                <td width="120" rowspan="3" style="vertical-align: top;">Nama Lengkap / Jabatan</td>
                <td width="5" align="center">:</td>
                <td>{{ $pinkel->kelompok->ketua }} / Ketua Kelompok</td>
            </tr>
            <tr>
                <td align="center">:</td>
                <td>{{ $pinkel->kelompok->sekretaris }} / Sekretaris Kelompok</td>
            </tr>
            <tr>
                <td align="center">:</td>
                <td>{{ $pinkel->kelompok->bendahara }} / Bendahara Kelompok</td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td>Nama Kelompok</td>
                <td align="center">:</td>
                <td>{{ $pinkel->kelompok->nama_kelompok }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td align="center">:</td>
                <td>
                    {{ $pinkel->kelompok->alamat_kelompok }} {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
                    {{ $pinkel->kelompok->d->nama_desa }} {{ $kec->sebutan_kec }} {{ $kec->nama_kec }}
                    {{ $nama_kabupaten }}
                </td>
            </tr>
            <tr>
                <td>Jumlah anggota</td>
                <td align="center">:</td>
                <td>{{ $pinkel->pinjaman_anggota_count }}
                    ({{ $keuangan->terbilang($pinkel->pinjaman_anggota_count) }}) Orang</td>
            </tr>
            <tr>
                <td>Jumlah Pengajuan</td>
                <td align="center">:</td>
                <td>{{ number_format($pinkel->proposal) }} ({{ $keuangan->terbilang($pinkel->proposal) }})</td>
            </tr>
            <tr>
                <td align="justify" colspan="3">
                    Benar keberadaannya dan Layak direkomendasikan untuk mendapatkan
                    Kredit Modal {{ $pinkel->jpp->deskripsi_jpp }} ({{ $pinkel->jpp->nama_jpp }}) pada
                    {{ $kec->nama_lembaga_sort }} {{ $kec->sebutan_kec }} {{ $kec->nama_kec }}
                    {{ $nama_kabupaten }}.
                </td>
            </tr>
            <tr>
                <td align="justify" colspan="3">
                    Demikian Surat Rekomendasi ini diberikan kepada yang bersangkutan untuk dipergunakan seperlunya.
                </td>
            </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
            <tr>
                <td width="33%" height="30">&nbsp;</td>
                <td width="33%">&nbsp;</td>
                <td width="33%">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td align="center">{{ $nama_kab }}, {{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td align="center">
                    {{ $pinkel->kelompok->d->sebutan_desa->sebutan_kades }} {{ $pinkel->kelompok->d->nama_desa }}
                </td>
            </tr>
            <tr>
                <td colspan="3" height="40">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td align="center">
                    <u>
                        <b>{{ $pinkel->kelompok->d->kades }}</b>
                    </u>
                    @if ($pinkel->kelompok->d->nip)
                        <div><small>NIP. {{ $pinkel->kelompok->d->nip }}</small></div>
                    @endif
                </td>
            </tr>
        </table>
    </main>
</body>
