@php
    use App\Utils\Tanggal;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR PENDUDUK</b>
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
            <th height="3%" width="5%">No</th>
            <th width="10%">NIK</th>
            <th width="17%">Nama Anggota</th>
            <th width="23%">Alamat</th>
            <th width="20%">Tgl Lahir</th>
            <th width="9%">Telpon</th>
            <th width="18%">Usaha</th>
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
            @foreach ($ds->anggota as $ang)
                <tr>
                    <td align="center">{{ ++$no }}</td>
                    <td align="center">{{ $ang->nik }}</td>
                    <td>{{ $ang->namadepan }}</td>
                    <td>{{ $ang->alamat }}</td>
                    <td align="left">
                        @if ($ang->tgl_lahir)
                            {{ $ang->tempat_lahir }}, {{ Tanggal::tglLatin($ang->tgl_lahir) }}
                        @endif
                    </td>
                    <td align="center">{{ $ang->hp }}</td>
                    <td align="left">
                        @if (is_numeric($ang->usaha))
                            {{ $ang->u->nama_usaha }}
                        @else
                            {{ $ang->usaha }}
                        @endif
                    </td>
                </tr>
            @endforeach
        @endforeach
    </table>
@endsection
