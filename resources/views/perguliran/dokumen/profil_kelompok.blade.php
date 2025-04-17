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
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>PROFIL {{ $pinkel->jenis_pp != '3' ? 'KELOMPOK' : '' }} {{ $pinkel->jpp->nama_jpp }}</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ $pinkel->kelompok->nama_kelompok }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tr>
            <td width="30">&nbsp;</td>
            <td width="10" align="center">A.</td>
            <td width="100">Nama {{ $pinkel->jenis_pp != '3' ? 'kelompok' : 'Lembaga' }}</td>
            <td width="5" align="right">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->nama_kelompok }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">B.</td>
            <td colspan="3">Alamat Lengkap</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>1.&nbsp; Alamat</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->alamat_kelompok }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>2.&nbsp; {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->d->nama_desa }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>3.&nbsp; Kecamatan</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $kec->nama_kec }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>4.&nbsp; Kabupaten</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $nama_kab }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>5.&nbsp; Provinsi</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $kab->wilayah->nama }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">C.</td>
            <td>Tingkat Kelompok</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->tk->nama_tk }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">D.</td>
            <td colspan="3">Susunan Pengurus</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>1.&nbsp;{{ $pinkel->jenis_pp != '3' ? 'Ketua' : 'Pimpinan' }}</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $ketua }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>2.&nbsp;{{ $pinkel->jenis_pp != '3' ? 'Sekertaris' : 'Penanggung Jawab' }}</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $sekretaris }}</td>
        </tr>
        @if ($pinkel->jenis_pp != '3')
            <tr>
                <td width="30">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td>3.&nbsp;Bendahara</td>
                <td align="right">:</td>
                <td style="font-weight: bold;">{{ $bendahara }}</td>
            </tr>
        @endif
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">E.</td>
            <td>Telepon</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->telpon }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">F.</td>
            <td>Tanggal Berdiri</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ Tanggal::tglLatin($pinkel->kelompok->tgl_berdiri) }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">G.</td>
            <td colspan="3">Deskripsi {{ $pinkel->jenis_pp != '3' ? 'Kelompok' : '' }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td colspan="3" align="justify">{{ $pinkel->jenis_pp != '3' ? 'Kelompok' : '' }}
                {{ $pinkel->kelompok->nama_kelompok }} adalah salah satu
                {{ $pinkel->jenis_pp != '3' ? 'kelompok' : 'Lembaga Usaha' }} yang berada di
                {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }} {{ $pinkel->kelompok->d->nama_desa }} Kec.
                {{ $kec->nama_kec }} {{ $kabupaten }} Prov. {{ ucwords(strtolower($kab->wilayah->nama)) }}.
                {{ $pinkel->jenis_pp != '3' ? 'Kelompok' : 'Lembaga' }} yang
                {{ $pinkel->jenis_pp != '3' ? 'diketuai' : 'dipimpin' }} oleh {{ $ketua }} ini sudah
                berdiri sejak tanggal {{ Tanggal::tglLatin($pinkel->kelompok->tgl_berdiri) }} yang berfokus pada
                jenis piutang {{ $pinkel->jpp->nama_spp }} ({{ $pinkel->jpp->deskripsi_jpp }}) serta memiliki jenis usaha
                {{ $pinkel->kelompok->usaha->nama_usaha }} dalam kegiatan {{ $pinkel->kelompok->kegiatan->nama_jk }}.
            </td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tr>
            <td width="50%">&nbsp;</td>
            <td align="center">{{ $kec->nama_kec }}, {{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
        </tr>
        <tr>
            <td width="50%">&nbsp;</td>
            <td align="center">Ketua Kelompok</td>
        </tr>
        <tr>
            <td colspan="2" height="30">&nbsp;</td>
        </tr>
        <tr>
            <td width="50%">&nbsp;</td>
            <td align="center">
                <b>{{ $ketua }}</b>
            </td>
        </tr>
    </table>
@endsection
