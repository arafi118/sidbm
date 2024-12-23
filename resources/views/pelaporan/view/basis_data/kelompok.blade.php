@php
    use App\Utils\Tanggal;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR KELOMPOK</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($kec->nama_lembaga_sort) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <thead>
            <tr>
                <th height="4%" width="3%">No</th>
                <th width="10%">Kode Kelompok</th>
                <th width="16%">Nama Kelompok</th>
                <th width="9%">Fungsi Kelompok</th>
                <th width="23%">Alamat</th>
                <th width="9%">Telpon</th>
                <th width="10%">Ketua</th>
                <th width="10%">Sekretaris</th>
                <th width="10%">Bendahara</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($desa as $ds)
                <tr style="font-weight: bold;">
                    <td colspan="8" align="left">
                        {{ $ds->kode_desa }}. {{ $ds->sebutan_desa->sebutan_desa }} {{ $ds->nama_desa }}
                    </td>
                </tr>

                @php
                    $no = 0;
                @endphp
                @foreach ($ds->kelompok as $kel)
                    <tr>
                        <td align="center">{{ ++$no }}</td>
                        <td align="center" style="mso-number-format:\@;">{{ $kel->kd_kelompok }}</td>
                        <td>{{ $kel->nama_kelompok }}</td>
                        <td align="left">{{ $kel->tk->nama_tk }}</td>
                        <td>{{ $kel->alamat_kelompok }}</td>
                        <td align="center">{{ $kel->telpon }}</td>
                        <td align="left">{{ $kel->ketua }}</td>
                        <td align="left">{{ $kel->sekretaris }}</td>
                        <td align="left">{{ $kel->bendahara }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@endsection
