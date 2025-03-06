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
                    <b>Susunan Pengurus</b>
                </div>
                <div style="font-size: 16px; text-decoration: underline;">
                    <b>{{ $pinkel->jenis_pp != '3' ? 'Kelompok' : '' }} {{ $pinkel->kelompok->nama_kelompok }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tr>
            <td width="40">Kode {{ $pinkel->jenis_pp != '3' ? 'Kelompok' : 'Lembaga' }}</td>
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
            <td>Nama {{ $pinkel->jenis_pp != '3' ? 'Kelompok' : 'Lembaga' }}</td>
            <td align="right">:</td>
            <td>
                <b>{{ $pinkel->kelompok->nama_kelompok }}</b>
            </td>
            <td>{{ $pinkel->jenis_pp != '3' ? 'Ketua' : 'Pimpinan' }}</td>
            <td align="right">:</td>
            <td>
                <b>{{ $ketua }}</b>
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
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px; margin-top: 12px;">
        <tr style="background: rgb(232, 232, 232)">
            <th class="l t b" height="16" width="10" align="center">No</th>
            <th class="l t b" width="150" align="center">Jabatan</th>
            <th class="l t b r" width="150" align="center">Nama</th>
        </tr>
        <tr>
            <td class="l t b" height="14" align="center">1.</td>
            <td class="l t b">
                {{ $pinkel->jenis_pp != '3' ? 'Ketua Kelompok' : 'Pimpinan' }}
            </td>
            <td class="l t b r">{{ $ketua }}</td>
        </tr>
        <tr>
            <td class="l t b" height="14" align="center">2.</td>
            <td class="l t b">
                {{ $pinkel->jenis_pp != '3' ? 'Sekretaris Kelompok' : 'Penanggung Jawab' }}
            </td>
            <td class="l t b r">{{ $sekretaris }}</td>
        </tr>
        @if ($pinkel->jenis_pp != '3')
            <tr>
                <td class="l t b" height="14" align="center">3.</td>
                <td class="l t b">Bendahara Kelompok</td>
                <td class="l t b r">{{ $bendahara }}</td>
            </tr>
        @endif
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td align="center">{{ $kec->nama_kec }}, {{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td align="center">
                @if ($kec->ttd_pengurus_kelompok == '1')
                    Ketua Kelompok
                @else
                    {{ $pinkel->kelompok->d->sebutan_desa->sebutan_kades }} {{ $pinkel->kelompok->d->nama_desa }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="3" height="30">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td align="center">
                <b>
                    @if ($kec->ttd_pengurus_kelompok == '1')
                        <u>{{ $ketua }}</u>
                    @else
                        <u>{{ $pinkel->kelompok->d->kades }}</u>
                    @endif
                </b>
            </td>
        </tr>
    </table>
@endsection
