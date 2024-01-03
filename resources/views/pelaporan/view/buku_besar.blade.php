@php
    use App\Utils\Tanggal;
    $total_saldo = 0;

    if ($rek->jenis_mutasi == 'debet') {
        $saldo_awal_tahun = floatval($saldo['debit']) - floatval($saldo['kredit']);
        $saldo_awal_bulan = floatval($d_bulan_lalu) - floatval($k_bulan_lalu);
        $total_saldo = $saldo_awal_tahun + $saldo_awal_bulan;
    } else {
        $saldo_awal_tahun = floatval($saldo['kredit']) - floatval($saldo['debit']);
        $saldo_awal_bulan = floatval($k_bulan_lalu) - floatval($d_bulan_lalu);
        $total_saldo = $saldo_awal_tahun + $saldo_awal_bulan;
    }

    $total_debit = 0;
    $total_kredit = 0;

    $number = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="4" align="center">
                <div style="font-size: 18px;">
                    <b>BUKU BESAR {{ strtoupper($rek->nama_akun) }}</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4" height="5"></td>
        </tr>
    </table>

    <div style="width: 100%; text-align: right;">Kode Akun : {{ $rek->kode_akun }}</div>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;">
            <td height="15" align="center" width="4%">No</td>
            <td align="center" width="10%">Tanggal</td>
            <td align="center" width="8%">Ref ID.</td>
            <td align="center">Keterangan</td>
            <td align="center" width="13%">Debit</td>
            <td align="center" width="13%">Kredit</td>
            <td align="center" width="13%">Saldo</td>
            <td align="center" width="5%">Ins</td>
        </tr>

        <tr style="background: rgb(230, 230, 230);">
            <td align="center"></td>
            <td align="center">{{ Tanggal::tglIndo($tahun . '-01-01') }}</td>
            <td align="center"></td>
            <td>Komulatif Transaksi Awal Tahun {{ $tahun }}</td>
            <td align="right">{{ number_format($saldo['debit'], 2) }}</td>
            <td align="right">{{ number_format($saldo['kredit'], 2) }}</td>
            <td align="right">{{ number_format($saldo_awal_tahun, 2) }}</td>
            <td align="center"></td>
        </tr>
        <tr style="background: rgb(255, 255, 255);">
            <td align="center"></td>
            <td align="center">{{ Tanggal::tglIndo($tahun . '-' . $bulan . '-01') }}</td>
            <td align="center"></td>
            <td>Komulatif Transaksi s/d Bulan Lalu</td>
            <td align="right">{{ number_format($d_bulan_lalu, 2) }}</td>
            <td align="right">{{ number_format($k_bulan_lalu, 2) }}</td>
            <td align="right">{{ number_format($total_saldo, 2) }}</td>
            <td align="center"></td>
        </tr>

        @foreach ($transaksi as $trx)
            @php
                if ($trx->rekening_debit == $rek->kode_akun) {
                    $ref = substr($trx->rekening_kredit, 0, 3);
                    $debit = $trx->jumlah;
                    $kredit = 0;
                } else {
                    $ref = substr($trx->rekening_debit, 0, 3);
                    $debit = 0;
                    $kredit = $trx->jumlah;
                }

                if ($rek->jenis_mutasi == 'debet') {
                    $_saldo = $debit - $kredit;
                } else {
                    $_saldo = $kredit - $debit;
                }

                $total_saldo += $_saldo;
                $total_debit += $debit;
                $total_kredit += $kredit;
            @endphp

            @php
                if ($harian && $trx->tgl_transaksi != $tgl_kondisi) {
                    continue;
                }

                $number++;
                $bg = 'rgb(230, 230, 230)';
                if ($number % 2 == 0) {
                    $bg = 'rgba(255, 255, 255)';
                }
            @endphp

            <tr style="background: {{ $bg }};">
                <td align="center">{{ $number }}</td>
                <td align="center">{{ Tanggal::tglIndo($trx->tgl_transaksi) }}</td>
                <td align="center">{{ $ref . '-' . $trx->idt }}</td>
                <td>{{ $trx->keterangan_transaksi }}</td>
                <td align="right">{{ number_format($debit, 2) }}</td>
                <td align="right">{{ number_format($kredit, 2) }}</td>
                <td align="right">{{ number_format($total_saldo, 2) }}</td>
                @if ($trx->user)
                    <td align="center">{{ $trx->user->ins }}</td>
                @else
                    <td align="center">&nbsp;</td>
                @endif
            </tr>
        @endforeach

        <tr>
            <td colspan="8" style="padding: 0px !important;">
                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px;">
                    <tr style="background: rgb(233,233,233)">
                        <td height="12">
                            <b>Total Transaksi {{ ucwords($sub_judul) }}</b>
                        </td>
                        <td align="right" width="13%">
                            <b>{{ number_format($total_debit, 2) }}</b>
                        </td>
                        <td align="right" width="13%">
                            <b>{{ number_format($total_kredit, 2) }}</b>
                        </td>
                        <td align="center" rowspan="3" width="18%">
                            <b>{{ number_format($total_saldo, 2) }}</b>
                        </td>
                    </tr>

                    <tr style="background: rgb(255,255,255)">
                        <td height="12">
                            <b>Total Transaksi sampai dengan {{ ucwords($sub_judul) }}</b>
                        </td>
                        <td align="right">
                            <b>{{ number_format($d_bulan_lalu + $total_debit, 2) }}</b>
                        </td>
                        <td align="right">
                            <b>{{ number_format($k_bulan_lalu + $total_kredit, 2) }}</b>
                        </td>
                    </tr>

                    <tr style="background: rgb(233,233,233)">
                        <td height="12">
                            <b>Total Transaksi Komulatif sampai dengan Tahun {{ $tahun }}</b>
                        </td>
                        <td align="right">
                            <b>{{ number_format($saldo['debit'] + $d_bulan_lalu + $total_debit, 2) }}</b>
                        </td>
                        <td align="right">
                            <b>{{ number_format($saldo['kredit'] + $k_bulan_lalu + $total_kredit, 2) }}</b>
                        </td>
                    </tr>
                </table>

                <div style="margin-top: 16px;"></div>
                {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kec->ttd->tanda_tangan_pelaporan), true) !!}
            </td>
        </tr>

    </table>
@endsection
