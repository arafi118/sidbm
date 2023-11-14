@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR KELOMPOK PELUNASAN 3 BULAN KEDEPAN</b>
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
        <tr>
            <th rowspan="2" width="4%">No</th>
            <th rowspan="2" width="12%">Nama Kelompok</th>
            <th rowspan="2" width="10%">Ketua</th>
            <th rowspan="2" width="15%">Alamat</th>
            <th rowspan="2" width="8%">Telpon</th>
            <th rowspan="2" width="8%">Tgl Cair</th>
            <th rowspan="2" width="6%">Jangka</th>
            <th rowspan="2" width="6%">Jatuh Tempo</th>
            <th rowspan="2" width="6%">Sisa Waktu</th>
            <th colspan="3" width="25%">Sisa Angsuran</th>
        </tr>
        <tr>
            <th width="8%">Pokok</th>
            <th width="8%">Jasa</th>
            <th width="8%">Jumlah</th>
        </tr>

        <tr>
            <td></td>
        </tr>
    </table>
@endsection
