@php
    use App\Utils\Tanggal;
    use App\Utils\Keuangan;
    $keuangan = new Keuangan();

    $tgl_pakai = $kec->tgl_pakai;
    $minimal_pakai = '2023-01-01';
    if (strtotime($tgl_pakai) < strtotime($minimal_pakai)) {
        $tgl_pakai = $minimal_pakai;
    }

    $nama_hari = Tanggal::namaHari($tgl_pakai);
    $nama_bulan = Tanggal::namaBulan($tgl_pakai);
    $hari = $keuangan->terbilang(Tanggal::hari($tgl_pakai));
    $bulan = $keuangan->terbilang(Tanggal::bulan($tgl_pakai));
    $tahun = $keuangan->terbilang(Tanggal::tahun($tgl_pakai));

    $penggolongan_inventaris = [
        'Tanah dengan umur ekonomis selamanya',
        'Gedung dengan umur ekonomis 20 tahun',
        'Kendaraan dengan umur ekonomis 10 tahun',
        'Komputer dan pendukungnya dengan umur ekonomis 4 tahun',
        'Peralatan umum dengan umur ekonomis 5 tahun.',
    ];

    $data_ati = [];
    foreach ($aset_tetap as $a1) {
        $saldo = $keuangan->komSaldo($a1);
        foreach ($a1->trx_kredit as $kredit) {
            $saldo += $kredit->jumlahl;
        }

        $data_ati[] = $saldo;
    }

    $data_atb = [];
    foreach ($aset_tak_berwujud as $a2) {
        $saldo = $keuangan->komSaldo($a2);
        foreach ($a2->trx_kredit as $kredit) {
            $saldo += $kredit->jumlahl;
        }

        $data_atb[] = $saldo;
    }

    $data_ckp = [];
    foreach ($cadangan_piutang as $cad) {
        $saldo = $keuangan->komSaldo($cad);
        foreach ($cad->trx_debit as $debit) {
            $saldo -= $debit->jumlahl;
        }

        $data_ckp[] = $saldo;
    }

    $ati = ['Gedung', 'Kendaraan', 'Komputer dan pendukungnya dan Peralatan umum (Inventaris)'];
    $atb = ['Perijinan ', 'Lisensi', 'Sewa'];
    $ckp = ['Kelompok SPP', 'Kelompok UEP'];
@endphp

