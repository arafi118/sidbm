@php
    use App\Utils\Tanggal;
    $section = 0;
    $empty = false;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    @foreach ($jenis_pp as $jpp)
        @php
            if ($jpp->pinjaman_anggota->isEmpty()) {
                $empty = true;
                continue;
            }

            $kd_desa = [];
            $id_kelompok = [];
            $t_alokasi = 0;
            $t_penghapusan = 0;
            $section_desa = 0;
            $section_kelompok = 0;
            $j_alokasi_desa = 0;
            $j_penghapusan_desa = 0;
            $j_alokasi_kel = 0;
            $j_penghapusan_kel = 0;
            $nama_desa = '';
            $nama_kelompok = '';
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
                        <b>DAFTAR PINJAMAN ANGGOTA {{ strtoupper($jpp->nama_jpp) }} DIHAPUS</b>
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
                    <th class="t l b" height="20" width="3%">No</th>
                    <th class="t l b" width="20%">Anggota</th>
                    <th class="t l b" width="12%">NIK</th>
                    <th class="t l b" width="10%">Tgl Cair</th>
                    <th class="t l b" width="10%">Tgl Hapus</th>
                    <th class="t l b" width="15%">Alokasi</th>
                    <th class="t l b" width="15%">Jumlah Penghapusan</th>
                    <th class="t l b r" width="15%">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jpp->pinjaman_anggota as $pinj)
                    @php
                        $kd_desa[] = $pinj->kd_desa;
                        $id_kelompok[] = $pinj->id_kel;
                        $desa = $pinj->kd_desa;
                        $kelompok = $pinj->id_kel;
                    @endphp
                    
                    {{-- Check if new desa --}}
                    @if (array_count_values($kd_desa)[$pinj->kd_desa] <= '1')
                        @if ($section_desa != $desa && count($kd_desa) > 1)
                            {{-- Print kelompok subtotal before closing previous desa --}}
                            @if ($section_kelompok > 0)
                                <tr style="font-weight: bold;" height="15">
                                    <td class="t l b" colspan="5" align="left">
                                        &nbsp;&nbsp;&nbsp;Jumlah {{ $nama_kelompok }}
                                    </td>
                                    <td class="t l b" align="right">{{ number_format($j_alokasi_kel, 2) }}</td>
                                    <td class="t l b" align="right">{{ number_format($j_penghapusan_kel, 2) }}</td>
                                    <td class="t l b r"></td>
                                </tr>
                            @endif
                            
                            {{-- Print desa subtotal --}}
                            <tr style="font-weight: bold; background: rgb(240, 240, 240);" height="15">
                                <td class="t l b" colspan="5" align="left">
                                    Jumlah {{ $nama_desa }}
                                </td>
                                <td class="t l b" align="right">{{ number_format($j_alokasi_desa, 2) }}</td>
                                <td class="t l b" align="right">{{ number_format($j_penghapusan_desa, 2) }}</td>
                                <td class="t l b r"></td>
                            </tr>
                            
                            @php
                                $t_alokasi += $j_alokasi_desa;
                                $t_penghapusan += $j_penghapusan_desa;
                            @endphp
                        @endif

                        {{-- Print desa header --}}
                        <tr style="font-weight: bold; background: rgb(200, 200, 200);">
                            <td class="t l b r" colspan="8" align="left">
                                {{ $pinj->kode_desa }}. {{ $pinj->nama_desa }}
                            </td>
                        </tr>

                        @php
                            $section_desa = $pinj->kd_desa;
                            $section_kelompok = 0;
                            $nama_desa = $pinj->sebutan_desa . ' ' . $pinj->nama_desa;
                            $j_alokasi_desa = 0;
                            $j_penghapusan_desa = 0;
                        @endphp
                    @endif
                    
                    {{-- Check if new kelompok --}}
                    @if (array_count_values($id_kelompok)[$pinj->id_kel] <= '1')
                        @if ($section_kelompok != $kelompok && $section_kelompok > 0)
                            {{-- Print kelompok subtotal --}}
                            <tr style="font-weight: bold;" height="15">
                                <td class="t l b" colspan="5" align="left">
                                    &nbsp;&nbsp;&nbsp;Jumlah {{ $nama_kelompok }}
                                </td>
                                <td class="t l b" align="right">{{ number_format($j_alokasi_kel, 2) }}</td>
                                <td class="t l b" align="right">{{ number_format($j_penghapusan_kel, 2) }}</td>
                                <td class="t l b r"></td>
                            </tr>
                        @endif

                        {{-- Print kelompok header --}}
                        <tr style="font-weight: bold;">
                            <td class="t l b r" colspan="8" align="left">
                                &nbsp;&nbsp;{{ $pinj->nama_kelompok }}
                            </td>
                        </tr>

                        @php
                            $nomor = 1;
                            $j_alokasi_kel = 0;
                            $j_penghapusan_kel = 0;
                            $section_kelompok = $pinj->id_kel;
                            $nama_kelompok = $pinj->nama_kelompok;
                        @endphp
                    @endif

                    <tr>
                        <td class="t l b" align="center">{{ $nomor++ }}</td>
                        <td class="t l b">{{ $pinj->namadepan }}</td>
                        <td class="t l b" align="center">{{ $pinj->nik }}</td>
                        <td class="t l b" align="center">{{ Tanggal::tglIndo($pinj->tgl_cair) }}</td>
                        <td class="t l b" align="center">{{ Tanggal::tglIndo($pinj->tgl_hapus) }}</td>
                        <td class="t l b" align="right">{{ number_format($pinj->alokasi, 2) }}</td>
                        <td class="t l b" align="right">{{ number_format($pinj->jumlah, 2) }}</td>
                        <td class="t l b r">{{ $pinj->catatan_verifikasi }}</td>
                    </tr>

                    @php
                        $j_alokasi_kel += $pinj->alokasi;
                        $j_penghapusan_kel += $pinj->jumlah;
                        $j_alokasi_desa += $pinj->alokasi;
                        $j_penghapusan_desa += $pinj->jumlah;
                    @endphp
                @endforeach
                
                {{-- Print last kelompok subtotal --}}
                @if ($section_kelompok > 0)
                    <tr style="font-weight: bold;" height="15">
                        <td class="t l b" colspan="5" align="left">
                            &nbsp;&nbsp;&nbsp;Jumlah {{ $nama_kelompok }}
                        </td>
                        <td class="t l b" align="right">{{ number_format($j_alokasi_kel, 2) }}</td>
                        <td class="t l b" align="right">{{ number_format($j_penghapusan_kel, 2) }}</td>
                        <td class="t l b r"></td>
                    </tr>
                @endif
                
                {{-- Print last desa subtotal --}}
                @php
                    $t_alokasi += $j_alokasi_desa;
                    $t_penghapusan += $j_penghapusan_desa;
                @endphp
                @if (count($kd_desa) > 0)
                    <tr style="font-weight: bold; background: rgb(240, 240, 240);">
                        <td class="t l b" colspan="5" align="left" height="15">
                            Jumlah {{ $nama_desa }}
                        </td>
                        <td class="t l b" align="right">{{ number_format($j_alokasi_desa, 2) }}</td>
                        <td class="t l b" align="right">{{ number_format($j_penghapusan_desa, 2) }}</td>
                        <td class="t l b r"></td>
                    </tr>

                    <tr>
                        <td colspan="8" style="padding: 0px !important;">
                            <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                                style="table-layout: fixed;">
                                <tr style="background: rgb(230, 230, 230); font-weight: bold;">
                                    <td width="55%" class="t l b" align="left" height="15">
                                        Total
                                    </td>
                                    <td width="15%" class="t l b" align="right">
                                        {{ number_format($t_alokasi, 2) }}
                                    </td>
                                    <td width="15%" class="t l b" align="right">
                                        {{ number_format($t_penghapusan, 2) }}
                                    </td>
                                    <td width="15%" class="t l b r"></td>
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
