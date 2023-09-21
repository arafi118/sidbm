@php
    use App\Utils\Tanggal;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>PROFIL KELOMPOK {{ $pinkel->jpp->nama_jpp }}</b>
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
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td width="30">&nbsp;</td>
            <td width="10" align="center">A.</td>
            <td width="70">Nama kelompok</td>
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
            <td style="font-weight: bold;">{{ $pinkel->kelompok->alamat }}</td>
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
            <td>1.&nbsp;Ketua</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->ketua }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>2.&nbsp;Sekertaris</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->sekretaris }}</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>3.&nbsp;Bendahara</td>
            <td align="right">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->bendahara }}</td>
        </tr>
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
            <td colspan="3">Deskripsi Kelompok</td>
        </tr>
        <tr>
            <td width="30">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td colspan="3" align="justify">Kelompok {{ $pinkel->kelompok->nama_kelompok }} adalah salah satu kelompok
                yang berada di
                {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }} {{ $pinkel->kelompok->d->nama_desa }} Kec.
                {{ $kec->nama_kec }} {{ $kabupaten }} Prov. {{ ucwords(strtolower($kab->wilayah->nama)) }}.
                Kelompok yang diketuai oleh {{ $pinkel->kelompok->ketua }} ini sudah berdiri sejak tanggal
                {{ Tanggal::tglLatin($pinkel->kelompok->tgl_berdiri) }} yang berfokus pada
                jenis pinjaman {{ $pinkel->jpp->nama_spp }} ({{ $pinkel->jpp->deskripsi_jpp }}) serta memiliki jenis usaha
                {{ $pinkel->kelompok->usaha->nama_usaha }} dalam kegiatan {{ $pinkel->kelompok->kegiatan->nama_jk }}.
            </td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
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
                <b>{{ $pinkel->kelompok->ketua }}</b>
            </td>
        </tr>
    </table>
@endsection
