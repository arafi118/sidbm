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

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
        <tr>
            <td height="15" align="center" width="15%">Kode Akun</td>
            <td align="left" width="50%">
                Alokasi Laba Usaha
            </td>
            <td align="right" width="35%">Jumlah</td>
        </tr>

        <tr>
            <td colspan="2">
                Laba/Rugi Tahun {{ $tahun }}
            </td>
            <td align="right">Rp. {{ number_format($surplus) }}</td>
        </tr>

        @foreach ($rekening as $rek)
            @php
                $saldo = 0;
                if ($rek->saldo) {
                    $saldo = $rek->saldo->debit - $rek->saldo->kredit;
                    if ($rek->lev1 == '2' || $rek->lev1 == '3') {
                        $saldo = $rek->saldo->kredit - $rek->saldo->debit;
                    }
                }
            @endphp
            <tr>
                <td colspan="3">
                    {{ str_replace('Utang', '', $rek->nama_akun) }}
                </td>
            </tr>
        @endforeach
    </table>
@endsection
