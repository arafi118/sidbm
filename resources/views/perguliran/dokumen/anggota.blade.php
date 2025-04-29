@php
    use App\Utils\Tanggal;
    $no = 0;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" styzle="font-size: 14px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR ANGGOTA</b>
                </div>
                <div style="font-size: 16px; text-decoration: underline;">
                    <b>KELOMPOK {{ strtoupper($pinkel->kelompok->nama_kelompok) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tr style="background: rgb(232, 232, 232)">
            <th width="10" height="20" align="center">No</th>
            <th width="80" align="center">NIK</th>
            <th width="130" align="center">Nama anggota</th>
            <th width="70" align="center">No HP</th>
            <th align="center">Alamat</th>
        </tr>
        @foreach ($pinjaman as $pa)
            @php
                $no = $loop->iteration;
            @endphp
            <tr>
                <td align="center">{{ $no }}.</td>
                <td align="center">{{ $pa->anggota->nik }}</td>
                <td>{{ $pa->anggota->namadepan }}</td>
                <td align="center">{{ $pa->anggota->hp }}</td>
                <td>{{ $pa->anggota->alamat }}</td>
            </tr>
        @endforeach

        @for ($i = $no + 1; $i <= 30; $i++)
            <tr>
                <td align="center">{{ $i }}.</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        @endfor
    </table>

    <div id="last-element"></div>
    <div id="position"></div>
    <script>
        var lastElement = document.getElementById("last-element");
        var lastElementY = lastElement.getBoundingClientRect().top;
        document.getElementById("position").textContent = "Y Position of Last Element: " + lastElementY;
    </script>
@endsection
