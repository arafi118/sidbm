@php
    use App\Utils\Tanggal;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <style>
        .break {
            page-break-after: always;
        }
    </style>
    @foreach ($pinkel->pinjaman_anggota as $pinj)
        @if ($loop->iteration > 1)
            <div class="break"></div>
        @endif

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <div style="font-size: 16px; text-decoration: underline;">
                        SURAT PERNYATAAN AHLI WARIS
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" height="5"></td>
            </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td colspan="3">
                    Yang bertanda tangan dibawah ini,
                </td>
            </tr>
            <tr>
                <td width="28%">Nama Penjamin</td>
                <td width="2%" align="center">:</td>
                <td width="70%">{{ $pinj->anggota->penjamin }}</td>
            </tr>
            <tr>
                <td>Nik/No KK.</td>
                <td align="center">:</td>
                <td>{{ $pinj->anggota->nik_penjamin }}/{{ $pinj->anggota->kk }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td align="center">:</td>
                <td>
                    {{ $pinj->anggota->alamat }}
                    {{ $pinj->anggota->d->sebutan_desa->sebutan_desa }} {{ $pinj->anggota->d->nama_desa }}
                </td>
            </tr>
            <tr>
                <td>Hubungan dengan Peminjam</td>
                <td align="center">:</td>
                <td>
                    @if ($pinj->anggota->keluarga)
                        {{ $pinj->anggota->keluarga->kekeluargaan }}
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="3" align="justify">
                    <p>
                        Adalah benar-benar ahli waris dari <b>{{ $pinj->anggota->namadepan }}</b> Dengan ini menyatakan
                        bersedia menanggung beban piutang {{ $pinkel->jpp->nama_jpp }} sampai lunas. Apabila terjadi
                        hal-hal yang tidak diinginkan yang menyebabkan peminjam tidak bisa melunasi kewajibannya seperti :
                        Meninggal Dunia, Melarikan Diri, Berpindah domisili di luar desa, gangguan kejiwaan, sakit parah,
                        dll.
                    </p>
                    <p>
                        Demikian Surat Pernyataan Ahli Waris ini saya buat tanpa ada paksaan dari pihak manapun.
                    </p>
                </td>
            </tr>
        </table>

        @if ($tanda_tangan)
            @php
                $tanda_tangan_anggota = Pinjaman::keyword(json_encode($tanda_tangan), [
                    'kec' => $kec,
                    'jenis_laporan' => $jenis_laporan,
                    'tgl_kondisi' => $tgl_kondisi,
                    'pinkel' => $pinkel,
                    'pinjaman_anggota' => $pinj,
                ]);
            @endphp
            <div style="font-size: 14px;">
                {!! $tanda_tangan_anggota !!}
            </div>
        @else
            <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
                <tr>
                    <td align="center" width="50%">&nbsp;</td>
                    <td align="center" width="50%">{{ $kec->nama_kec }}, {{ Tanggal::tglLatin($pinkel->tgl_cair) }}</td>
                </tr>
                <tr>
                    <td align="center">Peminjam</td>
                    <td align="center">Nama Penjamin</td>
                </tr>

                <tr>
                    <td align="center" colspan="2" height="30">&nbsp;</td>
                </tr>
                <tr style="font-weight: bold;">
                    <td align="center">{{ $pinj->anggota->namadepan }}</td>
                    <td align="center">{{ $pinj->anggota->penjamin }}</td>
                </tr>
            </table>
        @endif
    @endforeach
@endsection
