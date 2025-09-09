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
            $t_penghapusan = 0;
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
                        <b>DAFTAR PINJAMAN {{ strtoupper($jpp->nama_jpp) }} DIHAPUS</b>
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
            <thead>
                <tr style="background: rgb(230, 230, 230); font-weight: bold;">
                    <th class="t l b" height="20" width="4%">No</th>
                    <th class="t l b" width="20%">Kelompok - Loan ID.</th>
                    <th class="t l b" width="10%">Tgl Cair</th>
                    <th class="t l b" width="10%">Tgl Hapus</th>
                    <th class="t l b" width="15%">Alokasi</th>
                    <th class="t l b" width="15%">Jumlah Penghapusan</th>
                    <th class="t l b r" width="26%">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jpp->pinjaman_kelompok as $pinkel)
                    @php
                        $kd_desa[] = $pinkel->kd_desa;
                        $desa = $pinkel->kd_desa;
                    @endphp
                    @if (array_count_values($kd_desa)[$pinkel->kd_desa] <= '1')
                        @if ($section != $desa && count($kd_desa) > 1)
                            @php
                                $t_alokasi += $j_alokasi;
                                $t_penghapusan += $j_penghapusan;
                            @endphp
                            <tr style="font-weight: bold;" height="15">
                                <td class="t l b" colspan="4" align="left">
                                    Jumlah {{ $nama_desa }}
                                </td>
                                <td class="t l b" align="right">{{ number_format($j_alokasi, 2) }}</td>
                                <td class="t l b" align="right">{{ number_format($j_penghapusan, 2) }}</td>
                                <td class="t l b r"></td>
                            </tr>
                        @endif

                        <tr style="font-weight: bold;">
                            <td class="t l b r" colspan="7" align="left">
                                {{ $pinkel->kode_desa }}. {{ $pinkel->nama_desa }}
                            </td>
                        </tr>

                        @php
                            $nomor = 1;
                            $j_alokasi = 0;
                            $j_penghapusan = 0;
                            $section = $pinkel->kd_desa;
                            $nama_desa = $pinkel->sebutan_desa . ' ' . $pinkel->nama_desa;
                        @endphp
                    @endif

                    <tr>
                        <td class="t l b" align="center">{{ $nomor++ }}</td>
                        <td class="t l b">{{ $pinkel->nama_kelompok }} - {{ $pinkel->id }}</td>
                        <td class="t l b" align="center">{{ Tanggal::tglIndo($pinkel->tgl_cair) }}</td>
                        <td class="t l b" align="center">{{ Tanggal::tglIndo($pinkel->tgl_hapus) }}</td>
                        <td class="t l b" align="right">{{ number_format($pinkel->alokasi, 2) }}</td>
                        <td class="t l b" align="right">{{ number_format($pinkel->jumlah, 2) }}</td>
                        <td class="t l b r">{{ $pinkel->catatan_verifikasi }}</td>
                    </tr>

                    @php
                        $j_alokasi += $pinkel->alokasi;
                        $j_penghapusan += $pinkel->jumlah;
                    @endphp
                @endforeach
                @php
                    $t_alokasi += $j_alokasi;
                    $t_penghapusan += $j_penghapusan;
                @endphp
                @if (count($kd_desa) > 0)
                    <tr style="font-weight: bold;">
                        <td class="t l b" colspan="4" align="left" height="15">
                            Jumlah {{ $nama_desa }}
                        </td>
                        <td class="t l b" align="right">{{ number_format($j_alokasi, 2) }}</td>
                        <td class="t l b" align="right">{{ number_format($j_penghapusan, 2) }}</td>
                        <td class="t l b r"></td>
                    </tr>

                    <tr>
                        <td colspan="7" style="padding: 0px !important;">
                            <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                                style="table-layout: fixed;">
                                <tr style="background: rgb(230, 230, 230); font-weight: bold;">
                                    <td width="44%" class="t l b" align="left" height="15">
                                        Total
                                    </td>
                                    <td width="15%" class="t l b" align="right">
                                        {{ number_format($t_alokasi, 2) }}
                                    </td>
                                    <td width="15%" class="t l b" align="right">
                                        {{ number_format($t_penghapusan, 2) }}
                                    </td>
                                    <td width="26%" class="t l b r"></td>
                                </tr>

                                <tr>
                                    <td colspan="4">
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
