@php
    use App\Utils\Tanggal;

    $section = 0;
    $t_pokok = 0;
    $t_jasa = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR KELOMPOK PELUNASAN 3 BULAN KEDEPAN</b>
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

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px; table-layout: fixed;">
        <tr>
            <th class="t l b" rowspan="2" width="4%">No</th>
            <th class="t l b" rowspan="2" width="12%">Nama Kelompok</th>
            <th class="t l b" rowspan="2" width="10%">Ketua</th>
            <th class="t l b" rowspan="2" width="15%">Alamat</th>
            <th class="t l b" rowspan="2" width="9%">Telpon</th>
            <th class="t l b" rowspan="2" width="8%">Tgl Cair</th>
            <th class="t l b" rowspan="2" width="5%">Jangka</th>
            <th class="t l b" rowspan="2" width="8%">Jatuh Tempo</th>
            <th class="t l b" rowspan="2" width="5%">Sisa Waktu</th>
            <th class="t l b r" colspan="3">Sisa Angsuran</th>
        </tr>
        <tr>
            <th class="t l b" width="8%">Pokok</th>
            <th class="t l b" width="8%">Jasa</th>
            <th class="t l b r" width="8%">Jumlah</th>
        </tr>

        @foreach ($pinjaman_kelompok as $pinkel)
            @php
                $kd_desa[] = $pinkel->kd_desa;
                $desa = $pinkel->kd_desa;
            @endphp
            @if (array_count_values($kd_desa)[$pinkel->kd_desa] <= '1')
                @if ($section != $desa && count($kd_desa) > 1)
                    @php
                        $t_pokok += $j_pokok;
                        $t_jasa += $j_jasa;
                    @endphp
                    <tr style="font-weight: bold;">
                        <td class="t l b" colspan="9" align="left" height="15">
                            Jumlah {{ $nama_desa }}
                        </td>
                        <td class="t l b" align="right">{{ number_format($j_pokok) }}</td>
                        <td class="t l b" align="right">{{ number_format($j_jasa) }}</td>
                        <td class="t l b r" align="right">{{ number_format($j_pokok + $j_jasa) }}</td>
                    </tr>
                @endif

                <tr style="font-weight: bold;">
                    <td class="t l b r" colspan="12" align="left">
                        {{ $pinkel->kode_desa }}. {{ $pinkel->nama_desa }}
                    </td>
                </tr>

                @php
                    $nomor = 1;
                    $j_pokok = 0;
                    $j_jasa = 0;
                    $section = $pinkel->kd_desa;
                    $nama_desa = $pinkel->sebutan_desa . ' ' . $pinkel->nama_desa;
                @endphp
            @endif

            @php
                $sum_pokok = 0;
                $sum_jasa = 0;
                if ($pinkel->saldo) {
                    $sum_pokok = $pinkel->saldo->sum_pokok;
                    $sum_jasa = $pinkel->saldo->sum_jasa;
                }
            @endphp

            <tr>
                <td class="t l b" align="center">{{ $nomor++ }}</td>
                <td class="t l b" align="left">
                    {{ $pinkel->nama_kelompok }} - {{ $pinkel->id }}
                </td>
                <td class="t l b" align="left">
                    {{ $pinkel->ketua }}
                </td>
                <td class="t l b" align="left">
                    {{ $pinkel->alamat_kelompok }} {{ $pinkel->sebutan_desa }} {{ $pinkel->desa }}
                </td>
                <td class="t l b" align="center">
                    {{ $pinkel->telpon }}
                </td>
                <td class="t l b" align="center">
                    {{ Tanggal::tglIndo($pinkel->tgl_cair) }}
                </td>
                <td class="t l b" align="center">
                    {{ $pinkel->jangka }}
                </td>
                <td class="t l b" align="center">
                    @php
                        $jatuh_tempo = date('Y-m-d', strtotime('+' . $pinkel->jangka . ' month', strtotime($pinkel->tgl_cair)));
                    @endphp

                    {{ Tanggal::tglIndo($jatuh_tempo) }}
                </td>
                <td class="t l b" align="center">
                    {{ $pinkel->sisa * -1 }}
                </td>
                <td class="t l b" align="right">{{ number_format($sum_pokok) }}</td>
                <td class="t l b" align="right">{{ number_format($sum_jasa) }}</td>
                <td class="t l b r" align="right">{{ number_format($sum_pokok + $sum_jasa) }}</td>
            </tr>

            @php
                $j_pokok += $sum_pokok;
                $j_jasa += $sum_jasa;
            @endphp
        @endforeach

        @php
            $t_pokok += $j_pokok;
            $t_jasa += $j_jasa;
        @endphp
        <tr style="font-weight: bold;">
            <td class="t l b" colspan="9" align="left" height="15">
                Jumlah {{ $nama_desa }}
            </td>
            <td class="t l b" align="right">{{ number_format($j_pokok) }}</td>
            <td class="t l b" align="right">{{ number_format($j_jasa) }}</td>
            <td class="t l b r" align="right">{{ number_format($j_pokok + $j_jasa) }}</td>
        </tr>

        <tr>
            <td colspan="12" style="padding: 0px !important;">
                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 8px;">
                    <tr style="font-weight: bold;">
                        <td class="t l b" align="left" height="20" width="76%">
                            J U M L A H
                        </td>
                        <td width="8%" class="t l b" align="right">{{ number_format($t_pokok) }}</td>
                        <td width="8%" class="t l b" align="right">{{ number_format($t_jasa) }}</td>
                        <td width="8%" class="t l b r" align="right">{{ number_format($t_pokok + $t_jasa) }}</td>
                    </tr>

                    <tr>
                        <td colspan="4">
                            <div style="margin-top: 16px;"></div>
                            {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kec->ttd->tanda_tangan_pelaporan), true) !!}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
