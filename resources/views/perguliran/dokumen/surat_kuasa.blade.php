@php
    use App\Utils\Tanggal;
    $minus = 0;

    $ketua = $pinkel->kelompok->ketua;
    $sekretaris = $pinkel->kelompok->sekretaris;
    $bendahara = $pinkel->kelompok->bendahara;
    if ($pinkel->struktur_kelompok) {
        $struktur_kelompok = json_decode($pinkel->struktur_kelompok, true);
        $ketua = isset($struktur_kelompok['ketua']) ? $struktur_kelompok['ketua'] : '';
        $sekretaris = isset($struktur_kelompok['sekretaris']) ? $struktur_kelompok['sekretaris'] : '';
        $bendahara = isset($struktur_kelompok['bendahara']) ? $struktur_kelompok['bendahara'] : '';
    }
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>SURAT KUASA</b>
                </div>
                <div style="font-size: 16px; text-decoration: underline;">
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

        @php
            $data_pemanfaat = [];
        @endphp
        @foreach ($pinkel->pinjaman_anggota as $pa)
            @php
                if ($pa->alokasi == 0) {
                    $minus += 1;
                    continue;
                }

                $no = $loop->iteration - $minus;
                $data_pemanfaat[] = $pa;
            @endphp

            <tr>
                <td height="15" class="l b" align="center">{{ $no }}</td>
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
            <td class="l b">{{ $ketua }}</td>
            <td class="l b">Ketua</td>
            <td class="l b r">
                {{ $pinkel->kelompok->alamat_kelompok }}
            </td>
        </tr>
        <tr>
            <td height="15" class="l b" align="center">2</td>
            <td class="l b">{{ $sekretaris }}</td>
            <td class="l b">Sekretaris</td>
            <td class="l b r">
                {{ $pinkel->kelompok->alamat_kelompok }}
            </td>
        </tr>
        <tr>
            <td height="15" class="l b" align="center">3</td>
            <td class="l b">{{ $bendahara }}</td>
            <td class="l b">Bendahara</td>
            <td class="l b r">
                {{ $pinkel->kelompok->alamat_kelompok }}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div style="text-align: justify;">
                    Untuk menandatangani Surat Perjanjian Kredit (SPK) dan seluruh dokumen perjanjian sebagai bagian yang
                    tidak terpisahkan dari Surat Perjanjian Kredit (SPK)
                    kepada {{ $kec->nama_lembaga_sort }} {{ $kec->sebutan_kec }} {{ $kec->nama_kec }}.
                </div>

                <div style="text-align: justify;">
                    Berkaitan dengan pemberian kuasa ini, kami seluruh anggota kelompok
                    {{ $pinkel->kelompok->nama_kelompok }}
                    menyatakan bersedia menanggung segala resiko dan tanggungjawab yang muncul sebagai akibat
                    ditandatanganinya Surat perjanjian Kredit (SPK) tersebut.
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
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

    @php
        $batas_pemanfaat = ceil(count($data_pemanfaat) / 2);
    @endphp

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
                            <td width="25%" height="20">
                                @if (isset($data_pemanfaat[$j]))
                                    {{ $i }}. {{ $data_pemanfaat[$j]->anggota->namadepan }}
                                @endif
                            </td>
                            <td class="vb" width="25%">
                                @if (isset($data_pemanfaat[$j]))
                                    ..............................................
                                @endif
                            </td>
                            <td width="25%">
                                @if (isset($data_pemanfaat[$j + $batas_pemanfaat]))
                                    {{ $i + $batas_pemanfaat }}.
                                    {{ $data_pemanfaat[$j + $batas_pemanfaat]->anggota->namadepan }}
                                @endif
                            </td>
                            <td class="vb" width="25%">
                                @if (isset($data_pemanfaat[$j + $batas_pemanfaat]))
                                    ..............................................
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
                            {{ $ketua }}
                        </td>
                        <td align="center" width="33%">
                            {{ $sekretaris }}
                        </td>
                        <td align="center" width="33%">
                            {{ $bendahara }}
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
