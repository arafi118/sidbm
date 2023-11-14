@php
    use App\Utils\Tanggal;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    @foreach ($pinkel->pinjaman_anggota as $pa)
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
            <tr class="b">
                <td colspan="3" align="center">
                    <div style="font-size: 18px;">
                        <b>SURAT PERNYATAAN PEMINJAM</b>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" height="5"></td>
            </tr>
        </table>
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px; text-align: justify;">
            <tr>
                <td colspan="3">Yang bertanda tangan di bawah ini,</td>
            </tr>
            <tr>
                <td width="100">Nama Lengkap</td>
                <td width="5" align="right">:</td>
                <td>
                    <b>{{ $pa->anggota->namadepan }}</b>
                </td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td align="right">:</td>
                <td>
                    <b>{{ $pa->anggota->jk }}</b>
                </td>

            </tr>
            <tr>
                <td>Tempat, Tangal lahir</td>
                <td align="right">:</td>
                <td>
                    <b>{{ $pa->anggota->tempat_lahir }},
                        {{ $pa->anggota->tgl_lahir ? Tanggal::tglLatin($pa->anggota->tgl_lahir) : '' }}</b>
                </td>
            </tr>
            <tr>
                <td>NIK</td>
                <td align="right">:</td>
                <td>
                    <b>{{ $pa->anggota->nik }}</b>
                </td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td align="right">:</td>
                <td>
                    <b>{{ $pa->anggota->alamat }}</b>
                </td>
            </tr>
            <tr>
                <td>Pekerjan/Usaha</td>
                <td align="right">:</td>
                <td>
                    <b>{{ $pa->anggota->usaha }}</b>
                </td>
            </tr>
            <tr>
                <td width="100" colspan="3">
                    <div>
                        Dengan ini menyatakan dengan sebenarnya dan pernyataan ini tidak dapat ditarik kembali, bahwa:
                    </div>

                    <ol>
                        <li>
                            Saya selaku Anggota Kelompok {{ $pinkel->kelompok->nama_kelompok }} Kecamatan
                            {{ $kec->nama_kec }} melalui Desa/Kelurahan {{ $pa->anggota->d->nama_desa }}, Kecamatan
                            {{ $kec->nama_kec }} {{ $nama_kabupaten }}, benar-benar mengajukan pinjaman uang sebesar Rp.
                            {{ number_format($pa->proposal) }} ({{ $keuangan->terbilang($pa->proposal) }}).
                        </li>
                        <li>
                            Saya berjanji akan mengembalikan pinjaman saya tersebut sesuai dengan peraturan yang ada di
                            {{ $kec->nama_lembaga_sort }},
                        </li>
                        <li>
                            Apabila di kemudian hari saya melanggar isi dari surat pernyataan ini, maka saya bersedia
                            dilaporkan
                            kepada pihak yang berwajib dan/atau diproses secara hukum.
                        </li>
                        <li>
                            Jika dikemudian hari terjadi force majeure seperti banjir, gempa bumi, tanah longsor, petir,
                            angin
                            topan, kebakaran, huru-hara, kerusuhan, pemberontakan, dan perang atau saya berhalangan tetap
                            seperti sakit atau meninggal dunia yang mengakibatkan tidak dapat terpenuhinya kewajiban saya
                            sesuai
                            poin 4 (empat) diatas, maka sisa angsuran akan ditanggung oleh ahli waris.
                        </li>
                    </ol>

                    <div>
                        Demikian surat pernyataan ini saya buat dengan sebenarnya dan dengan penuh kesadaran serta rasa
                        tanggung jawab.
                    </div>
                </td>
            </tr>
        </table>
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
            <tr>
                <td colspan="3" height="20">&nbsp;</td>
            </tr>
            <tr>
                <td width="33%">&nbsp;</td>
                <td width="33%">&nbsp;</td>
                <td width="33%" align="center">{{ $kec->nama_kec }}, _________________</td>
            </tr>
            <tr>
                <td align="center">Saksi 1</td>
                <td align="center">Saksi 2</td>
                <td align="center">Yang Menyatakan</td>
            </tr>
            <tr>
                <td colspan="3" height="50">&nbsp;</td>
            </tr>
            <tr>
                <td align="center">
                    <b>{{ $pinkel->kelompok->ketua }}</b>
                </td>
                <td align="center">
                    <b>{{ $pa->anggota->penjamin }}</b>
                </td>
                <td align="center">
                    <b>{{ $pa->anggota->namadepan }}</b>
                </td>
            </tr>
        </table>
        <div class="break"></div>
    @endforeach

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
        <tr class="b">
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>SURAT PERNYATAAN PEMINJAM</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px; text-align: justify;">
        <tr>
            <td colspan="3">Yang bertanda tangan di bawah ini,</td>
        </tr>
        <tr>
            <td width="100">Nama Lengkap</td>
            <td width="5" align="right">:</td>
            <td></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td align="right">:</td>
            <td></td>

        </tr>
        <tr>
            <td>Tempat, Tangal lahir</td>
            <td align="right">:</td>
            <td></td>
        </tr>
        <tr>
            <td>NIK</td>
            <td align="right">:</td>
            <td></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td align="right">:</td>
            <td></td>
        </tr>
        <tr>
            <td>Pekerjan/Usaha</td>
            <td align="right">:</td>
            <td></td>
        </tr>
        <tr>
            <td width="100" colspan="3">
                <div>
                    Dengan ini menyatakan dengan sebenarnya dan pernyataan ini tidak dapat ditarik kembali, bahwa:
                </div>

                <ol>
                    <li>
                        Saya selaku Anggota Kelompok {{ $pinkel->kelompok->nama_kelompok }} Kecamatan {{ $kec->nama_kec }}
                        melalui Desa/Kelurahan {{ $pinkel->kelompok->d->nama_desa }}, Kecamatan {{ $kec->nama_kec }}
                        {{ $nama_kabupaten }}}, benar-benar mengajukan pinjaman uang sebesar Rp. _____________________,
                        dengan jaminan berupa barang kepada kelompok. Barang yang saya jaminkan sebagai berikut :

                        <ul style="list-style: disc;">
                            @for ($i = 0; $i < 3; $i++)
                                <li>
                                    <table border="0" width="100%" cellspacing="0" cellpadding="0"
                                        style="font-size: 12px;">
                                        <tr>
                                            <td height="12" width="80">Nama barang</td>
                                            <td width="10" align="center">:</td>
                                            <td>
                                                <b>________________________________________________________</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="12" width="80">Nilai Jual</td>
                                            <td width="10" align="center">:</td>
                                            <td>
                                                <b>Rp. _______________________</b>
                                            </td>
                                        </tr>
                                    </table>
                                </li>
                            @endfor
                        </ul>
                    </li>
                    <li>
                        Barang yang saya jaminkan kepada kelompok tersebut adalah benar-benar milik saya sendiri,
                    </li>
                    <li>
                        Saya berkewajiban merawat dan melindungi barang jaminan tersebut dan tidak akan menjual,
                        menggadaikan, dan/atau memindahtangankan kepada pihak lain sebelum kredit/pinjaman saya tersebut
                        lunas,
                    </li>
                    <li>
                        Apabila terjadi kemacetan atas kredit saya tersebut, saya bersedia menyerahkan barang jaminan
                        tersebut kepada pihak yang berwenang, guna menyelesaikan kredit/pinjaman saya kepada
                        {{ $kec->nama_lembaga_sort }} Kecamatan {{ $kec->nama_kec }},
                    </li>
                    <li>
                        Saya berjanji akan mengembalikan pinjaman saya tersebut sesuai dengan peraturan yang ada di
                        {{ $kec->nama_lembaga_sort }},
                    </li>
                    <li>
                        Apabila di kemudian hari saya melanggar isi dari surat pernyataan ini, maka saya bersedia dilaporkan
                        kepada pihak yang berwajib dan/atau diproses secara hukum.
                    </li>
                    <li>
                        Jika dikemudian hari terjadi force majeure seperti banjir, gempa bumi, tanah longsor, petir, angin
                        topan, kebakaran, huru-hara, kerusuhan, pemberontakan, dan perang atau saya berhalangan tetap
                        seperti sakit atau meninggal dunia yang mengakibatkan tidak dapat terpenuhinya kewajiban saya sesuai
                        poin 5 (lima) diatas, maka sisa angsuran akan ditanggung oleh ahli waris.
                    </li>
                </ol>

                <div>
                    Demikian surat pernyataan ini saya buat dengan sebenarnya dan dengan penuh kesadaran serta rasa tanggung
                    jawab.
                </div>
            </td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
        <tr>
            <td colspan="3" height="20">&nbsp;</td>
        </tr>
        <tr>
            <td width="33%">&nbsp;</td>
            <td width="33%">&nbsp;</td>
            <td width="33%" align="center">{{ $kec->nama_kec }}, _________________</td>
        </tr>
        <tr>
            <td align="center">Saksi 1</td>
            <td align="center">Saksi 2</td>
            <td align="center">Yang Menyatakan</td>
        </tr>
        <tr>
            <td colspan="3" height="50">&nbsp;</td>
        </tr>
        <tr>
            <td align="center">
                <b>{{ $pinkel->kelompok->ketua }}</b>
            </td>
            <td align="center">
                <b>____________________</b>
            </td>
            <td align="center">
                <b>____________________</b>
            </td>
        </tr>
    </table>
@endsection
