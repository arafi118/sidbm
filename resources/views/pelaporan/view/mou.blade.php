@php
    use App\Utils\Tanggal;

    $tgl_mou = $kec->tgl_registrasi;
    $jumlah = $kec->id == '340' ? 15000000 : 12500000;
    if (strtotime($kec->tgl_register) >= strtotime('2024-09-01')) {
        $jumlah = 15000000;
    }
    $nominal = $kec->biaya_tahunan;
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
            font-size: 12px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            border-bottom: 1px solid rgb(180, 180, 180);
            padding: 4px 12px;
        }

        footer {
            font-size: 12px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid rgb(180, 180, 180);
        }

        main {
            top: 40px;
            font-size: 12px;
            position: relative;
            padding-bottom: 37.79px;
        }

        table tr th,
        table tr td {
            padding: 2px 4px;
        }

        table.p tr th,
        table.p tr td {
            padding: 2px 4px;
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

        .page:before {
            content: counter(page);
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
        <div style="text-align: right; font-size: 8px;" id="page-number">
            Page <span class="page"></span> - MOU SI DBM
        </div>
    </footer>

    <main>
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
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
        </table>

        <div style="text-align: justify;">
            Pada hari ini {{ Tanggal::namaHari($tgl_mou) }} Tanggal
            {{ $keu->terbilang(Tanggal::hari($tgl_mou)) }} bulan {{ Tanggal::namaBulan($tgl_mou) }} tahun
            {{ $keu->terbilang(Tanggal::tahun($tgl_mou)) }} telah disepakati adanya perjanjian kerja sama antara
            :
        </div>

        <div style="font-weight: bold; font-size: 14px;">
            PIHAK PERTAMA
        </div>
        <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
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
        <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
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

        <p style="text-align: justify;">
            Dalam surat ini kedua belah pihak sepakat mengadakan perjanjian kerja sama sebagai berikut :
        </p>

        <div style="text-align: center;">
            <div><b style="font-size: 14px;">PASAL 1</b></div>
            <div><b style="font-size: 14px;">RUANG LINGKUP KERJA SAMA</b></div>

            <ol style="text-align: justify;">
                <li>
                    Pihak Pertama adalah sebuah Software Company yang telah me-release software akuntansi yang diberikan
                    nama SI DBM.
                </li>
                <li>
                    Pihak Kedua adalah sebuah Bumdesama Lkd yang menyelenggarakan kegiatan penyaluran dana bergulir bagi
                    kelompok-kelompok yang membutuhkan modal usaha dalam rangka pengentasan kemiskinan.
                </li>
                <li>
                    Pihak kedua akan menggunakan Software Akuntansi "SI DBM" dalam pengelolaan keuangan maupun
                    pengelolaan dana bergulir.
                </li>
                <li>
                    SI DBM yang digunakan dalam perjanjian ini adalah versi 4.0 dengan sistem manajemen keuangan double
                    entry berbasis accrual berpedoman kepada Kepmendesa Nomor 136/2022.
                </li>
            </ol>
        </div>

        <div style="text-align: center;">
            <div><b style="font-size: 14px;">PASAL 2</b></div>
            <div><b style="font-size: 14px;">KEWAJIBAN</b></div>

            <ol style="text-align: justify;">
                <li>
                    Pihak Pertama berkewajiban untuk :
                    <ul style="list-style: lower-alpha;">
                        <li>
                            Menyediakan aplikasi SI DBM Full Version yang dapat diakses secara online 24 jam dalam
                            sehari dan 7 hari dalam seminggu oleh Pihak Kedua melalui nama domain yang telah
                            diberikan
                            sebagaimana point (b).
                        </li>
                        <li>
                            Memberikan domain dan hosting dengan spesifikasi sebagai berikut :
                            <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                                style="font-size: 12px;">
                                <tr>
                                    <td width="90">Nama Domain</td>
                                    <td width="10">:</td>
                                    <td>
                                        <b>{{ Request::getHost() }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Space</td>
                                    <td>:</td>
                                    <td>
                                        <b>100Gb</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bandwith</td>
                                    <td>:</td>
                                    <td>
                                        <b>Unlimited</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>MySql</td>
                                    <td>:</td>
                                    <td>
                                        <b>Unlimited</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Register</td>
                                    <td>:</td>
                                    <td>
                                        <b>{{ Tanggal::tglLatin($kec->tgl_registrasi) }}</b>
                                    </td>
                                </tr>
                            </table>
                        </li>
                        <li>
                            Melakukan migrasi data dari laporan excel ke aplikasi SI DBM pada awal masa implementasi.
                        </li>
                        <li>
                            Memberikan username dan password kepada seluruh operator aplikasi Pihak Kedua
                        </li>
                        <li>
                            Memberikan pelatihan (training) 1 (satu) kali pada awal kerja sama selama 2 (dua) hari
                            dengan durasi 8 jam per hari, kepada maksimal 8 orang calon operator Pihak Kedua meliputi
                            unsur PO Bumdesma, Pengelola
                            Perguliran, Pengawas dan Verifikator yang akan menggunakan SI DBM di tempat pelatihan yang
                            ditentukan
                            oleh pihak Kedua. Apabila pelatihan dilakukan di luar kota alamat Pihak Pertama, maka Pihak
                            kedua
                            menanggung biaya transportasi dan akomodasi team trainer dari Pihak Pertama.
                        </li>
                        <li>
                            Memberikan layanan maintenance dan backup data secara berkala.
                        </li>
                        <li>
                            Mengadakan ujian Certified SI DBM bagi para operator yang telah mengikuti training
                        </li>
                        <li>
                            Memberikan Support setiap ada kendala penggunaan melalui WA, Telpon, E-mail, Facebook.
                        </li>
                        <li>
                            Menginformasikan versi upgrade jika telah di-release versi yang terbaru.
                        </li>
                        <li>
                            Memberikan jaminan bebas menggunakan SI DBM secara legal dan sah dan bebas dari tuntutan
                            pihak manapun
                        </li>
                    </ul>
                </li>
                <li>
                    Pihak Kedua berkewajiban untuk :
                    <ul style="list-style: lower-alpha;">
                        <li>
                            Menggunakan SI DBM sebagai software aplikasi dalam pengelolaan keuangan dan dana bergulir
                            pihak
                            Kedua dengan sebagaimana mestinya dan dengan rasa penuh tanggung jawab.
                        </li>
                        <li>
                            Menyampaikan laporan keuangan dalam bentuk softcopy excel untuk bahan migrasi kedalam
                            database SI DBM.
                        </li>
                        <li>
                            Menyediakan sarana hardware yang akan digunakan untuk mengakses aplikasi seperti komputer
                            desktop, laptop, printer dan jaringan internet.
                        </li>
                    </ul>
                </li>
            </ol>
        </div>

        <div style="text-align: center;">
            <div><b style="font-size: 14px;">PASAL 3</b></div>
            <div><b style="font-size: 14px;">H A K</b></div>

            <ol style="text-align: justify;">
                <li>
                    Pihak Pertama mempunyai hak :
                    <ul style="list-style: lower-alpha;">
                        <li>
                            Menerima masukan dan saran perbaikan fitur SI DBM sesuai dengan regulasi yang mengatur
                            Bumdesma
                            Lkd khususnya yang berkaitan dengan pengaturan pelaporan keuangan Bumdesma Lkd.
                        </li>
                        <li>
                            Memberikan sertifikat pelatihan bagi peserta yang mengikuti Ujian Sertifikasi SI DBM.
                        </li>
                        <li>
                            Menerima pembayaran instalasi dan migrasi data satu kali untuk selama pemakaian aplikasi
                            oleh Pihak
                            Kedua
                        </li>
                        <li>
                            Menerima pembayaran perpanjangan domain dan hosting satu kali dalam setahun.
                        </li>
                    </ul>
                </li>
                <li>
                    Pihak Kedua mempunyai hak :
                    <ul style="list-style: lower-alpha;">
                        <li>
                            Menggunakan SI DBM sebagai software aplikasi untuk pengelolaan keuangan dan manajemen
                            Bumdesma
                            Lkd sesuai regulasi yang berlaku secara nasional.
                        </li>
                        <li>
                            Mendapatkan Domain dan Hosting sesuai spesifikasi sebagaimana pasal 2 ayat 1 point (b).
                        </li>
                        <li>
                            Mendapatkan Support setiap ada kendala penggunaan melalui WA, Telpon, E-mail, Facebook.
                        </li>
                        <li>
                            Mendapatkan informasi mengenai release SI DBM yang terbaru dari Pihak Pertama, dan informasi
                            terbaru
                            tentang SI DBM dan perkembangannya
                        </li>
                        <li>
                            Memperoleh jaminan bebas menggunakan SI DBM secara legal dan sah dan bebas dari tuntutan
                            pihak
                            manapun.
                        </li>
                    </ul>
                </li>
            </ol>
        </div>

        <div style="text-align: center;">
            <div><b style="font-size: 14px;">PASAL 4</b></div>
            <div><b style="font-size: 14px;">BIAYA</b></div>

            <ol style="text-align: justify;">
                <li>
                    Pihak Kedua membayar biaya instalasi dan migrasi data pada server sebesar Rp.
                    {{ number_format($jumlah) }} ({{ $keu->terbilang($jumlah) }} Rupiah) serta dikenai PPn sebesar 10%
                    dengan
                    tahapan pembayaran 30% sebelum migrasi data
                    dan
                    tahap kedua 70% dibayarkan maksimal 1 (satu) minggu setelah Bimbingan Teknis.
                </li>
                <li>
                    Pihak Kedua membayar biaya domain dan hosting dengan spesifikasi sebagaimana pasal 2 ayat 1 point
                    (b)
                    sebesar Rp {{ number_format($nominal) }} ({{ $keu->terbilang($nominal) }} Rupiah) per tahun yang
                    dibayarkan setiap awal masa aktif.
                </li>
                <li>
                    Perpanjangan domain dan hosting dilakukan setiap 1 (satu) tahun sekali dengan biaya sebagaimana
                    tersebut
                    pada pasal 4 ayat 2 biaya domain dan hosting akan berubah sewaktu-waktu.
                </li>
                <li>
                    Pihak kedua membayar biaya migrasi ulang sebesar Rp. 1.000.000,- (Satu juta rupiah) apabila Pihak
                    Kedua
                    mengajukan permintaan migrasi ulang untuk mengatasi ketidaksesuaian data maupun kekosongan data
                    akibat
                    kelalaian/kesengajaan tidak dilakukan input transaksi oleh Pihak Kedua dalam segala sebabnya.
                </li>
            </ol>
        </div>

        <div style="text-align: center;">
            <div><b style="font-size: 14px;">PASAL 5</b></div>
            <div><b style="font-size: 14px;">JANGKA WAKTU PERJANJIAN</b></div>

            <ol style="text-align: justify;">
                <li>
                    Perjanjian kerja sama ini berlaku sejak ditandatangani dan berlaku dalam jangka waktu yang tidak
                    ditentukan.
                </li>
                <li>
                    Surat perjanjian ini dapat diperpanjang dan di-revisi sesuai dengan kesepakatan Kedua Belah Pihak
                    secara
                    tertulis dengan membuat surat perjanjian lanjutan dan/ atau pengganti.
                </li>
            </ol>
        </div>

        <div style="text-align: center;">
            <div><b style="font-size: 14px;">PASAL 6</b></div>
            <div><b style="font-size: 14px;">PERSELISIHAN DAN KESINAMBUNGAN KERJA SAMA</b></div>

            <ol style="text-align: justify;">
                <li>
                    Apabila terjadi perselisihan dan/atau kesalahpahaman diantara kedua belah pihak, maka diprioritaskan
                    dilakukan
                    penyelesaian secara kekeluargaan, sebelum dilakukan penyelesaian melaui jalur hukum.
                </li>
                <li>
                    Apabila terjadi kehilangan kemampuan dan atau pembaharuan manajemen PT. Asta Brata Teknologi dalam
                    menjalankan fungsi manajemen dan layanan kepada User SI DBM, maka sebagai bagian proses likuidasi
                    dan
                    atau pembaharuan manajemen PT. Asta Brata Teknologi berkewajiban melakukan pengalihan kewajiban
                    layanan
                    User SI DBM kepada pihak yang berkompeten dengan persetujuan kedua belah pihak.
                </li>
            </ol>
        </div>

        <div style="text-align: center;">
            <div><b style="font-size: 14px;">PASAL 7</b></div>
            <div><b style="font-size: 14px;">PENUTUP</b></div>

            <div style="text-align: justify;">
                Apabila ada hal-hal mendesak yang harus dilakukan dalam rangka meningkatkan kerja sama ini, maka akan
                diatur
                dikemudian hari oleh para pihak dan akan dituangkan dalam addendum perjanjian yang tidak terpisahkan
                dari
                perjanjian kerja sama ini.
            </div>
        </div>

        <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td width="50%">&nbsp;</td>
                <td width="50%" align="center">{{ $kec->nama_kec }}, {{ Tanggal::tglLatin($tgl_mou) }}</td>
            </tr>
            <tr>
                <td align="center">Pihak Pertama,</td>
                <td align="center">Pihak Kedua,</td>
            </tr>
            <tr>
                <td colspan="2" height="40">&nbsp;</td>
            </tr>
            <tr>
                <td align="center">
                    <b>SANTOSO, S.Ag</b>
                </td>
                <td align="center">
                    <b>{{ $dir->namadepan }} {{ $dir->namabelakang }}</b>
                </td>
            </tr>

            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" align="center">Saksi:</td>
            </tr>
            <tr>
                <td colspan="2" height="40">&nbsp;</td>
            </tr>
            <tr>
                <td align="center">
                    .......................................
                </td>
                <td align="center">..................................................</td>
            </tr>
        </table>
    </main>
</body>

</html>
