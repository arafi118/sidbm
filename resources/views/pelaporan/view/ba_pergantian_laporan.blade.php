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

    $saldo_excel = 0;
    if ($kom_aset->saldo_awal) {
        $saldo_excel = floatval($kom_aset->saldo_awal->debit) - floatval($kom_aset->saldo_awal->kredit);
    }

    $saldo_revaluasi = 0;
    if ($kom_aset->saldo) {
        $saldo_revaluasi = floatval($kom_aset->saldo->debit) - floatval($kom_aset->saldo->kredit);
    }
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

        table.p tr th,
        table.p tr td,
        table.p tr td table.p tr td {
            padding: 2 4px !important;
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

        table.vtop tr td {
            vertical-align: text-top;
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
                    Dalam hal Reklasifikasi dan revaluasi cadangan kerugian piutang (CKP) menghasilkan saldo yang
                    berbeda dari pola hitung lama (menggunakan kaidah penyajian laporan eks PNPM Mandiri Perdesaan) maka
                    perbedaan nilai tersebut akan berpengaruh menambah atau mengurangi laba ditahan dan total aset
                </li>
            </ul>
        </li>
        <li>
            Hasil perhitungan ulang (revaluasi) aset per tanggal {{ Tanggal::tglLatin($tgl_pakai) }}
            <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                style="font-size: 11px;">
                <tr style="background: rgb(74, 74, 74); color: #fff;">
                    <td height="20" align="center">Laporan Lama</td>
                    <td align="center">Revaluasi</td>
                    <td align="center">Laporan Baru</td>
                </tr>
                @foreach ($rekening as $rek)
                    @php
                        $no = $loop->iteration;
                        $saldo = 0;
                        if ($rek->kom_saldo) {
                            foreach ($rek->kom_saldo as $kom_saldo) {
                                $saldo = $kom_saldo->kredit - $kom_saldo->debit;
                                if (
                                    Keuangan::startWith($rek->kode_akun, '1.') ||
                                    Keuangan::startWith($rek->kode_akun, '5.')
                                ) {
                                    $saldo = $kom_saldo->debit - $kom_saldo->kredit;
                                }
                            }
                        }

                        $saldo_awal = 0;
                        if ($rek->saldo) {
                            $saldo_awal = $rek->saldo->kredit - $rek->saldo->debit;
                            if (
                                Keuangan::startWith($rek->kode_akun, '1.') ||
                                Keuangan::startWith($rek->kode_akun, '5.')
                            ) {
                                $saldo_awal = $rek->saldo->debit - $rek->saldo->kredit;
                            }
                        }

                        $revaluasi = $saldo - $saldo_awal;
                        if ($revaluasi == 0) {
                            continue;
                        }
                    @endphp

                    <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                        <td colspan="3">{{ $rek->nama_akun }}</td>
                    </tr>
                    <tr style="background: rgb(230, 230, 230);">
                        <td align="right">
                            @if ($saldo_awal < 0)
                                Rp. ({{ number_format($saldo_awal * -1, 2) }})
                            @else
                                Rp. {{ number_format($saldo_awal, 2) }}
                            @endif
                        </td>
                        <td align="right">
                            @if ($revaluasi < 0)
                                Rp. ({{ number_format($revaluasi * -1, 2) }})
                            @else
                                Rp. {{ number_format($revaluasi, 2) }}
                            @endif
                        </td>
                        <td align="right">
                            @if ($saldo < 0)
                                Rp. ({{ number_format($saldo * -1, 2) }})
                            @else
                                Rp. {{ number_format($saldo, 2) }}
                            @endif
                        </td>
                    </tr>
                @endforeach

                @php
                    $revaluasi = $saldo_revaluasi - $saldo_excel;
                @endphp
                <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                    <td colspan="3">Total {{ $kom_aset->nama_akun }}</td>
                </tr>
                <tr style="background: rgb(230, 230, 230);">
                    <td align="right">
                        @if ($saldo_excel < 0)
                            Rp. ({{ number_format($saldo_excel * -1, 2) }})
                        @else
                            Rp. {{ number_format($saldo_excel, 2) }}
                        @endif
                    </td>
                    <td align="right">
                        @if ($revaluasi < 0)
                            Rp. ({{ number_format($revaluasi * -1, 2) }})
                        @else
                            Rp. {{ number_format($revaluasi, 2) }}
                        @endif
                    </td>
                    <td align="right">
                        @if ($saldo_revaluasi < 0)
                            Rp. ({{ number_format($saldo_revaluasi * -1, 2) }})
                        @else
                            Rp. {{ number_format($saldo_revaluasi, 2) }}
                        @endif
                    </td>
                </tr>
            </table>
        </li>
        <li>
            {!! json_decode($kec->berita_acara, true) !!}
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
