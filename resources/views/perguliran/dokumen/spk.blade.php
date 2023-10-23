@php
    use App\Utils\Tanggal;

    $waktu = date('H:i');
    $tempat = 'Kantor DBM';

    $wt_cair = explode('_', $pinkel->wt_cair);
    if (count($wt_cair) == 1) {
        $waktu = $wt_cair[0];
    }

    if (count($wt_cair) == 2) {
        $waktu = $wt_cair[0];
        $tempat = $wt_cair[1];
    }

    $redaksi_spk = str_replace('<ol>', '', str_replace('</ol>', '', $kec->redaksi_spk));
    $redaksi_spk = str_replace('<ul>', '', str_replace('</ul>', '', $redaksi_spk));
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>SURAT PERJANJIAN KREDIT (SPK)</b>
                </div>
                <div style="font-size: 14px;">
                    Nomor: {{ $pinkel->spk_no }}
                </div>
                <div style="font-size: 14px;">
                    Tanggal: {{ Tanggal::tglLatin($pinkel->tgl_cair) }}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>

    <div style="text-align: justify;">
        Dengan memohon rahmat Tuhan Yang Maha Kuasa serta kesadaran akan cita-cita luhur pemberdayaan masyarakat desa untuk
        mencapai kemajuan ekonomi dan kemakmuran bersama, pada hari ini {{ Tanggal::namaHari($pinkel->tgl_cair) }} tanggal
        {{ $keuangan->terbilang(Tanggal::hari($pinkel->tgl_cair)) }} bulan {{ Tanggal::namaBulan($pinkel->tgl_cair) }} tahun
        {{ $keuangan->terbilang(Tanggal::tahun($pinkel->tgl_cair)) }}, bertempat di {{ $tempat }} kami yang bertanda
        tangan dibawah ini;
    </div>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
        <tr>
            <td width="90">Nama Lengkap</td>
            <td width="10" align="center">:</td>
            <td>{{ $dir->namadepan }} {{ $dir->namabelakang }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td align="center">:</td>
            <td>{{ $kec->sebutan_level_1 }} {{ $kec->nama_lembaga_sort }}</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td align="center">:</td>
            <td>{{ $dir->nik }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td align="center">:</td>
            <td>{{ $kec->alamat_kec }}</td>
        </tr>
    </table>

    <div style="text-align: justify;">
        Bertindak untuk dan atas nama Manajemen {{ $kec->nama_lembaga_sort }} {{ $kec->sebutan_kec }}
        {{ $kec->nama_kec }} selaku pengelola Dana Bergulir Masyarakat untuk {{ $pinkel->jpp->deskripsi_jpp }}
        ({{ $pinkel->jpp->nama_jpp }}) di {{ $kec->sebutan_kec }} {{ $kec->nama_kec }}, selanjutnya disebut PIHAK
        PERTAMA, dan
    </div>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
        <tr>
            <td width="90">Nama Lengkap</td>
            <td width="10" align="center">:</td>
            <td>{{ $pinkel->kelompok->ketua }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td align="center">:</td>
            <td>Ketua Kelompok</td>
        </tr>
        <tr>
            <td>Nama Lengkap</td>
            <td align="center">:</td>
            <td>{{ $pinkel->kelompok->sekretaris }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td align="center">:</td>
            <td>Sekretaris Kelompok</td>
        </tr>
        <tr>
            <td>Nama Lengkap</td>
            <td align="center">:</td>
            <td>{{ $pinkel->kelompok->bendahara }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td align="center">:</td>
            <td>Bendahara Kelompok</td>
        </tr>
    </table>

    <div style="text-align: justify;">
        Bertindak untuk dan atas nama kelompok {{ $pinkel->jpp->nama_jpp }} {{ $pinkel->kelompok->nama_kelompok }} yang
        berkedudukan di {{ $pinkel->kelompok->alamat_kelompok }} {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
        {{ $pinkel->kelompok->d->nama_desa }} {{ $kec->sebutan_kec }} {{ $kec->nama_kec }}, dan beserta anggota yang
        memberikan kuasa secara tertulis sebagaimana Surat Kuasa terlampir sebagai bagian yang tidak terpisahkan dari
        dokumen perjanjian kredit ini, selanjutnya disebut PIHAK KEDUA.
    </div>

    <p style="text-align: justify;">
        Dalam kedudukan para pihak sebagaimana tertulis diatas, dengan sadar dan sukarela serta rasa penuh tanggung jawab
        menyatakan telah membuat surat perjanjian kredit (SPK) dengan ketentuan-ketentuan yang disepakati bersama sebagai
        berikut :
    </p>

    <div style="text-align: center;">
        <b style="font-size: 14px;">PASAL 1</b>

        <ol style="text-align: justify;">
            <li>
                Pihak Pertama setuju memberikan kredit/pinjaman kepada Pihak Kedua sebesar Rp.
                {{ number_format($pinkel->alokasi) }} ({{ $keuangan->terbilang($pinkel->alokasi) }} Rupiah) yaitu jumlah
                yang telah diputuskan dalam rapat penetapan pendanaan, berdasarkan permohonan dari Pihak Kedua dan para
                pemberi kuasa yang dilakukan secara kelompok sesuai Surat Permohonan Kredit tanggal
                {{ Tanggal::tglLatin($pinkel->tgl_cair) }}.
            </li>
            <li>
                Pihak Kedua dan Pemberi kuasa, menyatakan telah menerima uang dengan jumlah sebagaimana yang
                tertulis pada ayat 1 diatas., dan telah diterima oleh para anggota pemanfaat sesuai kelayakan kredit
                masing-masing anggota pemanfaat yang dibuktikan secara sah dengan daftar penerima dana terlampir,
                dan sekaligus berlaku sebagai Surat Pengakuan Hutang, baik bagi setiap anggota penerima manfaat
                maupun secara kelompok dalam pernyataan ketaatan tanggung-renteng.
            </li>
        </ol>
    </div>

    <div style="text-align: center;">
        <b style="font-size: 14px;">PASAL 2</b>
        <div style="text-align: justify;">
            Kedua belah Pihak secara sukarela menerima syarat-syarat perjanjian utang-piutang sebagaimana
            dinyatakan dalam ketentuan-ketentuan dibawah ini :

            <ol style="text-align: justify;">
                <li>
                    Dana Pinjaman dari {{ $kec->nama_lembaga_sort }} akan dipergunakan untuk kegiatan usaha
                    dan/atau pembiayaan hal-hal yang bermanfaat untuk meningkatkan pendapatan dan mutu kehidupan
                    keluarga guna memberikan manfaat sebesar-besarnya bagi pertumbuhan ekonomi dan kesejahteraan
                    keluarga pengurus dan anggota kelompok {{ $pinkel->kelompok->nama_kelompok }}.
                </li>
                <li>
                    Menjunjung tinggi dan ikut menyepakati hasil Musyawarah antara Desa yang telah menetapkan pinjaman
                    kelompok sebagaimana kelompok {{ $pinkel->kelompok->nama_kelompok }} adalah termasuk dalam kategori
                    kelompok yang sepakat memberikan dukungan operasional dan pengembangan kepada
                    {{ $kec->nama_lembaga_sort }} secara progresif proporsional berupa jasa pinjaman sebesar
                    {{ $pinkel->pros_jasa / $pinkel->jangka }}% {{ $pinkel->jasa->nama_jj }} per-bulan dikalikan pokok
                    pinjaman.
                </li>
                <li>
                    Kelompok menyepakati akan melakukan angsuran kredit dalam jangka waktu {{ $pinkel->jangka }}
                    ({{ $keuangan->terbilang($pinkel->jangka) }}) bulan dengan cara membayar angsuran Pokok
                    {{ $pinkel->sis_pokok->nama_sistem }} ({{ $pinkel->sis_pokok->deskripsi_sistem }}) dan angsuran jasa
                    {{ $pinkel->sis_jasa->nama_sistem }} ({{ $pinkel->sis_jasa->deskripsi_sistem }}) sebagaimana jadwal
                    angsuran terlampir yang tidak terpisahkan dari Surat Perjanjian Kredit (SPK).
                </li>
                {!! json_decode($redaksi_spk, true) !!}
            </ol>
        </div>
    </div>

    <div style="text-align: center;">
        <b style="font-size: 14px;">PASAL 3</b>

        <ol style="text-align: justify;">
            <li>
                Pihak kedua dan pemberi kuasa sadar dan mengerti bahwa mengembalikan kredit secara lancar sesuai
                jadwal yang disepakati, merupakan kewajiban hukum sekaligus menunjukkan budi pekerti luhur untuk
                mengembangkan semangat tolong menolong dengan saudaranya sesama warga desa lain.
                Pengembalian kredit secara lancar akan memperluas kesempatan untuk memeproleh kredit berikutnya
                serta membuka peluang bagi orang lain mendapatkan giliran pelayanan.
            </li>
            <li>
                Apabila terjadi saling selisih berkenaan dengan hak serta kewajiban yang timbul atas perjanjian
                utang-piutang ini, akan diselesaikan secara musyawarah untuk mencapai kata sepakat. Apabila tidak
                dapat dicapai kata sepakat, kedua belah pihak setuju untuk menunjuk Pengadilan Negeri {{ $nama_kab }}
                sebagai upaya hukum menyelesaikan persengketaan tersebut.
            </li>
            <li>
                Pihak kedua menyatakan secara sadar dan sukarela telah menanda tangani akad atau perjanjian kredit
                ini, setelah terlebih dahulu membacakan isi perjanjian ini kepada para pemberi kuasa dengan
                sejelas-jelasnya dan tidak seorangpun diantaranya menyatakan keberatan, serta untuk menjadikan
                periksa bagi yang berwenang.
            </li>
        </ol>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;" class="p">
            <tr>
                <td>
                    <div style="margin-top: 24px;"></div>
                    {!! $ttd !!}
                </td>
            </tr>
        </table>
    </div>
@endsection
