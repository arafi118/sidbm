@php
use App\Utils\Keuangan;
$keuangan = new Keuangan;
@endphp

@extends('pelaporan.layout.base')

@section('content')
<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td colspan="3" align="center">
            <div style="font-size: 18px;">
                <b>CATATAN ATAS LAPORAN KEUANGAN</b>
            </div>
            <div style="font-size: 18px; text-transform: uppercase;">
                <b>{{ $kec->nama_lembaga_sort }}</b>
            </div>
            <div style="font-size: 16px;">
                <b>{{ strtoupper($sub_judul) }}</b>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="3" height="5"></td>
    </tr>
</table>

<ol style="list-style: upper-alpha;">
    <li>
        <div style="text-transform: uppercase;">Gambaran Umum</div>
        <div style="text-align: justify">
            {{ $kec->nama_lembaga_sort }} adalah Badan Usaha yang didirikan dari transformasi UPK PNPM-MPd
            dengan
            kegiatan usaha Dana Bergulir Masyarakat (DBM) melalui produk usahanya SPP dan UEP. Dalam
            perkembangannya sebagian dari laba DBM UPK PNPM-MPd kemudian sebelum
            ditetapkannya PP 11 tahun 2021 telah digunakan untuk membentuk unit usaha Perdagangan dan
            Produksi*.
        </div>
        <p style="text-align: justify">
            Bumdesma Lkd setelah didirikan sesuai ketentuan PP 11 tahun 2021 dilaksanakan transformasi
            sesuai Permendesa
            PDTT Nomor 15 tahun 2021 yang meliputi pengalihan aset, pengalihan kelembagaan, pengalihan
            personil, dan
            pengalihan kegiatan usaha. Modal awal Pendirian Bumdesma Lkd sesuai dengan ketentuan tersebut
            adalah berasal
            dari keseluruhan pengalihan keseluruhan aset DBM Eks PNPM MPd (Permendesa PDTT 15 tahun 2021
            Pasal 5) yang
            dicatat sebagai Ekuitas Bumdesma Lkd ditambah dengan Penyertaan Modal Desa. Yang kemudian
            didalam laporan
            posisi keuangan ekuitas yang berasal dari Aset DBM Eks PNPM Mpd disebut Modal Masyarakat Desa
            (Permendesa
            PDTT 15 tahun 2021 Pasal 6).
        </p>
        <p style="text-align: justify">
            Sesuai dengan ketentuan UU Cipta Kerja No 11 Tahun 2020 bahwa Menetapkan status Badan hukum BUM
            Desa pada
            ketentuan Pasal 117 "bahwa Badan Usaha Milik Desa yang selanjutnya disebut BUM Desa adalah Badan
            hukum yang
            didirikan oleh desa dan atau bersama desa-desa guna mengelola usaha, memanfaatkan aset,
            mengembangkan
            investasi dan produktivitas, menyediakan jasa pelayanan, dan atau jenis usaha lainnya untuk
            sebesar-besarnya
            kesejahteraan masyarakat desa." Status inilah yang menjadi dasar hukum pelaksanaan usaha
            didirikan dengan
            kegiatan Usaha Utama DBM.
        </p>
        <p style="text-align: justify">
            {{ $kec->nama_lembaga_sort }} didirikan di {{ $kec->nama_kec }} berdasarkan PERATURAN BERSAMA
            KEPALA DESA
            NOMOR ........ TAHUN 20.... dan mendapatkan Sertifikat Badan Hukum dari Menteri Hukum dan Hak
            Asasi Manusia
            No. {{ $kec->nomor_bh }} tanggal ............. . {{ $kec->nama_lembaga_sort }}
            menjalankan usaha
            pinjaman Dana Bergulir Masyarakat yang masuk dalam kategori usaha mikrofinance dan berdomisili
            di {{ $kec->nama_kec }}
            dengan perangkat organisasi sebagai berikut:

            <table style="margin-top: -10px; margin-left: 15px;">
                <tr>
                    <td style="padding: 0px; 4px;" width="60">Pengawas</td>
                    <td style="padding: 0px; 4px;">:</td>
                    <td style="padding: 0px; 4px;">.................................</td>
                </tr>
                <tr>
                    <td style="padding: 0px; 4px;">Direktur</td>
                    <td style="padding: 0px; 4px;">:</td>
                    <td style="padding: 0px; 4px;">.................................</td>
                </tr>
                <tr>
                    <td style="padding: 0px; 4px;">Sekretaris</td>
                    <td style="padding: 0px; 4px;">:</td>
                    <td style="padding: 0px; 4px;">.................................</td>
                </tr>
                <tr>
                    <td style="padding: 0px; 4px;">Bendahara</td>
                    <td style="padding: 0px; 4px;">:</td>
                    <td style="padding: 0px; 4px;">.................................</td>
                </tr>
                <tr>
                    <td style="padding: 0px; 4px;">Unit Usaha</td>
                    <td style="padding: 0px; 4px;">:</td>
                    <td style="padding: 0px; 4px;">.................................</td>
                </tr>
            </table>
        </p>
    </li>
    <li>
        <div style="text-transform: uppercase;">
            Ikhtisar Kebijakan Akutansi
        </div>
        <ol>
            <li>Pernyataan Kepatuhan</li>
            <li>
                Laporan keuangan disusun menggunakan Standar Akuntansi Keuangan
                Perusahaan Jasa Keuangan
            </li>
            <li>Dasar Penyusunan Kepmendesa 136 Tahun 2022</li>
            <li>
                Dasar penyusunan laporan keuangan adalah biaya historis dan
                menggunakan asumsi dasar akrual. Mata uang penyajian yang digunakan untuk menyusun laporan
                keuangan ini adalah Rupiah.
            </li>
            <li>Piutang Usaha</li>
            <li>
                Piutang usaha disajikan sebesar jumlah saldo pinjaman dikurangi
                dengan cadangan kerugian pinjaman
            </li>
            <li>Aset Tetap (berwujud dan tidak berwujud)</li>
            <li>
                Aset tetap dicatat sebesar biaya perolehannya jika aset
                tersebut dimiliki secara hukum oleh Bumdesma Lkd Aset
                tetap disusutkan menggunakan metode garis lurus tanpa nilai residu.
            </li>
            <li>Pengakuan Pendapatan dan Beban</li>
            <li>
                Jasa piutang kelompok dan lembaga lain yang sudah memasuki
                jatuh tempo pembayaran diakui sebagai pendapatan meskipun tidak diterbitkan kuitansi sebagai
                bukti pembayaran jasa piutang. Sedangkan denda keterlambatan pembayaran/pinalti diakui
                sebagai pendapatan pada saat diterbitkan kuitansi pembayaran.
            </li>
            <li>
                Adapun kewajiban bayar atas kebutuhan operasional, pemasaran
                maupun non operasional pada suatu periode operasi tertentu sebagai akibat
                telah menikmati manfaat/menerima fasilitas, maka hal tersebut sudah wajib diakui
                sebagai beban meskipun belum diterbitkan kuitansi pembayaran.
            </li>
            <li>Pajak Penghasilan</li>
            <li>
                Pajak Penghasilan mengikuti ketentuan perpajakan yang berlaku di Indonesia
            </li>
        </ol>
    </li>
    <li>
        <div style="text-transform: uppercase;">
            Informasi Tambahan Laporan Keuangan
        </div>
        <div>
            <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                <tr>
                    <td colspan="3" height="5"></td>
                </tr>
                <tr style="background: #000; color: #fff;">
                    <td width="30">Kode</td>
                    <td width="300">Nama Akun</td>
                    <td align="right">Saldo</td>
                </tr>
                <tr>
                    <td colspan="3" height="2"></td>
                </tr>

                @foreach ($akun1 as $lev1)
                @php
                $sum_akun1 = 0;
                @endphp
                <tr style="background: rgb(74, 74, 74); color: #fff;">
                    <td height="20" colspan="3" align="center">
                        <b>{{ $lev1->kode_akun }}. {{ $lev1->nama_akun }}</b>
                    </td>
                </tr>
                @foreach ($lev1->akun2 as $lev2)
                <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                    <td>{{ $lev2->kode_akun }}.</td>
                    <td colspan="2">{{ $lev2->nama_akun }}</td>
                </tr>

                @foreach ($lev2->akun3 as $lev3)
                @php
                $sum_saldo = 0;
                $akun_lev4 = [];
                @endphp

                @foreach($lev3->rek as $rek)

                @php
                $saldo = $keuangan->Saldo($tgl_kondisi, $rek->kode_akun);
                if ($rek->kode_akun == '3.2.02.01') {
                $saldo = $keuangan->surplus($tgl_kondisi);
                }

                $sum_saldo += $saldo;

                $akun_lev4[] = [
                "kode_akun" => $rek->kode_akun,
                "nama_akun" => $rek->nama_akun,
                "saldo" => $saldo
                ];
                @endphp
                @endforeach

                @php
                $bg = 'rgb(230, 230, 230)';
                if ($loop->iteration % 2 == 0) {
                $bg = 'rgba(255, 255, 255)';
                }

                if ($lev1->lev1 == '1') {
                $debit += $sum_saldo;
                } else {
                $kredit += $sum_saldo;
                }

                $sum_akun1 += $sum_saldo;
                @endphp

                <tr style="background: {{ $bg }};">
                    <td>{{ $lev3->kode_akun }}.</td>
                    <td>{{ $lev3->nama_akun }}</td>
                    <td align="right">{{ number_format($sum_saldo, 2) }}</td>
                </tr>

                @foreach ($akun_lev4 as $lev4)
                <tr style="background: {{ $bg }};">
                    <td>{{ $lev4['kode_akun'] }}.</td>
                    <td>{{ $lev4['nama_akun'] }}</td>
                    <td align="right">{{ number_format($lev4['saldo'], 2) }}</td>
                </tr>

                @endforeach
                @endforeach
                @endforeach

                <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                    <td height="20" colspan="2" align="left">
                        <b>Jumlah {{ $lev1->nama_akun }}</b>
                    </td>
                    <td align="right">{{ number_format($sum_akun1, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" height="2"></td>
                </tr>

                @endforeach
                <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                    <td height="20" colspan="2" align="left">
                        <b>Jumlah Liabilitas + Ekuitas </b>
                    </td>
                    <td align="right">{{ number_format($kredit, 2) }}</td>
                </tr>
            </table>
        </div>
    </li>
    <li>
        <div style="text-transform: uppercase;">
            Ketentuan Pembagian Laba Usaha
        </div>
        <ol>
            <li>
                Pembagian atas laba usaha dibagi menjadi Laba dibagikan dan laba ditahan sesuai dengan ketentuan pada
                Permendesa PDTT nomor 15 tahun 2021 yaitu:
                <ol style="list-style: lower-latin;">
                    <li>
                        Hasil usaha yang dibagikan paling sedikit terdiri atas: bagian milik bersama masyarakat Desa;
                        dan bagian Desa;
                    </li>
                    <li>
                        Besaran masing-masing bagian dihitung berdasarkan persentase penyertaan modal dan dituangkan
                        dalamanggaran dasar.
                    </li>
                    <li>
                        <div>Bagian Desa;</div>
                        <table style="margin-left: 10px;">
                            @foreach ($kec->desa as $desa)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $desa->sebutan_desa->sebutan_desa }} {{ $desa->nama_desa }}</td>
                                <td>:</td>
                                <td>........................................</td>
                            </tr>
                            @endforeach
                        </table>
                    </li>
                    <li>
                        <div>Bagian milik bersama masyarakat Desa digunakan untuk:</div>
                        <table style="margin-left: 10px;">
                            <tr>
                                <td>1. </td>
                                <td>Kegiatan sosial kemasyarakatan dan bantuan rumah tangga miskin .....%</td>
                            </tr>
                            <tr>
                                <td>2. </td>
                                <td>
                                    Pengembangan kapasitas kelompok simpan pinjam perempuan/usaha ekonomi produktif
                                    .....%
                                </td>
                            </tr>
                            <tr>
                                <td>3. </td>
                                <td>
                                    Pelatihan masyarakat, dan kelompok pemanfaat umum .....%
                                </td>
                            </tr>
                        </table>
                    </li>
                </ol>
            </li>
            <li>
                <div>Laba Ditahan</div>
                <ol style="list-style: lower-latin;">
                    <li>
                        Laba Ditahan untuk Peningkatan Modal DBM Usaha ....................., .....% Rp.
                        .........................
                    </li>
                    <li>
                        Laba Ditahan untuk Penambahan Investasi Usaha ....................., .....% Rp.
                        .........................
                    </li>
                    <li>
                        Laba Ditahan untuk Pendirian Unit Usaha ....................., .....% Rp.
                        .........................
                    </li>
                </ol>
            </li>
        </ol>
    </li>
    <li>
        <div style="text-transform: uppercase;">
            Penutup
        </div>
        <div style="text-align: justify">
            Laporan Keuangan {{ $kec->nama_lembaga_sort }} ini disajikan dengan berpedoman pada Keputusan Kementerian
            Desa Nomor 136/2022 Tentang Panduan Penyusunan Pelaporan Bumdes. Dimana yang dimaksud Bumdes yang
            dimaksud dalam Keputusan Kementerian Desa adalah meliputi Bumdes, Bumdesma dan Bumdesma Lkd. Catatan atas
            Laporan Keuangan (CaLK) ini merupakan bagian tidak terpisahkan dari Laporan Keuangan Badan Usaha Milik Desa
            Bersama {{ $kec->nama_lembaga_sort }} untuk Laporan Operasi {{ $nama_tgl }}. Selanjutnya Catatan atas
            Laporan Keuangan ini diharapkan untuk dapat berguna bagi pihak-pihak yang berkepentingan (stakeholders)
            serta memenuhi prinsip-prinsip transparansi, akuntabilitas, pertanggungjawaban, independensi, dan fairness
            dalam pengelolaan keuangan {{ $kec->nama_lembaga_sort }}.
        </div>
    </li>
</ol>
@endsection
