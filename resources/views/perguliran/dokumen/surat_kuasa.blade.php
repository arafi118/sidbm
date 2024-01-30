@php
    use App\Utils\Tanggal;
    $batas_pemanfaat = ceil($pinkel->pinjaman_anggota_count / 2);
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr class="b">
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>SURAT KUASA</b>
                </div>
                <div style="font-size: 16px;">
                    PENANDATANGANAN SPK
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="4" align="justify">
                Yang bertanda tangan di bawah ini, kami para anggota Kelompok {{ $pinkel->kelompok->nama_kelompok }} alamat
                {{ $pinkel->kelompok->alamat_kelompok }} {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
                {{ $pinkel->kelompok->d->nama_desa }} {{ $kec->sebutan_kec }} {{ $kec->nama_kec }} {{ $nama_kabupaten }} :
            </td>
        </tr>
        <tr style="background: rgb(232, 232, 232)">
            <th class="b l t" width="10" height="20">No</th>
            <th class="b l t" width="140">Nama Anggota</th>
            <th class="b l t" width="80">Nik</th>
            <th class="b l t r">Alamat</th>
        </tr>

        @foreach ($pinkel->pinjaman_anggota as $pa)
            <tr>
                <td height="15" class="l b" align="center">{{ $loop->iteration }}</td>
                <td class="l b">{{ $pa->anggota->namadepan }}</td>
                <td class="l b" align="center">{{ $pa->anggota->nik }}</td>
                <td class="l b r">
                    {{ $pa->anggota->alamat }} {{ $pa->anggota->d->sebutan_desa->sebutan_desa }}
                    {{ $pa->anggota->d->nama_desa }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4">
                Memberikan kuasa sepenuhnya kepada pengurus kelompok :
            </td>
        </tr>
        <tr style="background: rgb(232, 232, 232)">
            <th class="b l t" width="10" height="20">No</th>
            <th class="b l t" width="140">Nama Anggota</th>
            <th class="b l t" width="80">Jabatan</th>
            <th class="b l t r">Alamat</th>
        </tr>
        <tr>
            <td height="15" class="l b" align="center">1</td>
            <td class="l b">{{ $pinkel->kelompok->ketua }}</td>
            <td class="l b">Ketua</td>
            <td class="l b r">
                {{ $pinkel->kelompok->alamat_kelompok }}
            </td>
        </tr>
        <tr>
            <td height="15" class="l b" align="center">2</td>
            <td class="l b">{{ $pinkel->kelompok->sekretaris }}</td>
            <td class="l b">Sekretaris</td>
            <td class="l b r">
                {{ $pinkel->kelompok->alamat_kelompok }}
            </td>
        </tr>
        <tr>
            <td height="15" class="l b" align="center">3</td>
            <td class="l b">{{ $pinkel->kelompok->bendahara }}</td>
            <td class="l b">Bendahara</td>
            <td class="l b r">
                {{ $pinkel->kelompok->alamat_kelompok }}
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td align="center">
                {{ $kec->nama_kec }}, {{ Tanggal::tglLatin($pinkel->tgl_cair) }}
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td align="center">
                Anggota Kelompok selaku pemberi kuasa :
            </td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td style="padding: 0px !important;">
                <table border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px; margin-top: 8px;" class="p0">
                    @for ($i = 1; $i <= $batas_pemanfaat; $i++)
                        @php
                            $j = $i - 1;
                        @endphp
                        <tr>
                            <td width="25%">
                                @if (isset($pinkel->pinjaman_anggota[$j]))
                                    {{ $i }}. {{ $pinkel->pinjaman_anggota[$j]->anggota->namadepan }}
                                @endif
                            </td>
                            <td width="25%">
                                @if (isset($pinkel->pinjaman_anggota[$j]))
                                    ................................
                                @endif
                            </td>
                            <td width="25%">
                                @if (isset($pinkel->pinjaman_anggota[$j + $batas_pemanfaat]))
                                    {{ $i + $batas_pemanfaat }}.
                                    {{ $pinkel->pinjaman_anggota[$j + $batas_pemanfaat]->anggota->namadepan }}
                                @endif
                            </td>
                            <td width="25%">
                                @if (isset($pinkel->pinjaman_anggota[$j + $batas_pemanfaat]))
                                    ................................
                                @endif
                            </td>
                        </tr>
                    @endfor
                </table>

                <table border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px; margin-top: 8px;" class="p0">
                    <tr>
                        <td colspan="3" align="center">
                            Pengurus kelompok Selaku Penerima Kuasa :
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" height="50"></td>
                    </tr>
                    <tr>
                        <td align="center" width="33%">
                            {{ $pinkel->kelompok->ketua }}
                        </td>
                        <td align="center" width="33%">
                            {{ $pinkel->kelompok->sekretaris }}
                        </td>
                        <td align="center" width="33%">
                            {{ $pinkel->kelompok->bendahara }}
                        </td>
                    </tr>
                    <tr>
                        <td align="center">Ketua</td>
                        <td align="center">Sekretaris</td>
                        <td align="center">Bendahara</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
