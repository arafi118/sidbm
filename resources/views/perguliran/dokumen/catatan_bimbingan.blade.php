@php
    use App\Utils\Tanggal;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <style>
        .break {
            page-break-after: always;
        }

        p {
            padding: 0;
            margin: 0;
        }
    </style>
    @foreach ($catatan as $ct)
        @if ($loop->iteration > 1)
            <div class="break"></div>
        @endif

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td colspan="3" align="center">
                    <div style="font-size: 18px;">
                        <b>CATATAN BIMBINGAN KELOMPOK</b>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" height="5"></td>
            </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td width="22%">Nama Kelompok</td>
                <td width="2%" align="center">:</td>
                <td width="25%">{{ $pinkel->kelompok->nama_kelompok }} - {{ $pinkel->id }}</td>

                <td width="2%">&nbsp;</td>

                <td width="22%">Pengajuan</td>
                <td width="2%" align="center">:</td>
                <td width="25%">Rp. {{ number_format($pinkel->proposal) }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td align="center">:</td>
                <td>{{ $pinkel->kelompok->alamat_kelompok }}</td>

                <td>&nbsp;</td>

                <td>Pencairan</td>
                <td align="center">:</td>
                <td>Rp. {{ number_format($pinkel->alokasi) }}</td>
            </tr>
            <tr>
                <td>Tanggal Cair</td>
                <td align="center">:</td>
                <td>{{ Tanggal::tglLatin($pinkel->tgl_cair) }}</td>

                <td>&nbsp;</td>

                <td>Jumlah Pemanfaat</td>
                <td align="center">:</td>
                <td>
                    @if ($pinkel->pinjaman_anggota_count > 0)
                        {{ $pinkel->pinjaman_anggota_count }} orang
                    @else
                        Tidak ada
                    @endif
                </td>
            </tr>
            <tr>
                <td>Nomor SPK</td>
                <td align="center">:</td>
                <td>{{ $pinkel->spk_no }}</td>

                <td colspan="4">&nbsp;</td>
            </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td width="22%">Hasil Catatan Bimbingan</td>
                <td width="2%" align="center">:</td>
                <td width="26%">&nbsp;</td>
                <td width="50%">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4">
                    {!! $ct['catatan'] !!}
                </td>
            </tr>

            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>

            <tr>
                <td colspan="3">&nbsp;</td>
                <td align="center">{{ $kec->nama_kec }}, {{ Tanggal::tglLatin($ct['tanggal']) }}</td>
            </tr>
            <tr>
                <td colspan="4" height="40">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
                <td align="center">{{ $users[$ct['user']] }}</td>
            </tr>
        </table>
    @endforeach
@endsection
