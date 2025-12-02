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
    <div style="padding: 60px; padding-top: 0px; border: 1px solid #000; height: 82%;">
        <table border="0" width="100%" class="p">
            <tr>
                <td colspan="3" height="40" align="center" style="text-transform: uppercase; font-size: 16px;">
                    <b>K u i t a n s i</b>
                </td>
            </tr>
            <tr>
                <td width="90">Telah Diterima Dari</td>
                <td width="10" align="center">:</td>
                <td class="b">
                    <b>{{ $kec->sebutan_level_3 }}</b>
                </td>
            </tr>
            <tr>
                <td>Uang Sebanyak</td>
                <td align="center">:</td>
                <td class="b">
                    <b>{{ $keuangan->terbilang($pinkel->alokasi) }} Rupiah</b>
                </td>
            </tr>
            <tr>
                <td>Untuk Pembayaran</td>
                <td align="center">:</td>
                <td class="b">
                    <b>Pencairan Piutang Kel. {{ $pinkel->kelompok->nama_kelompok }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td class="b">
                    <b>
                        Beralamat Di {{ $pinkel->kelompok->alamat_kelompok }}
                        {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
                        {{ $pinkel->kelompok->d->nama_desa }}
                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td class="b">
                    <b>Loan ID. {{ $pinkel->id }} &mdash; SPK No. {{ $pinkel->spk_no }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="2" class="t b" align="center">
                    Rp. {{ number_format($pinkel->alokasi) }}
                </td>
                <td>&nbsp;</td>
            </tr>
        </table>

        @if ($tanda_tangan)
            {!! $tanda_tangan !!}
        @else
            <table border="0" width="100%" style="font-size: 11px;">
                <tr>
                    <td width="10%">&nbsp;</td>
                    <td width="10%">&nbsp;</td>
                    <td width="10%">&nbsp;</td>
                    <td width="10%">&nbsp;</td>
                    <td width="10%">&nbsp;</td>
                    <td width="10%">&nbsp;</td>
                    <td width="10%">&nbsp;</td>
                    <td width="10%">&nbsp;</td>
                    <td width="10%">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="6">&nbsp;</td>
                    <td colspan="3" align="center">
                        {{ $kec->nama_kec }}, {{ Tanggal::tglLatin($pinkel->tgl_cair) }}
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="6">
                        Dikeluarkan Oleh
                    </td>
                    <td align="center" colspan="3">
                        Diterima Oleh
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="3">
                        {{ $kec->sebutan_level_1 }}
                    </td>
                    <td align="center" colspan="3">
                        {{ $kec->sebutan_level_3 }}
                    </td>
                    <td align="center" colspan="3">
                        Ketua Kelompok
                    </td>
                </tr>
                <tr>
                    <td colspan="9" height="55">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" colspan="3">
                        <b>{{ $dir->namadepan }} {{ $dir->namabelakang }}</b>
                    </td>
                    <td align="center" colspan="3">
                        <b>{{ $bend->namadepan }} {{ $bend->namabelakang }}</b>
                    </td>
                    <td align="center" colspan="3">
                        <b>{{ $ketua }}</b>
                    </td>
                </tr>
            </table>
        @endif
    </div>
@endsection
