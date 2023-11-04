@extends('pelaporan.layout.base')

@section('content')
    <style>
        html {
            margin-left: 40px;
            margin-right: 40px;
        }
    </style>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>LAPORAN PENGGUNAAN DANA (E-BUDGETING)</b>
                </div>
                <div style="font-size: 16px;">
                    <b>TAHUN ANGGARAN {{ $tahun }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
@endsection
