@php
    use App\Utils\Tanggal;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    {{-- Pinjaman SPP --}}
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR KELOMPOK AKTIF SPP</b>
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

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <th height="12" width="20">No</th>
            <th width="250">Kelompok - Loan ID.</th>
            <th width="50">Angg.</th>
            <th width="50">Tgl Cair</th>
            <th>Tempo</th>
            <th width="100">Alokasi</th>
            <th width="100">Saldo</th>
            <th width="100">Tunggakan</th>
        </tr>

        @foreach ($spp as $desa)
            <tr style="font-weight: bold;">
                <td colspan="8" align="left">{{ $desa->kode_desa }}. {{ $desa->nama_desa }}</td>
            </tr>

            @php
                $nomor = 1;
                $j_angg = 0;
                $j_alokasi = 0;
                $j_saldo = 0;
                $j_tunggakan = 0;
            @endphp

            @foreach ($desa->kelompok as $kel)
                @foreach ($kel->pinkel as $pinkel)
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
                        $tunggakan = $tunggakan_pokok + $tunggakan_jasa;
                    @endphp
                    <tr>
                        <td align="center">{{ $nomor++ }}</td>
                        <td align="left">{{ $kel->nama_kelompok }} - {{ $pinkel->id }}</td>
                        <td align="center">{{ $pinkel->pinjaman_anggota_count }}</td>
                        <td align="center">{{ Tanggal::tglIndo($pinkel->tgl_cair) }}</td>
                        <td align="center">{{ $pinkel->jangka }}/{{ $pinkel->sis_pokok->sistem }}</td>
                        <td align="right">{{ number_format($pinkel->alokasi) }}</td>
                        <td align="right">{{ number_format($saldo) }}</td>
                        <td align="right">{{ number_format($tunggakan) }}
                    </tr>

                    @php
                        $j_angg += $pinkel->pinjaman_anggota_count;
                        $j_alokasi += $pinkel->alokasi;
                        $j_saldo += $saldo;
                        $j_tunggakan += $tunggakan;
                    @endphp
                @endforeach
            @endforeach

            <tr>
                <td colspan="5">Jumlah {{ $desa->sebutan_desa->sebutan_desa }} {{ $desa->nama_desa }}</td>
                <td align="right">{{ number_format($j_alokasi) }}</td>
                <td align="right">{{ number_format($j_saldo) }}</td>
                <td align="right">{{ number_format($j_tunggakan) }}
            </tr>
        @endforeach
    </table>

    {{-- Pinjaman UEP --}}
    <div class="break"></div>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR KELOMPOK AKTIF UEP</b>
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

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <th height="12" width="20">No</th>
            <th width="250">Kelompok - Loan ID.</th>
            <th width="50">Angg.</th>
            <th width="50">Tgl Cair</th>
            <th>Tempo</th>
            <th width="100">Alokasi</th>
            <th width="100">Saldo</th>
            <th width="100">Tunggakan</th>
        </tr>

        @foreach ($uep as $desa)
            <tr style="font-weight: bold;">
                <td colspan="8" align="left">{{ $desa->kode_desa }}. {{ $desa->nama_desa }}</td>
            </tr>

            @php
                $nomor = 1;
                $j_angg = 0;
                $j_alokasi = 0;
                $j_saldo = 0;
                $j_tunggakan = 0;
            @endphp

            @foreach ($desa->kelompok as $kel)
                @foreach ($kel->pinkel as $pinkel)
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
                        $tunggakan = $tunggakan_pokok + $tunggakan_jasa;
                    @endphp
                    <tr>
                        <td align="center">{{ $nomor++ }}</td>
                        <td align="left">{{ $kel->nama_kelompok }} - {{ $pinkel->id }}</td>
                        <td align="center">{{ $pinkel->pinjaman_anggota_count }}</td>
                        <td align="center">{{ Tanggal::tglIndo($pinkel->tgl_cair) }}</td>
                        <td align="center">{{ $pinkel->jangka }}/{{ $pinkel->sis_pokok->sistem }}</td>
                        <td align="right">{{ number_format($pinkel->alokasi) }}</td>
                        <td align="right">{{ number_format($saldo) }}</td>
                        <td align="right">{{ number_format($tunggakan) }}
                    </tr>

                    @php
                        $j_angg += $pinkel->pinjaman_anggota_count;
                        $j_alokasi += $pinkel->alokasi;
                        $j_saldo += $saldo;
                        $j_tunggakan += $tunggakan;
                    @endphp
                @endforeach
            @endforeach

            <tr>
                <td colspan="5">Jumlah {{ $desa->sebutan_desa->sebutan_desa }} {{ $desa->nama_desa }}</td>
                <td align="right">{{ number_format($j_alokasi) }}</td>
                <td align="right">{{ number_format($j_saldo) }}</td>
                <td align="right">{{ number_format($j_tunggakan) }}
            </tr>
        @endforeach
    </table>

    {{-- Pinjaman PL --}}
    <div class="break"></div>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR KELOMPOK AKTIF PL</b>
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

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <th height="12" width="20">No</th>
            <th width="250">Kelompok - Loan ID.</th>
            <th width="50">Angg.</th>
            <th width="50">Tgl Cair</th>
            <th>Tempo</th>
            <th width="100">Alokasi</th>
            <th width="100">Saldo</th>
            <th width="100">Tunggakan</th>
        </tr>

        @foreach ($pl as $desa)
            <tr style="font-weight: bold;">
                <td colspan="8" align="left">{{ $desa->kode_desa }}. {{ $desa->nama_desa }}</td>
            </tr>

            @php
                $nomor = 1;
                $j_angg = 0;
                $j_alokasi = 0;
                $j_saldo = 0;
                $j_tunggakan = 0;
            @endphp

            @foreach ($desa->kelompok as $kel)
                @foreach ($kel->pinkel as $pinkel)
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
                        $tunggakan = $tunggakan_pokok + $tunggakan_jasa;
                    @endphp
                    <tr>
                        <td align="center">{{ $nomor++ }}</td>
                        <td align="left">{{ $kel->nama_kelompok }} - {{ $pinkel->id }}</td>
                        <td align="center">{{ $pinkel->pinjaman_anggota_count }}</td>
                        <td align="center">{{ Tanggal::tglIndo($pinkel->tgl_cair) }}</td>
                        <td align="center">{{ $pinkel->jangka }}/{{ $pinkel->sis_pokok->sistem }}</td>
                        <td align="right">{{ number_format($pinkel->alokasi) }}</td>
                        <td align="right">{{ number_format($saldo) }}</td>
                        <td align="right">{{ number_format($tunggakan) }}
                    </tr>

                    @php
                        $j_angg += $pinkel->pinjaman_anggota_count;
                        $j_alokasi += $pinkel->alokasi;
                        $j_saldo += $saldo;
                        $j_tunggakan += $tunggakan;
                    @endphp
                @endforeach
            @endforeach

            <tr>
                <td colspan="5">Jumlah {{ $desa->sebutan_desa->sebutan_desa }} {{ $desa->nama_desa }}</td>
                <td align="right">{{ number_format($j_alokasi) }}</td>
                <td align="right">{{ number_format($j_saldo) }}</td>
                <td align="right">{{ number_format($j_tunggakan) }}
            </tr>
        @endforeach
    </table>
@endsection
