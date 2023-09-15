@php
    use App\Utils\Tanggal;
    $section = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    @foreach ($jenis_pp as $jpp)
        @php
            $kd_desa = [];
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

        <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr style="background: rgb(230, 230, 230); font-weight: bold;">
                <th height="12" width="20">No</th>
                <th width="250">Kelompok - Loan ID.</th>
                <th width="50">Angg.</th>
                <th width="50">Tgl Cair</th>
                <th>Tempo</th>
                <th width="100">Alokasi</th>
                <th width="100">Saldo</th>
                <th width="100">Tunggakan</th>
            </tr>

            @foreach ($jpp->pinjaman_kelompok as $pinkel)
                @php
                    $kd_desa[] = $pinkel->kd_desa;
                    $desa = $pinkel->kd_desa;
                @endphp
                @if (array_count_values($kd_desa)[$pinkel->kd_desa] <= '1')
                    @if ($section != $desa && count($kd_desa) > 1)
                        <tr>
                            <td colspan="5">Jumlah {{ $nama_desa }}</td>
                            <td align="right">{{ number_format($j_alokasi) }}</td>
                            <td align="right">{{ number_format($j_saldo) }}</td>
                            <td align="right">{{ number_format($j_tunggakan) }}
                        </tr>
                    @endif

                    <tr style="font-weight: bold;">
                        <td colspan="8" align="left">{{ $pinkel->kode_desa }}. {{ $pinkel->nama_desa }}</td>
                    </tr>

                    @php
                        $nomor = 1;
                        $j_angg = 0;
                        $j_alokasi = 0;
                        $j_saldo = 0;
                        $j_tunggakan = 0;
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
                    $tunggakan = $tunggakan_pokok + $tunggakan_jasa;
                @endphp
                <tr>
                    <td align="center">{{ $nomor++ }}</td>
                    <td align="left">{{ $pinkel->nama_kelompok }} - {{ $pinkel->id }}</td>
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
        </table>
    @endforeach
@endsection
