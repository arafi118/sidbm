@php
    use App\Utils\Tanggal;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR LEMBAGA LAIN</b>
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
        <tr>
            <th height="4%" width="3%">No</th>
            <th width="14%">Kode Lembaga</th>
            <th width="20%">Nama Lembaga</th>
            <th width="25%">Alamat</th>
            <th width="10%">Telpon</th>
            <th width="13%">Nama Pimpinan</th>
            <th width="15%">Penanggung Jawab</th>
        </tr>
        @foreach ($desa as $ds)
            <tr style="font-weight: bold;">
                <td colspan="7" align="left">
                    {{ $ds->kode_desa }}. {{ $ds->sebutan_desa->sebutan_desa }} {{ $ds->nama_desa }}
                </td>
            </tr>

            @php
                $no = 0;
            @endphp
            @foreach ($ds->kelompok as $kel)
                <tr>
                    <td align="center">{{ ++$no }}</td>
                    <td align="center">{{ $kel->kd_kelompok }}</td>
                    <td>{{ $kel->nama_kelompok }}</td>
                    <td>{{ $kel->alamat_kelompok }}</td>
                    <td align="center">{{ $kel->telpon }}</td>
                    <td align="left">{{ $kel->ketua }}</td>
                    <td align="left">{{ $kel->sekretaris }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>
@endsection
