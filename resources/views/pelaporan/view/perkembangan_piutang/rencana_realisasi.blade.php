@php
    use App\Utils\Tanggal;
    $section = 0;
    $jenis_produk_pinjaman = 0;
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
                continue;
            }

            $jenis_produk_pinjaman += 1;
        @endphp

        @php
            $kd_desa = [];

            $t_kelompok = 0;
            $t_pemanfaat = 0;
            $t_pengajuan = 0;
            $t_pencairan = 0;
        @endphp

        @if ($jpp->nama_jpp != 'SPP' && $jenis_produk_pinjaman > 1)
            <div class="break"></div>
        @endif

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
            <tr>
                <td colspan="3" align="center">
                    <div style="font-size: 18px;">
                        <b>LAPORAN REALISASI PENCAIRAN KELOMPOK {{ $jpp->nama_jpp }}</b>
                    </div>
                    <div style="font-size: 16px;">
                        <b style="text-transform: uppercase;">
                            {{ strtoupper($sub_judul) }}
                        </b>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" height="5"></td>
            </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <th class="t l b" rowspan="2" width="5%">No</th>
                <th class="t l b" rowspan="2" width="23%">Kelompok - Load ID</th>
                <th class="t l b" rowspan="2" width="20%">Nomor SPK</th>
                <th class="t l b" rowspan="2" width="12%">Ketua Kelompok</th>
                <th class="t l b" rowspan="2" width="5%">Ang</th>
                <th class="t l b" rowspan="2" width="8%">Tgl Cair</th>
                <th class="t l b" rowspan="2" width="5%">T/S</th>
                <th class="t l b r" colspan="2" width="22%">Alokasi</th>
            </tr>
            <tr>
                <th class="t l b" width="11%">Pengajuan</th>
                <th class="t l b r" width="11%">Pencairan</th>
            </tr>

            @foreach ($jpp->pinjaman_kelompok as $pinkel)
                @php
                    $kd_desa[] = $pinkel->kd_desa;
                    $desa = $pinkel->kd_desa;
                @endphp

                @if (array_count_values($kd_desa)[$pinkel->kd_desa] <= '1')
                    @if ($section != $desa && count($kd_desa) > 1)
                        @php
                            $t_kelompok += $j_kelompok;
                            $t_pemanfaat += $j_pemanfaat;
                            $t_pengajuan += $j_pengajuan;
                            $t_pencairan += $j_pencairan;
                        @endphp
                        <tr style="font-weight: bold;">
                            <td class="t l b" colspan="4" align="center" height="15">
                                Jumlah Kelompok {{ $nama_desa }} ({{ $j_kelompok }})
                            </td>
                            <td class="t l b" align="center">{{ $j_pemanfaat }}</td>
                            <td class="t l b" align="right" colspan="2">&nbsp;</td>
                            <td class="t l b" align="right">
                                {{ number_format($j_pengajuan, 2) }}
                            </td>
                            <td class="t l b r" align="right">
                                {{ number_format($j_pencairan, 2) }}
                            </td>
                        </tr>
                    @endif

                    <tr style="font-weight: bold;">
                        <td class="t l b r" colspan="9" align="left">
                            {{ $pinkel->kode_desa }}. {{ $pinkel->nama_desa }}
                        </td>
                    </tr>

                    @php
                        $nomor = 1;

                        $section = $pinkel->kd_desa;
                        $nama_desa = $pinkel->sebutan_desa . ' ' . $pinkel->nama_desa;
                        $j_kelompok = 0;
                        $j_pemanfaat = 0;
                        $j_pengajuan = 0;
                        $j_pencairan = 0;
                    @endphp
                @endif

                <tr>
                    <td class="t l b" align="center">{{ $nomor++ }}</td>
                    <td class="t l b">{{ $pinkel->nama_kelompok }} - {{ $pinkel->id }}</td>
                    <td class="t l b">{{ $pinkel->spk_no }}</td>
                    <td class="t l b">{{ $pinkel->ketua }}</td>
                    <td class="t l b" align="center">{{ $pinkel->pinjaman_anggota_count }}</td>
                    <td class="t l b" align="center">{{ Tanggal::tglIndo($pinkel->tgl_cair) }}</td>
                    <td class="t l b" align="center">{{ $pinkel->jangka }}/{{ $pinkel->sis_pokok->sistem }}</td>
                    <td class="t l b" align="right">{{ number_format($pinkel->proposal) }}</td>
                    <td class="t l b r" align="right">{{ number_format($pinkel->alokasi) }}</td>
                </tr>

                @php
                    $j_kelompok += 1;
                    $j_pemanfaat += $pinkel->pinjaman_anggota_count;
                    $j_pengajuan += $pinkel->proposal;
                    $j_pencairan += $pinkel->alokasi;
                @endphp
            @endforeach

            @if (count($kd_desa) > 0)
                @php
                    $t_kelompok += $j_kelompok;
                    $t_pemanfaat += $j_pemanfaat;
                    $t_pengajuan += $j_pengajuan;
                    $t_pencairan += $j_pencairan;
                @endphp
                <tr style="font-weight: bold;">
                    <td class="t l b" colspan="4" align="center" height="15">
                        Jumlah Kelompok {{ $nama_desa }} ({{ $j_kelompok }})
                    </td>
                    <td class="t l b" align="center">{{ $j_pemanfaat }}</td>
                    <td class="t l b" align="right" colspan="2">&nbsp;</td>
                    <td class="t l b" align="right">
                        {{ number_format($j_pengajuan, 2) }}
                    </td>
                    <td class="t l b r" align="right">
                        {{ number_format($j_pencairan, 2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="9" style="padding: 0px !important;">
                        <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                            style="font-size: 11px; table-layout: fixed;">
                            <tr style="font-weight: bold;">
                                <td class="t l b" colspan="4" align="center" height="15" width="60%">
                                    J U M L A H ({{ $t_kelompok }})
                                </td>
                                <td class="t l b" align="center" width="5%">{{ $t_pemanfaat }}</td>
                                <td class="t l b" align="right" width="13%">&nbsp;</td>
                                <td class="t l b" align="right" width="11%">
                                    {{ number_format($t_pengajuan, 2) }}
                                </td>
                                <td class="t l b r" align="right" width="11%">
                                    {{ number_format($t_pencairan, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="8">
                                    <div style="margin-top: 16px;"></div>
                                    {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kec->ttd->tanda_tangan_pelaporan), true) !!}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endif
        </table>
    @endforeach
@endsection
