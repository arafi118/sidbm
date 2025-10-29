@php
    use App\Utils\Pinjaman;
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
    @foreach ($pinkel->pinjaman_anggota as $pa)
        @if ($loop->iteration > 1)
            <div class="break"></div>
        @endif
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
            <tr>
                <td colspan="3" align="center">
                    <div style="font-size: 18px; text-decoration: underline;">
                        <b>SURAT PENGAKUAN UTANG DAN PERTANGGUNGAN AHLI WARIS</b>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" height="5"></td>
            </tr>
        </table>
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px; text-align: justify;">
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
                            {{ $kec->nama_kec }} {{ $nama_kabupaten }}, benar-benar mengajukan piutang uang sebesar Rp.
                            {{ number_format($pa->proposal) }} ({{ $keuangan->terbilang($pa->proposal) }}).
                        </li>
                        <li>
                            Saya berjanji akan mengembalikan piutang saya tersebut sesuai dengan peraturan yang ada di
                            {{ $kec->nama_lembaga_sort }},
                        </li>
                        <li>
                            Apabila di kemudian hari saya melanggar isi dari surat pernyataan ini, maka saya bersedia
                            dilaporkan kepada pihak yang berwajib dan/atau diproses secara hukum.
                        </li>
                        <li>
                            Jika dikemudian hari terjadi force majeure seperti banjir, gempa bumi, tanah longsor, petir,
                            angin
                            topan, kebakaran, huru-hara, kerusuhan, pemberontakan, dan perang atau saya berhalangan tetap
                            seperti sakit atau meninggal dunia yang mengakibatkan tidak dapat terpenuhinya kewajiban saya
                            sesuai
                            poin 4 (empat) diatas, maka sisa angsuran akan diselesaikan oleh ahli waris.
                        </li>
                    </ol>

                    <div>
                        Demikian surat pernyataan ini saya buat dengan sebenarnya dan dengan penuh kesadaran serta rasa
                        tanggung jawab.
                    </div>
                </td>
            </tr>
        </table>

        @if ($tanda_tangan)
            @php
                $tanda_tangan_anggota = Pinjaman::keyword(json_encode($tanda_tangan), [
                    'kec' => $kec,
                    'jenis_laporan' => $jenis_laporan,
                    'tgl_kondisi' => $tgl_kondisi,
                    'pinkel' => $pinkel,
                    'pinjaman_anggota' => $pa,
                ]);
            @endphp
            <div style="font-size: 14px;">
                {!! $tanda_tangan_anggota !!}
            </div>
        @else
            <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
                <tr>
                    <td colspan="3" height="20">&nbsp;</td>
                </tr>
                <tr>
                    <td width="40%">&nbsp;</td>
                    <td width="20%">&nbsp;</td>
                    <td width="40%" align="center">{{ $kec->nama_kec }}, {{ Tanggal::tglLatin($pa->tgl_proposal) }}
                    </td>
                </tr>
                <tr>
                    <td align="center">Saksi 1</td>
                    <td align="center">Saksi/Ahli Waris</td>
                    <td align="center">Yang Menyatakan</td>
                </tr>
                <tr>
                    <td colspan="3" height="50">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center">
                        <b>{{ $ketua }}</b>
                    </td>
                    <td align="center">
                        <b>{{ $pa->anggota->penjamin }}</b>
                    </td>
                    <td align="center">
                        <b>{{ $pa->anggota->namadepan }}</b>
                    </td>
                </tr>
            </table>
        @endif
    @endforeach
@endsection
