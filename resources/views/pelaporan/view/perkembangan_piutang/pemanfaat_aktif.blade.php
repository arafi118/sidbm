@php
    use App\Utils\Tanggal;
    $section = 0;
    $id_pinkel = 0;
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

            $loan_id = [];
            $kd_desa = [];
            $t_pencairan = 0;
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
            <thead>
                <tr>
                    <th class="t l b" height="5%" width="5%">No</th>
                    <th class="t l b" width="10%">NIK</th>
                    <th class="t l b" width="10%">Nomor KK</th>
                    <th class="t l b" width="20%">Nama Anggota</th>
                    <th class="t l b" width="30%">Alamat</th>
                    <th class="t l b" width="10%">Tgl Cair</th>
                    <th class="t l b r" width="15%">Alokasi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($jpp->pinjaman_anggota as $pinj)
                    @php
                        $loan_id[] = $pinj->id_pinkel;
                        $kd_desa[] = $pinj->kd_desa;
                        $desa = $pinj->kd_desa;
                        $id_pinj = $pinj->id_pinkel;
                        $tampil_jumlah_pemanfaat = true;
                    @endphp

                    @if (array_count_values($kd_desa)[$pinj->kd_desa] <= '1')
                        @if ($section != $desa && count($kd_desa) > 1)
                            @if ($id_pinkel != $id_pinj && count($loan_id) > 1)
                                <tr style="font-weight: bold;">
                                    <td class="t l b" colspan="6">
                                        Jumlah Alokasi Kelompok {{ $nama_kelompok }}
                                    </td>
                                    <td class="t l b r" align="right">
                                        {{ number_format($j_alokasi_kelompok) }}
                                    </td>
                                </tr>
                                @php
                                    $tampil_jumlah_pemanfaat = false;
                                @endphp
                            @endif

                            @php
                                $t_pencairan += $j_pencairan;
                            @endphp
                            <tr style="font-weight: bold;">
                                <td class="t l b" colspan="6">Jumlah {{ $nama_desa }}</td>
                                <td class="t l b r" align="right">{{ number_format($j_pencairan) }}</td>
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

                    @if (array_count_values($loan_id)[$pinj->id_pinkel] <= '1')
                        @if ($id_pinkel != $id_pinj && count($loan_id) > 1 && $tampil_jumlah_pemanfaat)
                            <tr style="font-weight: bold;">
                                <td class="t l b" colspan="6">
                                    Jumlah Alokasi Kelompok {{ $nama_kelompok }}
                                </td>
                                <td class="t l b r" align="right">
                                    {{ number_format($j_alokasi_kelompok) }}
                                </td>
                            </tr>
                        @endif

                        <tr style="font-weight: bold;">
                            <td class="t l b r" colspan="7" align="left">
                                {{ $nomor++ }}. {{ $pinj->nama_kelompok }} - Loan ID. {{ $pinj->id_pinkel }}
                            </td>
                        </tr>

                        @php
                            $nom = 1;
                            $id_pinkel = $pinj->id_pinkel;
                            $nama_kelompok = $pinj->nama_kelompok;
                            $j_alokasi_kelompok = 0;
                        @endphp
                    @endif

                    <tr>
                        <td class="t l b" align="center">{{ $nom++ }}</td>
                        <td class="t l b" align="center" style="mso-number-format:\@;">{{ $pinj->nik }}</td>
                        <td class="t l b" align="center">{{ $pinj->kk }}</td>
                        <td class="t l b">{{ $pinj->namadepan }}</td>
                        <td class="t l b">
                            {{ $pinj->alamat }} {{ $pinj->sebutan_desa }} {{ $pinj->nama_desa }}
                        </td>
                        <td class="t l b" align="center">{{ Tanggal::tglIndo($pinj->tgl_cair) }}</td>
                        <td class="t l b r" align="right">{{ number_format($pinj->alokasi) }}</td>
                    </tr>

                    @php
                        $j_pencairan += $pinj->alokasi;
                        $j_alokasi_kelompok += $pinj->alokasi;
                    @endphp
                @endforeach

                @if (count($kd_desa) > 0)
                    <tr style="font-weight: bold;">
                        <td class="t l b" colspan="6">
                            Jumlah Alokasi Kelompok {{ $nama_kelompok }}
                        </td>
                        <td class="t l b r" align="right">
                            {{ number_format($j_alokasi_kelompok) }}
                        </td>
                    </tr>

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
                                <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;" class="t l b r">
                                    <td height="15" width="85%">
                                        J U M L A H
                                    </td>
                                    <td align="right" width="15%">{{ number_format($t_pencairan) }}
                                </tr>
                            </table>

                            <div style="margin-top: 16px;"></div>
                            {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kec->ttd->tanda_tangan_pelaporan), true) !!}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endforeach
@endsection
