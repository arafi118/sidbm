@extends('pelaporan.layout.base')

@section('content')
<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td height="20">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4" align="center">
            <div style="font-size: 18px;">
                <b>LAPORAN LABA RUGI</b>
            </div>
            <div style="font-size: 16px;">
                <b>{{ strtoupper($sub_judul) }}</b>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4" height="5"></td>
    </tr>
    <tr>
        <td align="center" width="255">Rekening</td>
        <td align="center" width="70">s.d. Bulan Lalu</td>
        <td align="center" width="70">Bulan Ini</td>
        <td align="center" width="70">s.d. Bulan Ini</td>
    </tr>
    <tr>
        <td colspan="4">A. Pendapatan</td>
    </tr>
    @foreach ($pendapatan as $pend)
    <tr>
        <td align="left" width="230">{{ $pend->kode_akun }}. {{ $pend->nama_akun }}</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
    </tr>

    @foreach ($pend->rek as $rek)
    <tr>
        <td align="left" width="230">{{ $rek->kode_akun }}. {{ $rek->nama_akun }}</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
    </tr>
    @endforeach
    @endforeach
</table>
@endsection
