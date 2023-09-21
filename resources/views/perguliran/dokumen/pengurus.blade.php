@php
    use App\Utils\Tanggal;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr class="b">
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>Susunan Pengurus</b>
                </div>
                <div style="font-size: 16px;">
                    <b>Kelompok {{ $pinkel->kelompok->nama_kelompok }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td width="40">Kode Kelompok</td>
            <td width="5" align="right">:</td>
            <td width="150">
                <b>{{ $pinkel->kelompok->kd_kelompok }}</b>
            </td>
            <td width="40">Tanggal</td>
            <td width="5" align="right">:</td>
            <td width="150">
                <b>{{ Tanggal::tglIndo($pinkel->tgl_proposal) }}</b>
            </td>
        </tr>
        <tr>
            <td>Nama Kelompok</td>
            <td align="right">:</td>
            <td>
                <b>{{ $pinkel->kelompok->nama_kelompok }}</b>
            </td>
            <td>Ketua</td>
            <td align="right">:</td>
            <td>
                <b>{{ $pinkel->kelompok->ketua }}</b>
            </td>
        </tr>
        <tr>
            <td>Desa/Kelurahan</td>
            <td align="right">:</td>
            <td>
                <b>{{ $pinkel->kelompok->d->nama_desa }}</b>
            </td>
            <td>Telepon</td>
            <td align="right">:</td>
            <td>
                <b>{{ $pinkel->kelompok->telpon }}</b>
            </td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px; margin-top: 12px;">
        <tr style="background: rgb(232, 232, 232)">
            <th class="l t b" height="16" width="10" align="center">No</th>
            <th class="l t b" width="150" align="center">Jabatan</th>
            <th class="l t b r" width="150" align="center">Nama</th>
        </tr>
        <tr>
            <td class="l t b" height="14" align="center">1.</td>
            <td class="l t b">Ketua Kelompok</td>
            <td class="l t b r">{{ $pinkel->kelompok->ketua }}</td>
        </tr>
        <tr>
            <td class="l t b" height="14" align="center">2.</td>
            <td class="l t b">Sekertaris keompok</td>
            <td class="l t b r">{{ $pinkel->kelompok->sekretaris }}</td>
        </tr>
        <tr>
            <td class="l t b" height="14" align="center">3.</td>
            <td class="l t b">Bendahara Kelompok</td>
            <td class="l t b r">{{ $pinkel->kelompok->bendahara }}</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td align="center">{{ $kec->nama_kec }}, {{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td align="center">Mengetahui,</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td align="center">
                {{ $pinkel->kelompok->d->sebutan_desa->sebutan_kades }} {{ $pinkel->kelompok->d->nama_desa }}
            </td>
        </tr>
        <tr>
            <td colspan="3" height="30">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td align="center">
                <b>
                    <u>{{ $pinkel->kelompok->d->kades }}</u>
                </b>
            </td>
        </tr>
    </table>
@endsection
