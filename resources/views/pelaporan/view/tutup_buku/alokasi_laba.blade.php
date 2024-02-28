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

    $jumlah_laba_ditahan = $surplus;
    $jumlah = 0;
    $total = 0;
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

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: rgb(232, 232, 232); font-weight: bold; font-size: 12px;">
            <td height="20">
                <b>Laba/Rugi Tahun {{ $tahun - 1 }}</b>
            </td>
            <td align="right">
                <b>Rp. {{ number_format($surplus, 2) }}</b>
            </td>
        </tr>
        <tr style="background: rgb(74, 74, 74); color: #fff;">
            <td colspan="2" height="20">
                <b>Alokasi Laba Usaha</b>
            </td>
        </tr>
        @foreach ($rekening as $rek)
            <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                <td colspan="2">{{ str_replace('UTANG', '', strtoupper($rek->nama_akun)) }}</td>
            </tr>

            {{-- Laba Bagian Masyarakat --}}
            @if ($rek->kode_akun == '2.1.04.01')
                @foreach ($saldo_calk as $saldo)
                    @php
                        $jumlah_laba_ditahan -= floatval($saldo->kredit);
                    @endphp
                    @if (substr($saldo->id, -1) <= 3)
                        @php
                            $jumlah += floatval($saldo->kredit);
                            $bg = 'rgb(230, 230, 230)';
                            if ($loop->iteration % 2 == 0) {
                                $bg = 'rgba(255, 255, 255)';
                            }
                        @endphp
                        <tr style="background: {{ $bg }}">
                            <td>{{ $title_form[substr($saldo->id, -1)] }}</td>
                            <td align="right">
                                Rp. {{ number_format(floatval($saldo->kredit), 2) }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif

            {{-- Laba Bagian Desa --}}
            @if ($rek->kode_akun == '2.1.04.02')
                @foreach ($desa as $d)
                    @php
                        $saldo_desa = 0;
                        if ($d->saldo) {
                            $jumlah_laba_ditahan -= floatval($d->saldo->kredit);
                            $jumlah += floatval($d->saldo->kredit);
                            $saldo_desa = floatval($d->saldo->kredit);
                        }
                        $bg = 'rgb(230, 230, 230)';
                        if ($loop->iteration % 2 == 0) {
                            $bg = 'rgb(255, 255, 255)';
                        }
                    @endphp
                    <tr style="background: {{ $bg }}">
                        <td>
                            Bagian {{ $d->sebutan_desa->sebutan_desa }}
                            {{ $d->nama_desa }}
                        </td>
                        <td align="right">
                            Rp. {{ number_format($saldo_desa, 2) }}
                        </td>
                    </tr>
                @endforeach
            @endif

            {{-- Laba Bagian Penyerta Modal --}}
            @if ($rek->kode_akun == '2.1.04.03')
                @php
                    $jumlah += $jumlah_laba_ditahan;
                    $bg = 'rgb(230, 230, 230)';
                    if ($loop->iteration % 2 == 0) {
                        $bg = 'rgb(255, 255, 255)';
                    }
                @endphp
                <tr style="background: {{ $bg }}">
                    <td>
                        {{ str_replace('Utang', '', $rek->nama_akun) }}
                    </td>
                    <td align="right">
                        Rp. {{ number_format($jumlah_laba_ditahan, 2) }}
                    </td>
                </tr>
            @endif

            <tr style="background: rgb(150, 150, 150); font-weight: bold;">
                <td height="15">
                    <b>Jumlah</b>
                </td>
                <td align="right">
                    <b>Rp. {{ number_format($jumlah, 2) }}</b>
                </td>
            </tr>

            <tr>
                <td colspan="2" height="2"></td>
            </tr>

            @php
                $total += $jumlah;
                $jumlah = 0;
            @endphp
        @endforeach

        <tr style="background: rgb(150, 150, 150); font-weight: bold;">
            <td colspan="2">LABA DITAHAN</td>
        </tr>

        @foreach ($saldo_calk as $saldo)
            @if (substr($saldo->id, -1) > 3)
                @php
                    $jumlah += floatval($saldo->kredit);
                    $total += floatval($saldo->kredit);
                    $bg = 'rgb(230, 230, 230)';
                    if ($loop->iteration % 2 == 0) {
                        $bg = 'rgb(255, 255, 255)';
                    }
                @endphp
                <tr style="background: {{ $bg }}">
                    <td>{{ $title_form[substr($saldo->id, -1)] }}</td>
                    <td align="right">
                        Rp. {{ number_format(floatval($saldo->kredit), 2) }}
                    </td>
                </tr>
            @endif
        @endforeach

        <tr style="background: rgb(150, 150, 150); font-weight: bold;">
            <td height="15">
                <b>Jumlah</b>
            </td>
            <td align="right">
                <b>Rp. {{ number_format($jumlah, 2) }}</b>
            </td>
        </tr>


        <tr>
            <td colspan="2" style="padding: 0px !important;">
                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px;">
                    <tr style="background: rgb(74, 74, 74); color: #fff;">
                        <td align="center" height="20">
                            <b>Total Alokasi Laba Usaha</b>
                        </td>
                        <td align="right">
                            <b>Rp. {{ number_format($total, 2) }}</b>
                        </td>
                    </tr>
                </table>

                <div style="margin-top: 16px;"></div>
                {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kec->ttd->tanda_tangan_pelaporan), true) !!}
            </td>
        </tr>
    </table>
@endsection
