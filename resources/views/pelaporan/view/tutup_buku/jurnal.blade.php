@php
    use App\Utils\Tanggal;

    $data_idtp = [];
    $tgl_trx = [];

    $number = 1;
    $debit = 0;
    $kredit = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="7" align="center">
                <div style="font-size: 18px;">
                    <b>JURNAL PENUTUP</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="7" height="5"></td>
        </tr>
        <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;">
            <td height="15" align="center" width="4%">No</td>
            <td align="center" width="10%">Tanggal</td>
            <td align="center" width="8%">Ref ID.</td>
            <td align="center" width="8%">Kd. Rek</td>
            <td align="center" width="35%">Keterangan</td>
            <td align="center" width="15%">Debit</td>
            <td align="center" width="15%">Kredit</td>
            <td align="center" width="5%">Ins</td>
        </tr>

        @foreach ($saldo as $trx)
            @php
                $rek_debit = $rek->nama_akun;
                $rek_kredit = $trx->rek->nama_akun;

                $bg = 'rgba(255, 255, 255)';
                if ($number % 2 == 0) {
                    $bg = 'rgb(230, 230, 230)';
                }

                $jumlah = $trx->debit - $trx->kredit;
                if ($trx->rek->lev1 < 5) {
                    $jumlah = $trx->kredit - $trx->debit;
                }
            @endphp
            <tr style="background: {{ $bg }};">
                <td height="15" align="center">{{ $number }}.</td>
                <td align="center">{{ Tanggal::tglIndo($tgl_transaksi) }}</td>
                <td align="left">{{ substr($trx->id, 0, 6) }}</td>
                <td align="center">{{ $rek->kode_akun }}</td>
                <td align="left">{{ $rek_debit }}</td>
                <td align="right">{{ number_format($jumlah, 2) }}</td>
                <td align="right">&nbsp;</td>
                <td align="center">&nbsp;</td>
            </tr>
            <tr style="background: {{ $bg }};">
                <td height="15" align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="left">{{ substr($trx->id, 0, 6) }}</td>
                <td align="center">{{ $trx->kode_akun }}</td>
                <td align="left">{{ $rek_kredit }}</td>
                <td align="right">&nbsp;</td>
                <td align="right">{{ number_format($jumlah, 2) }}</td>
                <td align="center">&nbsp;</td>
            </tr>

            @php
                if ($trx->rek->lev1 < 5) {
                    $debit += $jumlah;
                    $kredit += $jumlah;
                } else {
                    $debit -= $jumlah;
                    $kredit -= $jumlah;
                }
            @endphp

            @php
                $number++;
            @endphp
        @endforeach

        <tr>
            <td colspan="8" style="padding: 0px !important;">
                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px;">
                    <tr style="background: rgb(233, 233, 233); font-weight: bold; color: #000;">
                        <td height="15" align="center">
                            <b>Total Transaksi</b>
                        </td>
                        <td align="right" width="15%">{{ number_format($debit, 2) }}</td>
                        <td align="right" width="15%">{{ number_format($kredit, 2) }}</td>
                        <td align="center" width="5%">&nbsp;</td>
                    </tr>
                </table>

                <div style="margin-top: 16px;"></div>
                {!! $tanda_tangan !!}
            </td>
        </tr>
    </table>
@endsection
