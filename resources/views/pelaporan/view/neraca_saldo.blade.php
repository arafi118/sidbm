@php
    $sum_nc_debit = 0;
    $sum_nc_kredit = 0;
    $sum_rl_debit = 0;
    $sum_rl_kredit = 0;
    $sum_ns_debit = 0;
    $sum_ns_kredit = 0;

    $pendapatan = 0;
    $biaya = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>NERACA</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: rgb(230, 230, 230); font-weight: bold;">
            <th rowspan="2" class="t l b" width="40%" height="30">Rekening</th>
            <th colspan="2" class="t l b" width="20%">Necaca Saldo</th>
            <th colspan="2" class="t l b" width="20%">Laba Rugi</th>
            <th colspan="2" class="t l b r" width="20%">Neraca</th>
        </tr>
        <tr style="background: rgb(230, 230, 230); font-weight: bold;">
            <th class="t l b" width="10%">Debit</th>
            <th class="t l b" width="10%">Kredit</th>
            <th class="t l b" width="10%">Debit</th>
            <th class="t l b" width="10%">Kredit</th>
            <th class="t l b" width="10%">Debit</th>
            <th class="t l b r" width="10%">Kredit</th>
        </tr>
        @foreach ($rekening as $rek)
            @php
                $saldo = floatval($keuangan->komSaldo($rek));

                $debit = 0;
                $kredit = $saldo;
                if ($rek->lev1 == '1' || $rek->lev1 == '5') {
                    $debit = $saldo;
                    $kredit = 0;
                }

            @endphp

            <tr>
                <th class="t l b" align="left" style="padding: 2px 4px;">
                    {{ $rek->kode_akun . '. ' . $rek->nama_akun }}
                </th>
                <td class="t l b" align="right">
                    @if ($debit < 0)
                        ({{ number_format($debit * -1, 2) }})
                    @else
                        {{ number_format($debit, 2) }}
                    @endif
                </td>
                <td class="t l b" align="right">
                    @if ($kredit < 0)
                        ({{ number_format($kredit * -1, 2) }})
                    @else
                        {{ number_format($kredit, 2) }}
                    @endif
                </td>

                @if ($rek->lev1 <= 3)
                    @php
                        $sum_nc_debit += $debit;
                        $sum_nc_kredit += $kredit;
                    @endphp
                    <td class="t l b" align="right">{{ number_format(0, 2) }}</td>
                    <td class="t l b" align="right">{{ number_format(0, 2) }}</td>
                    <td class="t l b" align="right">
                        @if ($debit < 0)
                            ({{ number_format($debit * -1, 2) }})
                        @else
                            {{ number_format($debit, 2) }}
                        @endif
                    </td>
                    <td class="t l b r" align="right">
                        @if ($kredit < 0)
                            ({{ number_format($kredit * -1, 2) }})
                        @else
                            {{ number_format($kredit, 2) }}
                        @endif
                    </td>
                @else
                    @php
                        $sum_rl_debit += $debit;
                        $sum_rl_kredit += $kredit;
                        if ($rek->lev1 == 4) {
                            $_pendapatan = $kredit - $debit;
                            $pendapatan += $_pendapatan;
                        } else {
                            $_biaya = $debit - $kredit;
                            $biaya += $_biaya;
                        }
                    @endphp
                    <td class="t l b" align="right">
                        @if ($debit < 0)
                            ({{ number_format($debit * -1, 2) }})
                        @else
                            {{ number_format($debit, 2) }}
                        @endif
                    </td>
                    <td class="t l b" align="right">
                        @if ($kredit < 0)
                            ({{ number_format($kredit * -1, 2) }})
                        @else
                            {{ number_format($kredit, 2) }}
                        @endif
                    </td>
                    <td class="t l b" align="right">
                        {{ number_format(0, 2) }}
                    </td>
                    <td class="t l b r" align="right">
                        {{ number_format(0, 2) }}
                    </td>
                @endif

                @php
                    $sum_ns_debit = $sum_nc_debit + $sum_rl_debit;
                    $sum_ns_kredit = $sum_nc_kredit + $sum_rl_kredit;
                @endphp
            </tr>
        @endforeach

        <tr>
            <td colspan="7" style="padding: 0px !important;">
                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px;">
                    <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                        <td class="t l b" width="40%" align="center">Surplus/Devisit</td>
                        <td class="t l b" width="10%">&nbsp;</td>
                        <td class="t l b" width="10%">&nbsp;</td>
                        <td class="t l b" width="10%" align="right">
                            @if ($pendapatan - $biaya < 0)
                                ({{ number_format(($pendapatan - $biaya) * -1, 2) }})
                            @else
                                {{ number_format($pendapatan - $biaya, 2) }}
                            @endif
                        </td>
                        <td class="t l b" width="10%">&nbsp;</td>
                        <td class="t l b" width="10%">&nbsp;</td>
                        <td class="t l b r" width="10%" align="right">
                            @if ($pendapatan - $biaya < 0)
                                ({{ number_format(($pendapatan - $biaya) * -1, 2) }})
                            @else
                                {{ number_format($pendapatan - $biaya, 2) }}
                            @endif
                        </td>
                    </tr>
                    <tr style="background: rgb(242, 242, 242); font-weight: bold;">
                        <td class="t l b" align="center">Jumlah</td>
                        <td class="t l b" align="right">
                            @if ($sum_ns_debit < 0)
                                ({{ number_format($sum_ns_debit * -1, 2) }})
                            @else
                                {{ number_format($sum_ns_debit, 2) }}
                            @endif
                        </td>
                        <td class="t l b" align="right">
                            @if ($sum_ns_kredit < 0)
                                ({{ number_format($sum_ns_kredit * -1, 2) }})
                            @else
                                {{ number_format($sum_ns_kredit, 2) }}
                            @endif
                        </td>
                        <td class="t l b" align="right">
                            @if ($sum_rl_debit + ($pendapatan - $biaya) < 0)
                                ({{ number_format(($sum_rl_debit + ($pendapatan - $biaya)) * -1, 2) }})
                            @else
                                {{ number_format($sum_rl_debit + ($pendapatan - $biaya), 2) }}
                            @endif
                        </td>
                        <td class="t l b" align="right">
                            @if ($sum_rl_kredit < 0)
                                ({{ number_format($sum_rl_kredit * -1, 2) }})
                            @else
                                {{ number_format($sum_rl_kredit, 2) }}
                            @endif
                        </td>
                        <td class="t l b" align="right">
                            @if ($sum_nc_debit < 0)
                                ({{ number_format($sum_nc_debit * -1, 2) }})
                            @else
                                {{ number_format($sum_nc_debit, 2) }}
                            @endif
                        </td>
                        <td class="t l b r" align="right">
                            @if ($sum_nc_kredit + ($pendapatan - $biaya) < 0)
                                ({{ number_format(($sum_nc_kredit + ($pendapatan - $biaya)) * -1, 2) }})
                            @else
                                {{ number_format($sum_nc_kredit + ($pendapatan - $biaya), 2) }}
                            @endif
                        </td>
                    </tr>
                </table>

                <div style="margin-top: 16px;"></div>
                {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kec->ttd->tanda_tangan_pelaporan), true) !!}
            </td>
        </tr>

</table @endsection
