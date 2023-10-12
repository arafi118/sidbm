@php
    use App\Utils\Tanggal;

    $tgl_mou = date('Y-m-d', strtotime('-1 month', strtotime($kec->tgl_registrasi)));
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MOU</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            border-bottom: 1px solid rgb(180, 180, 180);
            padding: 4px 12px;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid rgb(180, 180, 180);
        }

        main {
            padding-top: 40px;
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
    </style>
</head>

<body>

    <header>
        <div style="color: rgb(200,200,200); font-weight: bold;">
            MEMO OF UNDERSTANDING
            <span style="float: right; color: #000; font-size: 8px; font-weight: normal; font-style: italic;">
                MOU Nomor : {{ str_pad($kec->id, '3', '0', STR_PAD_LEFT) }}/SI-DBM/{{ Tanggal::tglRomawi($tgl_mou) }}
            </span>
        </div>
    </header>

    <footer>
        <div style="float: right; font-size: 8px;">
            Page 1 - MOU SI DBM
        </div>
    </footer>

    <main>
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td style="font-weight: bold; font-size: 16px;" align="center">
                    PERJANJIAN KERJA SAMA
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; font-size: 16px;" align="center">
                    IMPLEMENTASI SISTEM INFORMASI DANA BERGULIR MASYARAKAT
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; font-size: 16px;" align="center">ANTARA</td>
            </tr>
            <tr>
                <td style="font-weight: bold; font-size: 16px;" align="center">
                    {{ strtoupper($kec->nama_lembaga_sort) }}
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; font-size: 16px;" align="center">
                    {{ strtoupper($nama_kecamatan) }}
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; font-size: 16px;" align="center">
                    DENGAN PT. ASTA BRATA TEKNOLOGI
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="justify">
                    Pada hari ini {{ Tanggal::namaHari($tgl_mou) }} Tanggal
                    {{ $keu->terbilang(Tanggal::hari($tgl_mou)) }} bulan {{ Tanggal::namaBulan($tgl_mou) }} tahun
                    {{ $keu->terbilang(Tanggal::tahun($tgl_mou)) }} telah disepakati adanya perjanjian kerja sama antara
                    :

                    <div style="font-weight: bold; font-size: 14px;">
                        PIHAK PERTAMA
                    </div>
                    <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                        style="font-size: 11px;">
                        <tr>
                            <td rowspan="4" width="4%">&nbsp;</td>
                            <td width="20%">Nama Lengkap</td>
                            <td width="2%">:</td>
                            <td>SANTOSO, S.Ag</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>Direktur Utama</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>Desa Kaponan RT. 04/02 Kecamatan Pakis Kab. Magelang</td>
                        </tr>
                        <tr>
                            <td colspan="3" align="justify">
                                Dalam hal ini bertindak untuk dan atas nama PT. Asta Brata Teknologi selanjutnya disebut
                                sebagai Pihak Pertama
                            </td>
                        </tr>
                    </table>

                    <div style="font-weight: bold; font-size: 14px;">
                        PIHAK KEDUA
                    </div>
                    <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                        style="font-size: 11px;">
                        <tr>
                            <td rowspan="4" width="4%">&nbsp;</td>
                            <td width="20%">Nama Lengkap</td>
                            <td width="2%">:</td>
                            <td>{{ $dir->namadepan }} {{ $dir->namabelakang }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{ $kec->sebutan_level_1 }} {{ $kec->nama_lembaga_sort }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>{{ $dir->alamat }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" align="justify">
                                Dalam hal ini bertindak untuk dan atas nama {{ $kec->nama_lembaga_sort }}
                                {{ $nama_kecamatan }} selanjutnya disebut sebagai Pihak Kedua.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        Dalam surat ini kedua belah pihak sepakat mengadakan perjanjian kerja sama sebagai berikut :

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" align="center" style="font-weight: bold; font-size: 14px;">Pasal 1</td>
            </tr>
            <tr>
                <td colspan="2" align="center" style="font-weight: bold; font-size: 14px;">
                    RUANG LINGKUP KERJA SAMA
                </td>
            </tr>
            <tr>
                <td width="20">1.1</td>
                <td align="justify">
                    Pihak Pertama adalah sebuah Software Company yang telah me-release software akuntansi yang diberikan
                    nama SI DBM.
                </td>
            </tr>
            <tr>
                <td>1.2</td>
                <td align="justify">
                    Pihak Kedua adalah sebuah Bumdesama Lkd yang menyelenggarakan kegiatan penyaluran dana bergulir bagi
                    kelompok-kelompok yang membutuhkan modal usaha dalam rangka pengentasan kemiskinan.
                </td>
            </tr>
            <tr>
                <td>1.3</td>
                <td align="justify">
                    Pihak kedua akan menggunakan Software Akuntansi "SI DBM" dalam pengelolaan keuangan maupun
                    pengelolaan dana bergulir.
                </td>
            </tr>
            <tr>
                <td>1.4</td>
                <td align="justify">
                    SI DBM yang digunakan dalam perjanjian ini adalah versi 4.0 dengan sistem manajemen keuangan double
                    entry berbasis accrual berpedoman kepada Kepmendesa Nomor 136/2022.
                </td>
            </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" align="center" style="font-weight: bold; font-size: 14px;">Pasal 2</td>
            </tr>
            <tr>
                <td colspan="2" align="center" style="font-weight: bold; font-size: 14px;">
                    KEWAJIBAN
                </td>
            </tr>
            <tr>
                <td width="20">2.1</td>
                <td align="justify">
                    Pihak Pertama berkewajiban untuk :
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <ol>
                        <li>
                            Menyediakan aplikasi SI DBM Full Version yang dapat diakses secara online 24 jam dalam
                            sehari dan 7 hari dalam seminggu oleh Pihak Kedua melalui nama domain yang telah diberikan
                            sebagaimana point (b).
                        </li>
                        <li>
                            Memberikan domain dan hosting dengan spesifikasi sebagai berikut :
                            <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                                style="font-size: 11px;">
                                <tr>
                                    <td width="80">Nama Domain</td>
                                    <td width="10">:</td>
                                    <td>{{ $kec->web_kec }}</td>
                                </tr>
                                <tr>
                                    <td>Space</td>
                                    <td>:</td>
                                    <td>100GB</td>
                                </tr>
                            </table>
                        </li>
                    </ol>
                </td>
            </tr>
        </table>
    </main>
</body>

</html>
