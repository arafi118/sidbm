@php
    use App\Utils\Tanggal;
    $section = 0;

    $triwulan = [
        '01' => ['1', '2', ' 3'],
        '02' => ['1', '2', ' 3'],
        '03' => ['1', '2', ' 3'],
        '04' => ['4', '5', ' 6'],
        '05' => ['4', '5', ' 6'],
        '06' => ['4', '5', ' 6'],
        '07' => ['7', '8', ' 9'],
        '08' => ['7', '8', ' 9'],
        '09' => ['7', '8', ' 9'],
        '10' => ['10', '11', ' 12'],
        '11' => ['10', '11', ' 12'],
        '12' => ['10', '11', ' 12'],
    ];

    $bulan_tampil = $triwulan[$bulan];
    $bulan1 = $bulan_tampil[0];
    $bulan2 = $bulan_tampil[1];
    $bulan3 = $bulan_tampil[2];
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <style>
        html {
            margin-left: 40px;
            margin-right: 40px;
        }
    </style>

    @foreach ($jenis_pp as $jpp)
        @php
            if ($jpp->pinjaman_kelompok->isEmpty()) {
                break;
            }
        @endphp
        @php
            $kd_desa = [];

            $t_rencana1 = 0;
            $t_realisasi1 = 0;

            $t_rencana2 = 0;
            $t_realisasi2 = 0;

            $t_rencana3 = 0;
            $t_realisasi3 = 0;
        @endphp

        @if ($jpp->nama_jpp != 'SPP')
            <div class="break"></div>
        @endif
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
            <tr>
                <td colspan="3" align="center">
                    <div style="font-size: 18px;">
                        <b>LAPORAN RENCANA REALISASI PENCAIRAN KELOMPOK {{ $jpp->nama_jpp }}</b>
                    </div>
                    <div style="font-size: 16px;">
                        <b style="text-transform: uppercase;">
                            Triwulan {{ $keuangan->romawi(str_pad(ceil($bulan / 4), '2', '0', STR_PAD_LEFT)) }}
                            Tahun {{ $tahun }}
                        </b>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" height="5"></td>
            </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr style="background: rgb(232, 232, 232); font-weight: bold; font-size: 12px;">
                <th rowspan="2" class="t l b" width="30%">Nama Kelompok</th>
                @foreach ($triwulan[$bulan] as $bln)
                    <th colspan="2" class="t l b" width="20%" height="16">
                        {{ Tanggal::namaBulan(date('Y') . '-' . $bln . '-01') }}
                    </th>
                @endforeach
                <th rowspan="2" class="t l b r" width="10%">Total</th>
            </tr>
            <tr style="background: rgb(232, 232, 232); font-weight: bold; font-size: 12px;">
                <th class="t l b" width="10%" height="16">Rencana</th>
                <th class="t l b" width="10%">Realisasi</th>
                <th class="t l b" width="10%">Rencana</th>
                <th class="t l b" width="10%">Realisasi</th>
                <th class="t l b" width="10%">Rencana</th>
                <th class="t l b" width="10%">Realisasi</th>
            </tr>

            @foreach ($jpp->pinjaman_kelompok as $pinkel)
                @php
                    $kd_desa[] = $pinkel->kd_desa;
                    $desa = $pinkel->kd_desa;

                @endphp

                @if (array_count_values($kd_desa)[$pinkel->kd_desa] <= '1')
                    @if ($section != $desa && count($kd_desa) > 1)
                        @php
                            $t_rencana1 += $j_rencana1;
                            $t_realisasi1 += $j_realisasi1;

                            $t_rencana2 += $j_rencana2;
                            $t_realisasi2 += $j_realisasi2;

                            $t_rencana3 += $j_rencana3;
                            $t_realisasi3 += $j_realisasi3;
                        @endphp
                        <tr style="font-weight: bold;">
                            <td class="t l b" align="left" height="15">
                                Jumlah {{ $nama_desa }}
                            </td>
                            <td class="t l b" align="right">{{ number_format($j_rencana1) }}</td>
                            <td class="t l b" align="right">{{ number_format($j_realisasi1) }}</td>
                            <td class="t l b" align="right">{{ number_format($j_rencana2) }}</td>
                            <td class="t l b" align="right">{{ number_format($j_realisasi2) }}</td>
                            <td class="t l b" align="right">{{ number_format($j_rencana3) }}</td>
                            <td class="t l b" align="right">{{ number_format($j_realisasi3) }}</td>
                            <td class="t l b r" align="right">
                                {{ number_format($j_realisasi1 + $j_realisasi2 + $j_realisasi3) }}</td>
                        </tr>
                    @endif

                    <tr style="font-weight: bold;">
                        <td class="t l b r" colspan="8" align="left">{{ $pinkel->kode_desa }}.
                            {{ $pinkel->nama_desa }}</td>
                    </tr>

                    @php
                        $nomor = 1;

                        $j_rencana1 = 0;
                        $j_realisasi1 = 0;

                        $j_rencana2 = 0;
                        $j_realisasi2 = 0;

                        $j_rencana3 = 0;
                        $j_realisasi3 = 0;

                        $section = $pinkel->kd_desa;
                        $nama_desa = $pinkel->sebutan_desa . ' ' . $pinkel->nama_desa;
                    @endphp
                @endif

                @php
                    $realisasi1 = 0;
                    $realisasi2 = 0;
                    $realisasi3 = 0;
                    foreach ($pinkel->real as $real) {
                        if (in_array(Tanggal::bulan($real->tgl_transaksi), $bulan_tampil)) {
                            $bulan_trx = Tanggal::bulan($real->tgl_transaksi);
                            $tahun_trx = Tanggal::tahun($real->tgl_transaksi);

                            if ($bulan_trx == $bulan1 && $tahun_trx == $tahun) {
                                $realisasi1 += $real->realisasi_pokok + $real->realisasi_jasa;
                            }

                            if ($bulan_trx == $bulan2 && $tahun_trx == $tahun) {
                                $realisasi2 += $real->realisasi_pokok + $real->realisasi_jasa;
                            }

                            if ($bulan_trx == $bulan3 && $tahun_trx == $tahun) {
                                $realisasi3 += $real->realisasi_pokok + $real->realisasi_jasa;
                            }
                        }
                    }

                    $rencana1 = 0;
                    $rencana2 = 0;
                    $rencana3 = 0;
                    foreach ($pinkel->ra as $ra) {
                        if (in_array(Tanggal::bulan($ra->jatuh_tempo), $bulan_tampil)) {
                            $bulan_tempo = Tanggal::bulan($ra->jatuh_tempo);
                            $tahun_tempo = Tanggal::tahun($ra->jatuh_tempo);

                            if ($bulan_tempo == $bulan1 && $tahun_tempo == $tahun) {
                                $rencana1 += $ra->wajib_pokok + $ra->wajib_jasa;
                            }

                            if ($bulan_tempo == $bulan2 && $tahun_tempo == $tahun) {
                                $rencana2 += $ra->wajib_pokok + $ra->wajib_jasa;
                            }

                            if ($bulan_tempo == $bulan3 && $tahun_tempo == $tahun) {
                                $rencana3 += $ra->wajib_pokok + $ra->wajib_jasa;
                            }
                        }
                    }
                @endphp

                <tr>
                    <td class="t l b" align="left">
                        {{ $pinkel->nama_kelompok }} [{{ $pinkel->ketua }}] - {{ $pinkel->id }}
                    </td>
                    <td class="t l b" align="right">{{ number_format($rencana1) }}</td>
                    <td class="t l b" align="right">{{ number_format($realisasi1) }}</td>
                    <td class="t l b" align="right">{{ number_format($rencana2) }}</td>
                    <td class="t l b" align="right">{{ number_format($realisasi2) }}</td>
                    <td class="t l b" align="right">{{ number_format($rencana3) }}</td>
                    <td class="t l b" align="right">{{ number_format($realisasi3) }}</td>
                    <td class="t l b r" align="right">{{ number_format($realisasi1 + $realisasi2 + $realisasi3) }}</td>
                </tr>

                @php
                    $j_rencana1 += $rencana1;
                    $j_realisasi1 += $realisasi1;

                    $j_rencana2 += $rencana2;
                    $j_realisasi2 += $realisasi2;

                    $j_rencana3 += $rencana3;
                    $j_realisasi3 += $realisasi3;
                @endphp
            @endforeach
            @php
                $t_rencana1 += $j_rencana1;
                $t_realisasi1 += $j_realisasi1;

                $t_rencana2 += $j_rencana2;
                $t_realisasi2 += $j_realisasi2;

                $t_rencana3 += $j_rencana3;
                $t_realisasi3 += $j_realisasi3;
            @endphp

            @if (count($kd_desa) > 0)
                <tr style="font-weight: bold;">
                    <td class="t l b" align="left" height="8">
                        Jumlah {{ $nama_desa }}
                    </td>
                    <td class="t l b" align="right">{{ number_format($j_rencana1) }}</td>
                    <td class="t l b" align="right">{{ number_format($j_realisasi1) }}</td>
                    <td class="t l b" align="right">{{ number_format($j_rencana2) }}</td>
                    <td class="t l b" align="right">{{ number_format($j_realisasi2) }}</td>
                    <td class="t l b" align="right">{{ number_format($j_rencana3) }}</td>
                    <td class="t l b" align="right">{{ number_format($j_realisasi3) }}</td>
                    <td class="t l b r" align="right">
                        {{ number_format($j_realisasi1 + $j_realisasi2 + $j_realisasi3) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="8" style="padding: 0px !important;">
                        <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                            style="font-size: 8px; table-layout: fixed;">
                            <tr style="font-weight: bold;">
                                <td class="t l b" align="left" height="15" width="30%">
                                    Jumlah {{ $nama_desa }}
                                </td>
                                <td class="t l b" align="right" width="10%">{{ number_format($t_rencana1) }}</td>
                                <td class="t l b" align="right" width="10%">{{ number_format($t_realisasi1) }}</td>
                                <td class="t l b" align="right" width="10%">{{ number_format($t_rencana2) }}</td>
                                <td class="t l b" align="right" width="10%">{{ number_format($t_realisasi2) }}</td>
                                <td class="t l b" align="right" width="10%">{{ number_format($t_rencana3) }}</td>
                                <td class="t l b" align="right" width="10%">{{ number_format($t_realisasi3) }}</td>
                                <td class="t l b r" align="right" width="10%">
                                    {{ number_format($t_realisasi1 + $t_realisasi2 + $t_realisasi3) }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="8">
                                    <div style="margin-top: 24px;"></div>
                                    {!! json_decode($kec->ttd->tanda_tangan_pelaporan, true) !!}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endif
        </table>
    @endforeach
@endsection
