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
            <td align="center">
                <div style="font-size: 18px;">
                    <b>ALOKASI PEMBAGIAN LABA USAHA</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
    </table>

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="2">Laba/Rugi Tahun {{ $tahun }}</td>
            <td align="right">{{ number_format($surplus) }}</td>
        </tr>
    </table>
@endsection
