@php
    use App\Utils\Tanggal;
    $section = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    @foreach ($jenis_pp as $jpp)
        @php
            if ($jpp->pinjaman_kelompok->isEmpty()) {
                break;
            }
        @endphp
        @php
            $kd_desa = [];
            $t_angg = 0;
            $t_alokasi = 0;
            $t_saldo = 0;
            $t_tunggakan_pokok = 0;
            $t_tunggakan_jasa = 0;
        @endphp
        @if ($jpp->nama_jpp != 'SPP')
            <div class="break"></div>
        @endif

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td colspan="3" align="center">
                    <div style="font-size: 18px;">
                        <b>DAFTAR KELOMPOK AKTIF {{ strtoupper($jpp->nama_jpp) }}</b>
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
                <th class="t l b" height="20" width="3%">No</th>
                <th class="t l b">Kelompok - Loan ID.</th>
                <th class="t l b" width="5%">Angg.</th>
                <th class="t l b" width="8%">Tgl Cair</th>
                <th class="t l b" width="8%">Tempo</th>
                <th class="t l b" width="9%">Alokasi</th>
                <th class="t l b" width="9%">Saldo</th>
                <th class="t l b" width="9%">Tunggakan Pokok</th>
                <th class="t l b r" width="9%">Tunggakan Jasa</th>
            </tr>

            @foreach ($jpp->pinjaman_kelompok as $pinkel)
                @php
                    $kd_desa[] = $pinkel->kd_desa;
                    $desa = $pinkel->kd_desa;
                @endphp
                @if (array_count_values($kd_desa)[$pinkel->kd_desa] <= '1')
                    @if ($section != $desa && count($kd_desa) > 1)
                        @php
                            $t_angg += $j_angg;
                            $t_alokasi += $j_alokasi;
                            $t_saldo += $j_saldo;
                            $t_tunggakan_pokok += $j_tunggakan_pokok;
                            $t_tunggakan_jasa += $j_tunggakan_jasa;
                        @endphp
                        <tr>
                            <td class="t l b" colspan="5">Jumlah {{ $nama_desa }}</td>
                            <td class="t l b" align="right">{{ number_format($j_alokasi) }}</td>
                            <td class="t l b" align="right">{{ number_format($j_saldo) }}</td>
                            <td class="t l b" align="right">{{ number_format($j_tunggakan_pokok) }}
                            <td class="t l b r" align="right">{{ number_format($j_tunggakan_jasa) }}
                        </tr>
                    @endif

                    <tr style="font-weight: bold;">
                        <td class="t l b r" colspan="9" align="left">{{ $pinkel->kode_desa }}. {{ $pinkel->nama_desa }}
                        </td>
                    </tr>

                    @php
                        $nomor = 1;
                        $j_angg = 0;
                        $j_alokasi = 0;
                        $j_saldo = 0;
                        $j_tunggakan_pokok = 0;
                        $j_tunggakan_jasa = 0;
                        $section = $pinkel->kd_desa;
                        $nama_desa = $pinkel->sebutan_desa . ' ' . $pinkel->nama_desa;
                    @endphp
                @endif

                @php
                    $saldo = $pinkel->alokasi;
                    $tunggakan = 0;
                    $sum_pokok = 0;
                    $sum_jasa = 0;

                    if ($pinkel->saldo) {
                        $saldo = $pinkel->alokasi - $pinkel->saldo->sum_pokok;
                        $sum_pokok = $pinkel->saldo->sum_pokok;
                        $sum_jasa = $pinkel->saldo->sum_jasa;
                    }

                    $tunggakan_pokok = $pinkel->target->target_pokok - $sum_pokok;
                    if ($tunggakan_pokok < 0) {
                        $tunggakan_pokok = 0;
                    }
                    $tunggakan_jasa = $pinkel->target->target_jasa - $sum_jasa;
                    if ($tunggakan_jasa < 0) {
                        $tunggakan_jasa = 0;
                    }
                @endphp
                <tr>
                    <td class="t l b" align="center">{{ $nomor++ }}</td>
                    <td class="t l b" align="left">{{ $pinkel->nama_kelompok }} - {{ $pinkel->id }}</td>
                    <td class="t l b" align="center">{{ $pinkel->pinjaman_anggota_count }}</td>
                    <td class="t l b" align="center">{{ Tanggal::tglIndo($pinkel->tgl_cair) }}</td>
                    <td class="t l b" align="center">{{ $pinkel->jangka }}/{{ $pinkel->sis_pokok->sistem }}</td>
                    <td class="t l b" align="right">{{ number_format($pinkel->alokasi) }}</td>
                    <td class="t l b" align="right">{{ number_format($saldo) }}</td>
                    <td class="t l b" align="right">{{ number_format($tunggakan_pokok) }}
                    <td class="t l b r" align="right">{{ number_format($tunggakan_jasa) }}
                </tr>

                @php
                    $j_angg += $pinkel->pinjaman_anggota_count;
                    $j_alokasi += $pinkel->alokasi;
                    $j_saldo += $saldo;
                    $j_tunggakan_pokok += $tunggakan_pokok;
                    $j_tunggakan_jasa += $tunggakan_jasa;
                @endphp
            @endforeach

            @if (count($kd_desa) > 0)
                @php
                    $t_angg += $j_angg;
                    $t_alokasi += $j_alokasi;
                    $t_saldo += $j_saldo;
                    $t_tunggakan_pokok += $j_tunggakan_pokok;
                    $t_tunggakan_jasa += $j_tunggakan_jasa;
                @endphp

                <tr>
                    <td class="t l b" colspan="5">Jumlah {{ $nama_desa }}</td>
                    <td class="t l b" align="right">{{ number_format($j_alokasi) }}</td>
                    <td class="t l b" align="right">{{ number_format($j_saldo) }}</td>
                    <td class="t l b" align="right">{{ number_format($j_tunggakan_pokok) }}
                    <td class="t l b r" align="right">{{ number_format($j_tunggakan_jasa) }}
                </tr>

                <tr>
                    <td colspan="9" style="padding: 0px !important;">
                        <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                            style="font-size: 11px;">
                            <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;" class="t l b r">
                                <td height="15" colspan="5" align="center">J U M L A H</td>
                                <td align="right" width="9%">{{ number_format($t_alokasi) }}</td>
                                <td align="right" width="9%">{{ number_format($t_saldo) }}</td>
                                <td align="right" width="9%">{{ number_format($t_tunggakan_pokok) }}
                                <td align="right" width="9%">{{ number_format($t_tunggakan_jasa) }}
                            </tr>
                        </table>

                        <div style="margin-top: 16px;"></div>
                        {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kec->ttd->tanda_tangan_pelaporan), true) !!}
                    </td>
                </tr>
            @endif
        </table>
    @endforeach
@endsection
