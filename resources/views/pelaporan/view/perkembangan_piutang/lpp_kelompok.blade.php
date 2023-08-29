@php
    use App\Utils\Tanggal;
    $section = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    @foreach ($jenis_pp as $jpp)
        @php
            $kd_desa = [];
        @endphp
        @if ($jpp->nama_jpp != 'SPP')
            <div class="break"></div>
        @endif
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
            <tr>
                <td colspan="3" align="center">
                    <div style="font-size: 18px;">
                        <b>DAFTAR PERKEMBANGAN PINJAMAN PER KELOMPOK {{ strtoupper($jpp->nama_jpp) }}</b>
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
        <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 9px;">
            <tr style="background: rgb(230, 230, 230); font-weight: bold;">
                <th rowspan="2" width="8">No</th>
                <th rowspan="2">Kelompok - Loan ID</th>
                <th rowspan="2" width="20">Tgl Cair</th>
                <th rowspan="2" width="20">Tempo</th>
                <th rowspan="2" width="30">Alokasi</th>
                <th colspan="2" width="80">Target</th>
                <th colspan="2" width="80">Real s.d. Bulan Lalu</th>
                <th colspan="2" width="80">Real Bulan Ini</th>
                <th colspan="2" width="80">Real s.d. Bulan Ini</th>
                <th colspan="2" width="80">Saldo</th>
                <th rowspan="2" width="8">%</th>
                <th colspan="2" width="80">Tunggakan</th>
            </tr>
            <tr style="background: rgb(230, 230, 230); font-weight: bold;">
                <th>Pokok</th>
                <th>Jasa</th>
                <th>Pokok</th>
                <th>Jasa</th>
                <th>Pokok</th>
                <th>Jasa</th>
                <th>Pokok</th>
                <th>Jasa</th>
                <th>Pokok</th>
                <th>Jasa</th>
                <th>Pokok</th>
                <th>Jasa</th>
            </tr>

            @foreach ($jpp->pinjaman_kelompok as $pinkel)
                @php
                    $kd_desa[] = $pinkel->kd_desa;
                    $desa = $pinkel->kd_desa;
                    
                @endphp
                @if (array_count_values($kd_desa)[$pinkel->kd_desa] <= '1')
                    @if ($section != $desa && count($kd_desa) > 1)
                        @php
                            $j_pross = 1;
                            if ($j_target_pokok != 0) {
                                $j_pross = $j_real_bi_pokok / $j_target_pokok;
                            }
                        @endphp
                        <tr style="font-weight: bold;">
                            <td colspan="4" align="left">
                                Jumlah {{ $nama_desa }}
                            </td>
                            <td align="right">{{ number_format($j_alokasi) }}</td>
                            <td align="right">{{ number_format($j_target_pokok) }}</td>
                            <td align="right">{{ number_format($j_target_jasa) }}</td>
                            <td align="right">{{ number_format($j_real_bl_pokok) }}</td>
                            <td align="right">{{ number_format($j_real_bl_jasa) }}</td>
                            <td align="right">{{ number_format($j_real_pokok) }}</td>
                            <td align="right">{{ number_format($j_real_jasa) }}</td>
                            <td align="right">{{ number_format($j_real_bi_pokok) }}</td>
                            <td align="right">{{ number_format($j_real_bi_jasa) }}</td>
                            <td align="right">{{ number_format($j_saldo_pokok) }}</td>
                            <td align="right">{{ number_format($j_saldo_jasa) }}</td>
                            <td align="center">{{ number_format(floor($j_pross * 100)) }}</td>
                            <td align="right">{{ number_format($j_tunggakan_pokok) }}</td>
                            <td align="right">{{ number_format($j_tunggakan_jasa) }}</td>
                        </tr>
                    @endif

                    <tr style="font-weight: bold;">
                        <td colspan="18" align="left">{{ $pinkel->kode_desa }}. {{ $pinkel->nama_desa }}</td>
                    </tr>

                    @php
                        $nomor = 1;
                        $j_alokasi = 0;
                        $j_target_pokok = 0;
                        $j_target_jasa = 0;
                        $j_real_bl_pokok = 0;
                        $j_real_bl_jasa = 0;
                        $j_real_pokok = 0;
                        $j_real_jasa = 0;
                        $j_real_bi_pokok = 0;
                        $j_real_bi_jasa = 0;
                        $j_saldo_pokok = 0;
                        $j_saldo_jasa = 0;
                        $j_tunggakan_pokok = 0;
                        $j_tunggakan_jasa = 0;
                        $section = $pinkel->kd_desa;
                        $nama_desa = $pinkel->sebutan_desa . ' ' . $pinkel->nama_desa;
                    @endphp
                @endif

                @php
                    $real_pokok = 0;
                    $real_jasa = 0;
                    $sum_pokok = 0;
                    $sum_jasa = 0;
                    $saldo_pokok = $pinkel->alokasi;
                    $saldo_jasa = $pinkel->alokasi / $pinkel->pros_jasa;
                    if ($pinkel->saldo) {
                        $real_pokok = $pinkel->saldo->realisasi_pokok;
                        $real_jasa = $pinkel->saldo->realisasi_jasa;
                        $sum_pokok = $pinkel->saldo->sum_pokok;
                        $sum_jasa = $pinkel->saldo->sum_jasa;
                        $saldo_pokok = $pinkel->saldo->saldo_pokok;
                        $saldo_jasa = $pinkel->saldo->saldo_jasa;
                    }
                    
                    $target_pokok = 0;
                    $target_jasa = 0;
                    if ($pinkel->target) {
                        $target_pokok = $pinkel->target->target_pokok;
                        $target_jasa = $pinkel->target->target_jasa;
                    }
                    
                    $tunggakan_pokok = $target_pokok - $sum_pokok;
                    if ($tunggakan_pokok < 0) {
                        $tunggakan_pokok = 0;
                    }
                    $tunggakan_jasa = $target_jasa - $sum_jasa;
                    if ($tunggakan_jasa < 0) {
                        $tunggakan_jasa = 0;
                    }
                    
                    $pross = 1;
                    if ($target_pokok != 0) {
                        $pross = $sum_pokok / $target_pokok;
                    }
                @endphp

                <tr>
                    <td align="center">{{ $nomor++ }}</td>
                    <td align="left">{{ $pinkel->nama_kelompok }} - {{ $pinkel->id }}</td>
                    <td align="center">{{ Tanggal::tglIndo($pinkel->tgl_cair) }}</td>
                    <td align="center">{{ $pinkel->jangka }}</td>
                    <td align="right">{{ number_format($pinkel->alokasi) }}</td>
                    <td align="right">{{ number_format($target_pokok) }}</td>
                    <td align="right">{{ number_format($target_jasa) }}</td>
                    <td align="right">{{ number_format($sum_pokok - $pinkel->real_sum_realisasi_pokok) }}</td>
                    <td align="right">{{ number_format($sum_jasa - $pinkel->real_sum_realisasi_jasa) }}</td>
                    <td align="right">{{ number_format($pinkel->real_sum_realisasi_pokok) }}</td>
                    <td align="right">{{ number_format($pinkel->real_sum_realisasi_jasa) }}</td>
                    <td align="right">{{ number_format($sum_pokok) }}</td>
                    <td align="right">{{ number_format($sum_jasa) }}</td>
                    <td align="right">{{ number_format($saldo_pokok) }}</td>
                    <td align="right">{{ number_format($saldo_jasa) }}</td>
                    <td align="center">{{ number_format(floor($pross * 100)) }}</td>

                    @if ($pinkel->tgl_lunas <= $tgl_kondisi && $pinkel->status == 'L')
                        <td colspan="2" align="center">
                            V-LUNAS {{ Tanggal::tglIndo($pinkel->tgl_lunas) }}
                        </td>
                    @elseif ($pinkel->tgl_lunas <= $tgl_kondisi && $pinkel->status == 'R')
                        <td colspan="2" align="center">
                            Rescedulling {{ Tanggal::tglIndo($pinkel->tgl_lunas) }}
                        </td>
                    @elseif ($pinkel->tgl_lunas <= $tgl_kondisi && $pinkel->status == 'H')
                        <td colspan="2" align="center">
                            Penghapusan {{ Tanggal::tglIndo($pinkel->tgl_lunas) }}
                        </td>
                    @else
                        <td align="right">{{ number_format($tunggakan_pokok) }}</td>
                        <td align="right">{{ number_format($tunggakan_jasa) }}</td>
                    @endif
                </tr>

                @php
                    $j_alokasi += $pinkel->alokasi;
                    $j_target_pokok += $target_pokok;
                    $j_target_jasa += $target_jasa;
                    $j_real_bl_pokok += $sum_pokok - $pinkel->real_sum_realisasi_pokok;
                    $j_real_bl_jasa += $sum_jasa - $pinkel->real_sum_realisasi_jasa;
                    $j_real_pokok += $pinkel->real_sum_realisasi_pokok;
                    $j_real_jasa += $pinkel->real_sum_realisasi_jasa;
                    $j_real_bi_pokok += $sum_pokok;
                    $j_real_bi_jasa += $sum_jasa;
                    $j_saldo_pokok += $saldo_pokok;
                    $j_saldo_jasa += $saldo_jasa;
                    $j_tunggakan_pokok += $tunggakan_pokok;
                    $j_tunggakan_jasa += $tunggakan_jasa;
                @endphp
            @endforeach
            @php
                $j_pross = 1;
                if ($j_target_pokok != 0) {
                    $j_pross = $j_real_bi_pokok / $j_target_pokok;
                }
            @endphp
            @if (count($kd_desa) > 0)
                <tr style="font-weight: bold;">
                    <td colspan="4" align="left">
                        Jumlah {{ $nama_desa }}
                    </td>
                    <td align="right">{{ number_format($j_alokasi) }}</td>
                    <td align="right">{{ number_format($j_target_pokok) }}</td>
                    <td align="right">{{ number_format($j_target_jasa) }}</td>
                    <td align="right">{{ number_format($j_real_bl_pokok) }}</td>
                    <td align="right">{{ number_format($j_real_bl_jasa) }}</td>
                    <td align="right">{{ number_format($j_real_pokok) }}</td>
                    <td align="right">{{ number_format($j_real_jasa) }}</td>
                    <td align="right">{{ number_format($j_real_bi_pokok) }}</td>
                    <td align="right">{{ number_format($j_real_bi_jasa) }}</td>
                    <td align="right">{{ number_format($j_saldo_pokok) }}</td>
                    <td align="right">{{ number_format($j_saldo_jasa) }}</td>
                    <td align="center">{{ number_format(floor($j_pross * 100)) }}</td>
                    <td align="right">{{ number_format($j_tunggakan_pokok) }}</td>
                    <td align="right">{{ number_format($j_tunggakan_jasa) }}</td>
                </tr>
            @endif
        </table>
    @endforeach
@endsection
