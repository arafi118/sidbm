@php
use App\Utils\Tanggal;

$data_idtp = [];
$tgl_trx = [];

$number = 1;
@endphp

@extends('pelaporan.layout.base')

@section('content')
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
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
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
    <tr>
        <td colspan="7" height="5"></td>
    </tr>
    <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;">
        <td height="15" align="center" width="10">No</td>
        <td align="center" width="35">Tanggal</td>
        <td align="center" width="35">Ref ID.</td>
        <td align="center" width="35">Kd. Rek</td>
        <td align="center" width="175">Keterangan</td>
        <td align="center" width="60">Debit</td>
        <td align="center" width="60">Kredit</td>
        <td align="center">Ins</td>
    </tr>

    @foreach ($transaksi as $trx)

    @php
    $data_idtp[] = $trx->idtp;

    if ($trx->idtp != '0' && array_count_values($data_idtp)[$trx->idtp] > 1 && $trx->tgl_transaksi ==
    $tgl_trx[$trx->idtp]) {
    continue;
    }
    $tgl_trx[$trx->idtp] = $trx->tgl_transaksi;

    $bg = 'rgba(255, 255, 255)';
    if ($number % 2 == 0) {
    $bg = 'rgb(230, 230, 230)';
    }

    @endphp

    @if ($trx->idtp != '0')

    <tr style="background: {{ $bg }};">
        <td height="15" align="center" width="10">{{ $number }}.</td>
        <td align="center">{{ Tanggal::tglIndo($trx->tgl_transaksi) }}</td>
        <td align="left">{{ $trx->idtp }}.0</td>
        <td align="center">{{ $trx->rekening_debit }}</td>
        <td align="left">{{ $trx->rek_debit->nama_akun }}</td>
        <td align="right">{{ number_format($trx->angs_sum_jumlah, 2) }}</td>
        <td align="right">&nbsp;</td>
        <td align="center">{{ $trx->user->ins }}</td>
    </tr>

    @foreach ($trx->angs as $angs)
    <tr style="background: {{ $bg }};">
        <td height="15" align="center" width="10">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="left">{{ $trx->idtp }}.{{ $angs->idt }}</td>
        <td align="center">{{ $angs->rekening_kredit }}</td>
        <td align="left">{{ $angs->rek_kredit->nama_akun }}</td>
        <td align="right">&nbsp;</td>
        <td align="right">{{ number_format($angs->jumlah, 2) }}</td>
        <td align="center">{{ $angs->user->ins }}</td>
    </tr>
    @endforeach

    @else
    <tr style="background: {{ $bg }};">
        <td height="15" align="center" width="10">{{ $number }}.</td>
        <td align="center">{{ Tanggal::tglIndo($trx->tgl_transaksi) }}</td>
        <td align="left">{{ $trx->idt }}</td>
        <td align="center">{{ $trx->rekening_debit }}</td>
        <td align="left">{{ $trx->rek_debit->nama_akun }}</td>
        <td align="right">{{ number_format($trx->jumlah, 2) }}</td>
        <td align="right">&nbsp;</td>
        <td align="center">{{ $trx->user->ins }}</td>
    </tr>
    <tr style="background: {{ $bg }};">
        <td height="15" align="center" width="10">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="left">{{ $trx->idt }}</td>
        <td align="center">{{ $trx->rekening_kredit }}</td>
        <td align="left">{{ $trx->rek_kredit->nama_akun }}</td>
        <td align="right">&nbsp;</td>
        <td align="right">{{ number_format($trx->jumlah, 2) }}</td>
        <td align="center">{{ $trx->user->ins }}</td>
    </tr>
    @endif

    @php
    $number++;
    @endphp

    @endforeach

</table>

@endsection
