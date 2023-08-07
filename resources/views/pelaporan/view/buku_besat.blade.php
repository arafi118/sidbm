@extends('pelaporan.layout.base')

@section('content')
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
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

<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
    <tr>
        <td>No</td>
        <td>Tanggal</td>
        <td>Ref ID.</td>
        <td>Keterangan</td>
        <td>Debit</td>
        <td>Kredit</td>
        <td>Saldo</td>
        <td>Ins</td>
    </tr>
</table>
@endsection
