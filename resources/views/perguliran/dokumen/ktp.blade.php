@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px; text-decoration: underline">
                    <b>FC KTP PEMANFAAT DAN PENJAMIN</b>
                </div>
                <div style="font-size: 16px;">
                    <b>KELOMPOK {{ strtoupper($pinkel->kelompok->nama_kelompok) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
@endsection
