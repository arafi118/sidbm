@php
    use App\Utils\Tanggal;

    $title_form = [
        1 => 'Kegiatan sosial kemasyarakatan dan bantuan RTM',
        2 => 'Pengembangan kapasitas kelompok SPP/UEP',
        3 => 'Pelatihan masyarakat, dan kelompok pemanfaat umum',
        4 => 'Penambahan Modal DBM',
        5 => 'Penambahan Investasi Usaha',
        6 => 'Pendirian Unit Usaha',
    ];
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="7" align="center">
                <div style="font-size: 18px;">
                    <b>ALOKASI PEMBAGIAN LABA USAHA</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
        <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;">
            <td height="15" align="center" width="15%">Kode Akun</td>
            <td align="left" width="50%">
                Alokasi Laba Usaha
            </td>
            <td align="right" width="35%">Jumlah</td>
        </tr>

        @foreach ($rekening as $rek)
            <tr>
                <td align="center">{{ $rek->kode_akun }}</td>
                <td align="left">{{ $rek->nama_akun }}</td>
                <td align="right">Rp. {{ number_format(0, 2) }}</td>
            </tr>
        @endforeach
    </table>
@endsection
