@extends('pelaporan.layout.base')

@section('content')
<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td colspan="3" align="center">
            <div><b>NERACA</b></div>
            <div><b>{{ strtoupper($sub_judul) }}</b></div>
        </td>
    </tr>
    <tr>
        <td colspan="3" height="5"></td>
    </tr>
    <tr style="background: #000; color: #fff;">
        <td width="60">Kode</td>
        <td width="290">Nama Akun</td>
        <td align="right">Saldo</td>
    </tr>
    <tr>
        <td colspan="3" height="1"></td>
    </tr>
    <tr>
        <td width="50">Kode</td>
        <td width="300">Nama Akun</td>
        <td align="right">Saldo</td>
    </tr>
</table>
@endsection
