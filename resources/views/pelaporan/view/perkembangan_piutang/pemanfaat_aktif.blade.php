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
            $t_pengajuan = 0;
            $t_pencairan = 0;
        @endphp
        @if ($jpp->nama_jpp != 'SPP')
            <div class="break"></div>
        @endif

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td colspan="3" align="center">
                    <div style="font-size: 18px;">
                        <b>DAFTAR PEMANFAAT AKTIF {{ strtoupper($jpp->nama_jpp) }}</b>
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
            <tr>
                <th class="t l b" rowspan="2" width="5%">No</th>
                <th class="t l b" rowspan="2" width="25%">Nama Kelompok</th>
                <th class="t l b" rowspan="2" width="20%">Nama Anggota</th>
                <th class="t l b" rowspan="2" width="22%">Alamat</th>
                <th class="t l b" rowspan="2" width="8%">Tgl Cair</th>
                <th class="t l b r" colspan="2" width="20%">Alokasi</th>
            </tr>
            <tr>
                <th class="t l b" width="10%">Pengajuan</th>
                <th class="t l b r" width="10%">Pencairan</th>
            </tr>

            @foreach ($jpp->pinjaman_anggota as $pinj)
                @php
                    $kd_desa[] = $pinj->kd_desa;
                    $desa = $pinj->kd_desa;
                @endphp

                @if (array_count_values($kd_desa)[$pinj->kd_desa] <= '1')
                    @if ($section != $desa && count($kd_desa) > 1)
                        @php
                            $t_pengajuan += $j_pengajuan;
                            $t_pencairan += $j_pencairan;
                        @endphp
                        <tr style="font-weight: bold;">
                            <td class="t l b" colspan="5">Jumlah {{ $nama_desa }}</td>
                            <td class="t l b" align="right">{{ number_format($j_pengajuan) }}
                            <td class="t l b r" align="right">{{ number_format($j_pencairan) }}
                        </tr>
                    @endif

                    <tr style="font-weight: bold;">
                        <td class="t l b r" colspan="7" align="left">
                            {{ $pinj->kode_desa }}. {{ $pinj->nama_desa }}
                        </td>
                    </tr>

                    @php
                        $nomor = 1;
                        $section = $pinj->kd_desa;
                        $nama_desa = $pinj->sebutan_desa . ' ' . $pinj->nama_desa;
                        $j_pengajuan = 0;
                        $j_pencairan = 0;
                    @endphp
                @endif

                <tr>
                    <td class="t l b" align="center">{{ $nomor++ }}</td>
                    <td class="t l b">{{ $pinj->nama_kelompok }} - Loan ID. {{ $pinj->id_pinkel }}</td>
                    <td class="t l b">{{ $pinj->namadepan }}</td>
                    <td class="t l b">{{ $pinj->alamat }}</td>
                    <td class="t l b" align="center">{{ Tanggal::tglIndo($pinj->tgl_cair) }}</td>
                    <td class="t l b" align="right">{{ number_format($pinj->proposal) }}</td>
                    <td class="t l b r" align="right">{{ number_format($pinj->alokasi) }}</td>
                </tr>

                @php
                    $j_pengajuan += $pinj->proposal;
                    $j_pencairan += $pinj->alokasi;
                @endphp
            @endforeach

            @if (count($kd_desa) > 0)
                @php
                    $t_pengajuan += $j_pengajuan;
                    $t_pencairan += $j_pencairan;
                @endphp
                <tr style="font-weight: bold;">
                    <td class="t l b" colspan="5">Jumlah {{ $nama_desa }}</td>
                    <td class="t l b" align="right">{{ number_format($j_pengajuan) }}
                    <td class="t l b r" align="right">{{ number_format($j_pencairan) }}
                </tr>

                <tr>
                    <td colspan="7" style="padding: 0px !important;">
                        <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                            style="font-size: 11px;">
                            <tr style="font-weight: bold;">
                                <td class="t l b" colspan="5" width="80%">
                                    J U M L A H
                                </td>
                                <td class="t l b" align="right" width="10%">{{ number_format($t_pengajuan) }}
                                <td class="t l b r" align="right" width="10%">{{ number_format($t_pencairan) }}
                            </tr>
                        </table>

                        <div style="margin-top: 24px;"></div>
                        {!! json_decode($kec->ttd->tanda_tangan_pelaporan, true) !!}
                    </td>
                </tr>
            @endif
        </table>
    @endforeach
@endsection