<!DOCTYPE html>
<html lang="en" translate="no">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BERITA ACARA PERUBAHAN FORMAT PELAPORAN DAN MIGRASI DATA KEUANGAN</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        html {
            font-size: 12px;
            margin: 75.59px;
            margin-left: 94.48px;
        }

        ol,
        ul {
            margin-left: unset;
            text-align: justify;
            margin-top: 0;
        }

        ul li::before,
        ol li::before {
            content: attr(data-icon);
        }

        table tr th,
        table tr td,
        table tr td table.p tr td {
            padding: 0 4px !important;
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
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>BERITA ACARA</b>
                </div>
                <div style="font-size: 16px; text-transform: uppercase;">
                    <b>PERUBAHAN FORMAT PELAPORAN DAN MIGRASI DATA KEUANGAN</b>
                </div>
                <div style="font-size: 16px; text-transform: uppercase;">
                    <b>{{ strtoupper($kec->nama_lembaga_sort) }}</b>
                </div>
                <div style="font-size: 12px;">
                    Nomor : ................................................
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>

    <p style="text-align: justify;">
        Mendasar kepada Keputusan Menteri Desa Pembangunan Daerah Tertinggal dan Transmigrasi Nomor 136 tahun 2022
        tentang Panduan Pelaporan Keuangan Bumdes, Bumdesa dan Bumdema Lkd maka pada hari ini {{ $nama_hari }}
        tanggal {{ $hari }} bulan {{ $nama_bulan }} tahun {{ $tahun }} telah dilakukan migrasi data
        keuangan Bumdesma {{ $kec->nama_lembaga_sort }} dari Format Pelaporan dan
        tata cara penyajian menggunakan pola dan tata cara mendasar pada Format pelaporan eks PNPM Mandiri Perdesaan ke
        dalam dan menggunakan Format dan tata cara penyajian laporan keuangan berdasar Kepmendesa PDTT Nomor 136 Tahun
        2022.
    </p>
    <p style="text-align: justify; margin-bottom: 0; ">
        Berkaitan dengan proses migrasi tersebut diatas telah dilakukan penyesuaian data keuangan menggunakan kaidah
        reklasifikasi dan revaluasi aset sesuai aturan ketentuan dalam Kepemdesa PDTT nomor 136 Tahun 2022 dengan
        rincian penyesuaian sebagai berikut sebagai berikut:
    </p>

    <ol>
        <li>
            Dilakukan pemisahan, penggantian nama akun (reklasifikasi) dan koreksi beban penyusutan (revaluasi asset)
            berdasarkan norma penyusutan terkait dengan akun Biaya dibayar dimuka, Inventaris dan aktiva tetap, dengan
            rincian sebagai berikut :
            <ul style="list-style: lower-alpha;">
                <li>
                    Nama Akun dan penggolongan/kategori serta dengan umur ekonomis menjadi ;
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
                        @foreach ($penggolongan_inventaris as $pi => $val)
                            <tr>
                                <td width="7%" align="right">{{ $loop->iteration }})</td>
                                <td>{{ $val }}</td>
                            </tr>
                        @endforeach
                    </table>
                </li>
                <li>
                    Total Perolehan Aset Tetap dan Aset tidak berwujud sesuai dengan harga awal perolehan / diakui
                    sebagai asset UPK sebelum bertransformasi menjadi Bumdesma Lkd.
                </li>
                <li>
                    Hasil perhitungan ulang (revaluasi) akumulasi penyusutan per tanggal 1 Januari 2023 menjadi
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
                        @foreach ($ati as $pi => $val)
                            @php
                                $no = $loop->iteration;
                            @endphp
                            <tr>
                                <td width="7%" align="right">{{ $no }})</td>
                                <td>{{ $val }} Sebesar Rp. {{ number_format($data_ati[$no - 1], 2) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </li>
                <li>
                    Hasil perhitungan ulang (revaluasi) akumulasi amortisasi per tanggal 1 Januari 2023 menjadi;
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
                        @foreach ($atb as $pi => $val)
                            @php
                                $no = $loop->iteration;
                            @endphp
                            <tr>
                                <td width="7%" align="right">{{ $no }})</td>
                                <td>{{ $val }} Sebesar Rp. {{ number_format($data_atb[$no - 1], 2) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </li>
                <li>
                    Dalam hal Reklasifikasi dan revaluasi asset tetap dan inventaris serta asset tak berwujud
                    menghasilkan saldo perolehan dan akumulasi menyusutan yang berbeda dari pola hitung lama
                    (menggunakan kaidah penyajian laporan eks PNPM Mandiri Perdesaan) maka perbedaan nilai tersebut akan
                    berpengaruh menambah atau mengurangi saldo laba ditahan dan selanjutnya ke total aset.
                </li>
            </ul>
        </li>
        <li>
            Memisahkan nominal Cadangan Resiko Pinjaman dari Surplus ditahan dan/ atau memperhitungkan ulang nilai
            resiko berdasarkan kolektibilitas dengan rincian sebagai berikut :
            <ul style="list-style: lower-alpha;">
                <li>
                    Cadangan Resiko dilakukan reklasifikasi asset sehingga penyajiannya dicatatkan/diakui sebagai
                    beban penyisihan kerugian piutang dalam akun <b>Cadangan Kerugian Piutang (CKP)</b> yang harus
                    <b>dipindahkan</b> penyajiannya dari posisi/kategori utang cadangan resiko atau surplus ditahan
                    kedalam <b>kategori Aset dengan saldo minus (-).</b>
                </li>
                <li>
                    Berkaitan dengan poin 2.c sesuai kaidah/system akuntansi berbasis akrual yang diterapkan dalam
                    Kepmendesa PDTT Nomor 136 Tahun 2022 Cadangan Kerugian Piutang (CKP) diakui sebagai transaksi beban
                    penyisihan kerugian piutang saat nominal itu diungkapkan sesuai parameter dan kriteria
                    perhitungannya.
                </li>
                <li>
                    Adapun besaran Cadangan Kerugian Piutang (CKP) yang diakui dalam proses migrasi ini adalah :
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
                        @foreach ($ckp as $pi => $val)
                            @php
                                $no = $loop->iteration;
                            @endphp
                            <tr>
                                <td width="7%" align="right">{{ $no }})</td>
                                @if ($data_ckp[$no - 1] < 0)
                                    <td>{{ $val }} Sebesar Rp.
                                        ({{ number_format($data_ckp[$no - 1] * -1, 2) }})
                                    </td>
                                @else
                                    <td>{{ $val }} Sebesar Rp. {{ number_format($data_ckp[$no - 1], 2) }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                </li>
                <li>
                    Dalam hal Reklasifikasi dan revaluasi cadangan kerugian piutang (CKP) menghasilkan saldo yang
                    berbeda dari pola hitung lama (menggunakan kaidah penyajian laporan eks PNPM Mandiri Perdesaan) maka
                    perbedaan nilai tersebut akan berpengaruh menambah atau mengurangi laba ditahan dan total aset
                </li>
            </ul>
        </li>
        <li>
            Dalam hal didapati ada kelebihan angsuran pokok untuk sejumlah pinjman yang telah berstatus lunas dan
            mengakibatkan terjadi saldo minus yang selanjutnya berpengarus pada total saldo pinjaman se-kecamatan maka
            diakui sebagai tembahan pendapatan lain-lain yang akan dicatatkan menjadi pengurangan di saldo piutang dan
            penambahan saldo laba ditahan dan total aset.
        </li>
        <li>
            Terlampir Laporan Posisi Keuangan hasil migrasi data ke dalam Format pelaporan sesuai Kepmendesa PDTT Nomor
            136 Tahun 2022 dan Laporan Posisi Keuangan (Neraca) dan pendukungnya sesuai Format pelaporan eks PNPM
            Mandiri Perdesaan Bumdesma {{ $kec->nama_lembaga_sort }} Per tanggal {{ Tanggal::tglLatin($tgl_pakai) }}
            sebagai bagian yang tak terpisahkan dari Berita Acara ini.
        </li>
        <li>
            Penyesuaian dan migrasi data keuangan ini telah dibahas dan disepakati dalam Musyawarah Antar Desa
            Tahunan/Khusus pada hari {{ $nama_hari }} tanggal {{ $hari }} bulan {{ $nama_bulan }} tahun
            {{ $tahun }} Dan untuk menjadi pedoman dalam pencatatan,
            penatausahaan dan penyajian laporan serta periksaan laporan Bumdesma {{ $kec->nama_lembaga_sort }}
            sampai dengan diterbitkankan regulasi baru yang mengikat penyajian laporan keuangan Bumdesma Lkd secara
            nasional.
        </li>
    </ol>
    <p>
        Demikian Berita Acara ini dibuat dengan sebenar-benarnya untuk dapat digunakan sebagaimana mestinya
    </p>

    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="3" height="20">&nbsp;</td>
        </tr>
        <tr>
            <td width="60%">&nbsp;</td>
            <td width="10%">Dibuat di</td>
            <td width="30%">: {{ $kec->nama_kec }}</td>
        </tr>
        <tr>
            <td width="60%">&nbsp;</td>
            <td width="10%">Tanggal</td>
            <td width="30%">: {{ Tanggal::tglLatin($tgl_pakai) }}</td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" width="50%">{{ $kec->sebutan_level_1 }}</td>
            <td align="center" width="50%">{{ $kec->sebutan_level_3 }}</td>
        </tr>
        <tr>
            <td colspan="2" height="50">&nbsp;</td>
        </tr>
        <tr>
            <td align="center">( {{ $direktur->namadepan }} {{ $direktur->namabelakang }} )</td>
            <td align="center">( {{ $bendahara->namadepan }} {{ $bendahara->namabelakang }} )</td>
        </tr>

        <tr>
            <td colspan="2" height="20">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" colspan="2">Mengetahui:</td>
        </tr>
        <tr>
            <td align="center">{{ $kec->nama_bp_long }}</td>
            <td align="center">{{ $kec->nama_bkad_long }}</td>
        </tr>
        <tr>
            <td colspan="2" height="50">&nbsp;</td>
        </tr>
        <tr>
            <td align="center">(..............................)</td>
            <td align="center">(..............................)</td>
        </tr>
    </table>
</body>

</html>
