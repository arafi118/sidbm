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
                <th class="t l b" height="20" width="5%">No</th>
                <th class="t l b" width="25%">Nama Kelompok</th>
                <th class="t l b" width="10%">NIK</th>
                <th class="t l b" width="20%">Nama Anggota</th>
                <th class="t l b" width="22%">Alamat</th>
                <th class="t l b" width="8%">Tgl Cair</th>
                <th class="t l b r" width="10%">Alokasi</th>
            </tr>

            @foreach ($jpp->pinjaman_anggota as $pinj)
                @php
                    $kd_desa[] = $pinj->kd_desa;
                    $desa = $pinj->kd_desa;
                @endphp

                @if (array_count_values($kd_desa)[$pinj->kd_desa] <= '1')
                    @if ($section != $desa && count($kd_desa) > 1)
                        @php
                            $t_pencairan += $j_pencairan;
                        @endphp
                        <tr style="font-weight: bold;">
                            <td class="t l b" colspan="6">Jumlah {{ $nama_desa }}</td>
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
                    <td class="t l b" align="center">{{ $pinj->nik }}</td>
                    <td class="t l b">{{ $pinj->namadepan }}</td>
                    <td class="t l b">{{ $pinj->alamat }}</td>
                    <td class="t l b" align="center">{{ Tanggal::tglIndo($pinj->tgl_cair) }}</td>
                    <td class="t l b r" align="right">{{ number_format($pinj->alokasi) }}</td>
                </tr>

                @php
                    $j_pencairan += $pinj->alokasi;
                @endphp
            @endforeach

            @if (count($kd_desa) > 0)
                @php
                    $t_pencairan += $j_pencairan;
                @endphp
                <tr style="font-weight: bold;">
                    <td class="t l b" colspan="6">Jumlah {{ $nama_desa }}</td>
                    <td class="t l b r" align="right">{{ number_format($j_pencairan) }}
                </tr>

                <tr>
                    <td colspan="7" style="padding: 0px !important;">
                        <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                            style="font-size: 11px;">
                            <tr style="font-weight: bold;">
                                <td class="t l b" colspan="6" width="90%">
                                    J U M L A H
                                </td>
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
