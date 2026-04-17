@php
    use App\Utils\Tanggal;
    $section = 0;
    $empty = false;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    @foreach ($jenis_pp as $jpp)
        @php
            if ($jpp->pinjaman_kelompok->isEmpty()) {
                $empty = true;
                continue;
            }

            $kd_desa = [];
            $t_alokasi = 0;
            $nomor = 1;
        @endphp

        @if ($jpp->nama_jpp != 'SPP' && !$empty)
            <div class="break"></div>
            @php
                $empty = false;
            @endphp
        @endif

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td colspan="3" align="center">
                    <div style="font-size: 18px;">
                        <b>
                            DAFTAR PINJAMAN TIDAK LAYAK {{ strtoupper($jpp->nama_jpp) }}
                        </b>
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
            <thead>
                <tr style="background: rgb(230, 230, 230); font-weight: bold;">
                    <th class="t l b" width="5%" height="20">No</th>
                    <th class="t l b" width="25%">Nama Kelompok</th>
                    <th class="t l b" width="30%">Alamat</th>
                    <th class="t l b" width="20%">Tanggal Tunggu</th>
                    <th class="t l b r" width="20%">Alokasi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($jpp->pinjaman_kelompok as $pinkel)
                    @php
                        $kd_desa[] = $pinkel->kd_desa;
                        $desa = $pinkel->kd_desa;

                        $jenis_pinjaman = $pinkel->jenis_pp < 3 ? 'Kelompok' : '';
                        $alokasi_kelompok = $pinkel->alokasi;
                        $t_alokasi += $alokasi_kelompok;
                    @endphp

                    @if (array_count_values($kd_desa)[$pinkel->kd_desa] <= '1')
                        <tr style="font-weight: bold;">
                            <td class="t l b r" colspan="5" align="left" height="15">
                                {{ $pinkel->kode_desa }}. {{ $pinkel->nama_desa }}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td class="t l b" align="center">{{ $nomor++ }}</td>
                        <td class="t l b" align="left">
                            {{ $jenis_pinjaman }} {{ $pinkel->nama_kelompok }} - {{ $pinkel->id }}
                        </td>
                        <td class="t l b" align="left">
                            {{ $pinkel->sebutan_desa }} {{ $pinkel->nama_desa }}
                        </td>
                        <td class="t l b" align="center">{{ Tanggal::tglIndo($pinkel->tgl_tunggu) }}</td>
                        <td class="t l b r" align="right">{{ number_format($alokasi_kelompok, 2) }}</td>
                    </tr>
                @endforeach

                @if (count($kd_desa) > 0)
                    <tr>
                        <td colspan="5" style="padding: 0px !important;">
                            <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                                style=" table-layout: fixed;">
                                <tr style="background: rgb(230, 230, 230); font-weight: bold;">
                                    <td class="t l b" align="center" width="80%" height="15">
                                        J U M L A H
                                    </td>
                                    <td class="t l b r" width="20%" align="right">
                                        {{ number_format($t_alokasi, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        <div style="margin-top: 16px;"></div>
                                        {!! $tanda_tangan !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endforeach
@endsection
