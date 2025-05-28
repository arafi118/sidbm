@php
    use App\Utils\Tanggal;

    $rencana_pendapatan = [];
    $rencana_beban = [];
    foreach ($bulan_hitung as $index => $val) {
        $rencana_pendapatan[$val] = 0;
        $rencana_beban[$val] = 0;
    }

    $realisasi_pendapatan = [];
    $realisasi_beban = [];
    foreach ($bulan_hitung as $index => $val) {
        $realisasi_pendapatan[$val] = 0;
        $realisasi_beban[$val] = 0;
    }

    $kom_rencana_pendapatan = 0;
    $kom_rencana_beban = 0;
    $kom_realisasi_pendapatan = 0;
    $kom_realisasi_beban = 0;

    $is_triwulan = $triwulan == 1 || count($bulan_tampil) == 0;
    $colspan = count($bulan_tampil) * 2 + 1 + 2;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <style>
        html {
            margin-left: 40px;
            margin-right: 40px;
        }
    </style>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>LAPORAN PENGGUNAAN DANA (E-BUDGETING)</b>
                </div>
                <div style="font-size: 16px;">
                    <b style="text-transform: uppercase;">
                        @if (count($bulan_tampil) != 0)
                            Triwulan
                            {{ $keuangan->romawi(str_pad($triwulan, '2', '0', STR_PAD_LEFT)) }}
                        @else
                            Januari - Desember
                        @endif
                        Tahun Anggaran {{ $tahun }}
                    </b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 8px;">
        <thead>
            <tr style="background: rgb(232, 232, 232); font-weight: bold; font-size: 10px;">
                <th rowspan="2" class="t l b" width="20%">Rekening</th>
                @if (!$is_triwulan)
                    <th rowspan="2" class="t l b" width="8%">Komulatif Bulan Lalu</th>
                @endif
                @foreach ($bulan_tampil as $bt)
                    <th colspan="2" class="t l b" width="18%" height="16">
                        {{ Tanggal::namaBulan(date('Y') . '-' . $bt . '-01') }}
                    </th>
                @endforeach
                <th colspan="2" class="t l b r" width="18%">
                    {{ count($bulan_tampil) == '0' ? 'Akumulasi Januari - Desember' : 'Total' }}
                </th>
            </tr>
            <tr style="background: rgb(232, 232, 232); font-weight: bold; font-size: 10px;">
                @foreach ($bulan_tampil as $bt)
                    <th class="t l b" width="9%">Rencana</th>
                    <th class="t l b" width="9%">Realisasi</th>
                @endforeach
                <th class="t l b" width="9%">Rencana</th>
                <th class="t l b r" width="9%">Realisasi</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($akun1 as $lev1)
                @php
                    $kom_rencana_bulan_lalu = 0;
                    $kom_realisasi_bulan_lalu = 0;

                    $rencana = [];
                    $realisasi = [];
                    foreach ($bulan_hitung as $index => $val) {
                        $rencana[$val] = 0;
                        $realisasi[$val] = 0;
                    }
                @endphp

                <tr style="background: rgb(200, 200, 200); font-weight: bold;">
                    <td colspan="{{ $is_triwulan ? $colspan : $colspan + 1 }}" class="t l b r">
                        <b>{{ $lev1->kode_akun }}. {{ $lev1->nama_akun }}</b>
                    </td>
                </tr>

                @foreach ($lev1->akun2 as $lev2)
                    <tr style="background: rgb(150, 150, 150); font-weight: bold;">
                        <td colspan="{{ $is_triwulan ? $colspan : $colspan + 1 }}" class="t l b r">
                            <b>{{ $lev2->kode_akun }}. {{ $lev2->nama_akun }}</b>
                        </td>
                    </tr>

                    @foreach ($lev2->akun3 as $lev3)
                        @foreach ($lev3->rek as $rek)
                            @php
                                $bg = 'rgb(230, 230, 230)';
                                if ($loop->iteration % 2 == 0) {
                                    $bg = 'rgba(255, 255, 255)';
                                }

                                $bulan_ini = 0;
                                $realisasi_bulan_lalu = 0;

                                $total_rencana = 0;
                                $total_realisasi = 0;

                                $kom_realisasi = 0;
                            @endphp

                            <tr style="background: {{ $bg }};">
                                <td class="t l b">{{ $rek->kode_akun }}. {{ $rek->nama_akun }}</td>

                                @foreach ($rek->kom_saldo as $saldo)
                                    @php
                                        if ($bulan_ini != 0) {
                                            $realisasi_bulan_lalu = $saldo_lalu;
                                        }

                                        $bulan_ini = $saldo->bulan;
                                        $saldo_realisasi = floatval($saldo->kredit) - floatval($saldo->debit);
                                        if ($rek->lev1 == 5) {
                                            $saldo_realisasi = floatval($saldo->debit) - floatval($saldo->kredit);
                                        }

                                        $saldo_rencana = 0;
                                        if ($saldo->eb) {
                                            $saldo_rencana = $saldo->eb->jumlah;
                                        }

                                        if ($saldo->bulan <= $akhir) {
                                            $total_rencana += $saldo_rencana;
                                            $kom_rencana_bulan_lalu += $saldo_rencana;
                                        }
                                        $saldo_lalu = $saldo_realisasi;
                                        $saldo_realisasi -= $realisasi_bulan_lalu;

                                        if (in_array($saldo->bulan, $bulan_hitung)) {
                                            $rencana[$saldo->bulan] += $saldo_rencana;
                                            $realisasi[$saldo->bulan] += $saldo_realisasi;

                                            $total_realisasi += $saldo_realisasi;
                                        }
                                    @endphp

                                    @if ($saldo->bulan == $bulan_lalu && !$is_triwulan)
                                        @php
                                            $kom_realisasi = floatval($saldo->kredit) - floatval($saldo->debit);
                                            if ($rek->lev1 == 5) {
                                                $kom_realisasi = floatval($saldo->debit) - floatval($saldo->kredit);
                                            }

                                            $kom_realisasi_bulan_lalu += $kom_realisasi;
                                        @endphp
                                        <td class="t l b" align="right">
                                            {{ number_format($kom_realisasi, 2) }}
                                        </td>
                                    @endif

                                    @if (in_array($saldo->bulan, $bulan_tampil))
                                        <td class="t l b" align="right">
                                            {{ number_format($saldo_rencana, 2) }}
                                        </td>
                                        <td class="t l b" align="right">
                                            {{ number_format($saldo_realisasi, 2) }}
                                        </td>
                                    @endif
                                @endforeach

                                {{-- @php
                                dd($total_realisasi, $kom_realisasi, $bulan_hitung);
                            @endphp --}}
                                <td class="t l b" align="right">
                                    {{ number_format($total_rencana, 2) }}
                                </td>
                                <td class="t l b r" align="right">
                                    {{ number_format($total_realisasi + $kom_realisasi, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
                @php
                    foreach ($rencana as $i => $val) {
                        if ($lev1->lev1 == 4) {
                            $rencana_pendapatan[$i] += $rencana[$i];
                            $realisasi_pendapatan[$i] += $realisasi[$i];
                        } else {
                            $rencana_beban[$i] += $rencana[$i];
                            $realisasi_beban[$i] += $realisasi[$i];
                        }
                    }

                    if ($lev1->lev1 == 4) {
                        $kom_realisasi_pendapatan += $kom_realisasi_bulan_lalu;
                        $kom_rencana_pendapatan += $kom_rencana_bulan_lalu;
                    } else {
                        $kom_realisasi_beban += $kom_realisasi_bulan_lalu;
                        $kom_rencana_beban += $kom_rencana_bulan_lalu;
                    }

                    $kom_realisasi_akun1 = 0;
                @endphp

                <tr style="background: rgb(150, 150, 150); font-weight: bold;">
                    <td align="center" class="t l b" height="14">Total Realisasi {{ $lev1->nama_akun }}</td>
                    @if (!$is_triwulan)
                        <td align="right" class="t l b">{{ number_format($kom_realisasi_bulan_lalu, 2) }}</td>
                    @endif

                    @foreach ($bulan_hitung as $index => $val)
                        @php
                            $kom_realisasi_akun1 += $realisasi[$val];
                        @endphp

                        @if (in_array($val, $bulan_tampil))
                            <td align="right" class="t l b">{{ number_format($rencana[$val], 2) }}</td>
                            <td align="right" class="t l b">{{ number_format($realisasi[$val], 2) }}</td>
                        @endif
                    @endforeach
                    <td align="right" class="t l b">
                        {{ number_format($kom_rencana_bulan_lalu, 2) }}
                    </td>
                    <td align="right" class="t l b r">
                        {{ number_format($kom_realisasi_bulan_lalu + $kom_realisasi_akun1, 2) }}
                    </td>
                </tr>
            @endforeach

            <tr>
                <td colspan="{{ $is_triwulan ? $colspan : $colspan + 1 }}" style="padding: 0px !important;">
                    <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                        style="font-size: 9px;">
                        <tr style="background: rgb(232, 232, 232); font-weight: bold; font-size: 8px;">
                            <th width="20%" class="t l b" height="18">Surplus</th>
                            @if (!$is_triwulan)
                                <th width="8%" class="t l b" align="right">
                                    {{ number_format($kom_realisasi_pendapatan - $kom_realisasi_beban, 2) }}
                                </th>
                            @endif

                            @php
                                $surplus_realisasi_pendapatan = 0;
                                $surplus_realisasi_beban = 0;
                            @endphp
                            @foreach ($bulan_hitung as $index => $val)
                                @php
                                    $saldo_rencana_pendapatan = $rencana_pendapatan[$val];
                                    $saldo_realisasi_pendapatan = $realisasi_pendapatan[$val];

                                    $saldo_rencana_beban = $rencana_beban[$val];
                                    $saldo_realisasi_beban = $realisasi_beban[$val];

                                    $surplus_realisasi_pendapatan += $saldo_realisasi_pendapatan;
                                    $surplus_realisasi_beban += $saldo_realisasi_beban;
                                @endphp

                                @if (in_array($val, $bulan_tampil))
                                    <th width="9%" class="t l b" align="right">
                                        {{ number_format($saldo_rencana_pendapatan - $saldo_rencana_beban, 2) }}
                                    </th>
                                    <th width="9%" class="t l b" align="right">
                                        {{ number_format($saldo_realisasi_pendapatan - $saldo_realisasi_beban, 2) }}
                                    </th>
                                @endif
                            @endforeach

                            <th width="9%" class="t l b" align="right">
                                {{ number_format($kom_rencana_pendapatan - $kom_rencana_beban, 2) }}
                            </th>
                            <th width="9%" class="t l b r" align="right">
                                {{ number_format($kom_realisasi_pendapatan - $kom_realisasi_beban + ($surplus_realisasi_pendapatan - $surplus_realisasi_beban), 2) }}
                            </th>
                        </tr>
                    </table>

                    <div style="margin-top: 16px;"></div>
                    {!! $tanda_tangan !!}
                </td>
            </tr>
        </tbody>
    </table>
@endsection
