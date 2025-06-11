@php
    use App\Utils\Tanggal;

    $ketua = $pinkel->kelompok->ketua;
    $sekretaris = $pinkel->kelompok->sekretaris;
    $bendahara = $pinkel->kelompok->bendahara;
    if ($pinkel->struktur_kelompok) {
        $struktur_kelompok = json_decode($pinkel->struktur_kelompok, true);
        $ketua = isset($struktur_kelompok['ketua']) ? $struktur_kelompok['ketua'] : '';
        $sekretaris = isset($struktur_kelompok['sekretaris']) ? $struktur_kelompok['sekretaris'] : '';
        $bendahara = isset($struktur_kelompok['bendahara']) ? $struktur_kelompok['bendahara'] : '';
    }
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
        <tr>
            <td width="50">Nomor</td>
            <td width="10" align="center">:</td>
            <td colspan="2">
                ______/{{ Tanggal::tglRomawi($pinkel->tgl_dana) }}
            </td>
        </tr>
        <tr>
            <td>Sifat</td>
            <td align="center">:</td>
            <td colspan="2">
                Penting dan Rahasia
            </td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td align="center">:</td>
            <td colspan="2">
                <b>Pemberitahuan dan Undangan Verifikasi</b>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td align="left" width="140">
                <div>Kepada Yth.</div>
                <div>
                    1. {{ $pinkel->kelompok->d->sebutan_desa->sebutan_kades }} {{ $pinkel->kelompok->d->nama_desa }},
                </div>
                <div>
                    2. Ketua Kelompok {{ $pinkel->kelompok->nama_kelompok }}
                </div>
                <div>Di</div>
                <div style="font-weight: bold; text-align: center;">
                    Tempat
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4" height="16">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="3">
                <div>Dengan hormat,</div>
                <div style="text-align: justify;">
                    Menindaklanjuti tahapan perguliran, dengan ini diberitahukan tentang <b><u>JADWAL VERIFIKASI</u></b> di
                    desa setempat dan diharap kehadirannya untuk kegiatan tersebut yang akan dilaksanakan dengan ketentuan
                    sebagai berikut;
                </div>
                <table class="p0">
                    <tr>
                        <td width="10">1.</td>
                        <td width="120">Nama Kelompok</td>
                        <td width="5">:</td>
                        <td>{{ $pinkel->kelompok->nama_kelompok }}</td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>
                            {{ $pinkel->kelompok->alamat_kelompok }}
                            {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }} {{ $pinkel->kelompok->d->nama_desa }}
                        </td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Tanggal Verifikasi</td>
                        <td>:</td>
                        <td>{{ Tanggal::tglLatin($pinkel->tgl_verifikasi) }}</td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>Waktu</td>
                        <td>:</td>
                        <td>Jam : {{ substr($pinkel->waktu_verifikasi, 0, 5) }}</td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Tempat</td>
                        <td>:</td>
                        <td>Rumah Ketua kelompok / {{ $ketua }}</td>
                    </tr>
                    <tr>
                        <td>6.</td>
                        <td>Kegiatan</td>
                        <td>:</td>
                        <td>
                            Verifikasi usulan kelompok {{ $pinkel->kelompok->nama_kelompok }} Rp.
                            {{ number_format($pinkel->proposal) }}
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="3">
                            Catatan :
                            <ul style="margin: 0px;">
                                <li>
                                    Peminjam harus hadir dan tidak boleh diwakilkan, bagi yang tidak hadir dianggap tidak
                                    mengajukan pinjaman.
                                </li>
                                <li>
                                    Untuk kelompok perguliran, buku-buku administrasi perguliran sebelumnya harap dibawa
                                    untuk
                                    pembinaan dan penilaian perguliran sebelumnya.
                                </li>
                                <li>
                                    Tempat yang akan di kunjungi Tim verifikasi meliputi Balai desa, Kelompok dan anggota,
                                    Lingkungan kelompok.
                                </li>
                                <li>
                                    Dimohon kepala desa ikut hadir secara pribadi.
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>

                <p style="text-align: justify;">
                    Terkait hal tersebut kepada semua kelompok yang mengajukan pinjaman kelompok untuk mempersiapkan semua
                    ketentuan di atas.
                </p>

                <div style="text-align: justify;">
                    Demikian surat pemberitahuan ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan
                    terimakasih.
                </div>
            </td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
        <tr>
            <td colspan="2" height="24">&nbsp;</td>
        </tr>
        <tr>
            <td width="50%">&nbsp;</td>
            <td width="50%" align="center">
                {{ $kec->nama_kec }}, {{ Tanggal::tglLatin($pinkel->tgl_verifikasi) }}
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center">{{ $kec->sebutan_level_1 }} DBM</td>
        </tr>
        <tr>
            <td colspan="2" height="40">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center">{{ $dir->namadepan }} {{ $dir->namabelakang }}</td>
        </tr>
    </table>
@endsection
