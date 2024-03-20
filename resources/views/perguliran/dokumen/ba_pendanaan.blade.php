@php
    use App\Utils\Tanggal;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px; table-layout: fixed;">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="20%">Nama Kelompok</th>
                <th width="">Alamat</th>
                <th width="9%">Jenis Pinjaman</th>
                <th width="8%">Anggota</th>
                <th width="15%">Ketua Kelompok</th>
                <th width="15%">Alokasi Pendanaan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($pinjaman as $pinkel)
                <tr>
                    <td align="center">
                        <b>{{ $loop->iteration }}.</b>
                    </td>
                    <td>
                        {{ $pinkel->kelompok->nama_kelompok }}
                    </td>
                    <td>
                        {{ $pinkel->kelompok->alamat_kelompok }} {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
                        {{ $pinkel->kelompok->d->nama_desa }}
                    </td>
                    <td align="center">
                        {{ $pinkel->jpp->nama_jpp }}
                    </td>
                    <td align="center">
                        {{ $pinkel->pinjaman_anggota_count }}
                    </td>
                    <td>
                        {{ $pinkel->kelompok->ketua }}
                    </td>
                    <td align="right">
                        {{ number_format($pinkel->alokasi) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
