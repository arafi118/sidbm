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
                    <b>JURNAL TRANSAKSI</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="7" height="5"></td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <thead>
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
        </thead>

        <tbody>
            @foreach ($transaksi as $trx)
                @php
                    $data_idtp[] = $trx->idtp;

                    if (
                        $trx->idtp != '0' &&
                        array_count_values($data_idtp)[$trx->idtp] > 1 &&
                        $trx->tgl_transaksi == $tgl_trx[$trx->idtp]
                    ) {
                        continue;
                    }
                    $tgl_trx[$trx->idtp] = $trx->tgl_transaksi;

                    $bg = 'rgba(255, 255, 255)';
                    if ($number % 2 == 0) {
                        $bg = 'rgb(230, 230, 230)';
                    }

                @endphp

                @if ($trx->idtp != '0')
                    @php
                        $jumlah_angs = 0;
                    @endphp

                    @foreach ($trx->angs as $angs)
                        @php
                            $jumlah_angs += $angs->jumlah;
                        @endphp
                    @endforeach

                    <tr style="background: {{ $bg }};">
                        <td height="15" align="center">{{ $number }}.</td>
                        <td align="center">{{ Tanggal::tglIndo($trx->tgl_transaksi) }}</td>
                        <td align="left">{{ $trx->idtp }}.0</td>
                        <td align="center">{{ $trx->rekening_debit }}</td>
                        <td align="left">{{ $trx->rek_debit->nama_akun }}</td>
                        <td align="right">{{ number_format($jumlah_angs, 2) }}</td>
                        <td align="right">&nbsp;</td>
                        @if ($trx->user)
                            <td align="center">{{ $trx->user->ins }}</td>
                        @else
                            <td align="center">&nbsp;</td>
                        @endif
                    </tr>

                    @foreach ($trx->angs as $angs)
                        <tr style="background: {{ $bg }};">
                            <td height="15" align="center">&nbsp;</td>
                            <td align="center">&nbsp;</td>
                            <td align="left">{{ $trx->idtp }}.{{ $angs->idt }}</td>
                            <td align="center">{{ $angs->rekening_kredit }}</td>
                            <td align="left">{{ $angs->rek_kredit->nama_akun }}</td>
                            <td align="right">&nbsp;</td>
                            <td align="right">{{ number_format($angs->jumlah, 2) }}</td>
                            @if ($trx->user)
                                <td align="center">{{ $trx->user->ins }}</td>
                            @else
                                <td align="center">&nbsp;</td>
                            @endif
                        </tr>

                        @php
                            $kredit += $angs->jumlah;
                        @endphp
                    @endforeach

                    @php
                        $debit += $jumlah_angs;
                    @endphp
                @else
                    @php
                        $rek_debit = '';
                        $rek_kredit = '';

                        if ($trx->rek_debit) {
                            $rek_debit = $trx->rek_debit->nama_akun;
                        }
                        if ($trx->rek_kredit) {
                            $rek_kredit = $trx->rek_kredit->nama_akun;
                        }
                    @endphp
                    <tr style="background: {{ $bg }};">
                        <td height="15" align="center">{{ $number }}.</td>
                        <td align="center">{{ Tanggal::tglIndo($trx->tgl_transaksi) }}</td>
                        <td align="left">{{ $trx->idt }}</td>
                        <td align="center">{{ $trx->rekening_debit }}</td>
                        <td align="left">{{ $rek_debit }}</td>
                        <td align="right">{{ number_format(floatval($trx->jumlah), 2) }}</td>
                        <td align="right">&nbsp;</td>
                        @if ($trx->user)
                            <td align="center">{{ $trx->user->ins }}</td>
                        @else
                            <td align="center">&nbsp;</td>
                        @endif
                    </tr>
                    <tr style="background: {{ $bg }};">
                        <td height="15" align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="left">{{ $trx->idt }}</td>
                        <td align="center">{{ $trx->rekening_kredit }}</td>
                        <td align="left">{{ $rek_kredit }}</td>
                        <td align="right">&nbsp;</td>
                        <td align="right">{{ number_format(floatval($trx->jumlah), 2) }}</td>
                        @if ($trx->user)
                            <td align="center">{{ $trx->user->ins }}</td>
                        @else
                            <td align="center">&nbsp;</td>
                        @endif
                    </tr>

                    @php
                        $debit += floatval($trx->jumlah);
                        $kredit += floatval($trx->jumlah);
                    @endphp
                @endif

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
        </tbody>
    </table>
@endsection
