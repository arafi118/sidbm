@php
    use App\Utils\Tanggal;
    $waktu = '';
    $tempat = '';
    $wt_cair = explode('_', $pinkel->wt_cair);
    if (count($wt_cair) == 1) {
        $waktu = $wt_cair[0];
    }

    if (count($wt_cair) == 2) {
        $waktu = $wt_cair[0];
        $tempat = $wt_cair[1];
    }

    $minus = 0;
    $no = 0;

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
            <td align="center">
                <div style="font-size: 18px; text-decoration: underline;">
                    <b>DAFTAR HADIR PENCAIRAN {{ $pinkel->jpp->nama_jpp }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td height="5"></td>
        </tr>
    </table>
    <table border="0" width="100%" align="center"cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td width="70">Nomor Proposal</td>
            <td align="center" width="5">:</td>
            <td width="150"><b>{{ $pinkel->kelompok->kd_kelompok }} - {{ $pinkel->id }}</b></td>
            <td width="70">Tanggal</td>
            <td align="center" width="5">:</td>
            <td width="150">
                <b>{{ Tanggal::tglLatin($pinkel->tgl_cair) }}</b>
            </td>
        </tr>
        <tr>
            <td>Kelompok</td>
            <td align="center">:</td>
            <td><b>{{ $pinkel->kelompok->nama_kelompok }}</b></td>
            <td>Waktu</td>
            <td align="center">:</td>
            <td>
                <b>{{ $waktu }}</b>
            </td>
        </tr>
        <tr>
            <td>{{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}/Kecamatan</td>
            <td align="center">:</td>
            <td><b>{{ $pinkel->kelompok->d->nama_desa }} / {{ $kec->nama_kec }}</b></td>
            <td>Tempat</td>
            <td align="center">:</td>
            <td>
                <b>{{ $tempat }}</b>
            </td>
        </tr>
        <tr>
            <td colspan="6">&nbsp;</td>
        </tr>
    </table>
    <table border="1" width="100%" align="center"cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <th width="10" height="20" align="center">No</th>
            <th width="130" align="center">Nama Lengkap</th>
            <th width="70" align="center">Unsur / Jabatan</th>
            <th align="center">Alamat</th>
            <th width="80" align="center">Tanda Tangan</th>
        </tr>
        @foreach ($pinkel->pinjaman_anggota as $pa)
            @php
                if ($pa->alokasi == 0) {
                    $minus += 1;
                    continue;
                }

                $no = $loop->iteration - $minus;
            @endphp
            <tr>
                <td height="15" align="center"><b>{{ $no }}.</b></td>
                <td>{{ $pa->anggota->namadepan }}</td>
                <td align="center">Pemanfaat</td>
                <td>{{ $pa->anggota->alamat }}</td>
                <td>{{ $no }}.</td>
            </tr>
        @endforeach

        @for ($i = $no + 1; $i <= 20; $i++)
            <tr>
                <td height="15" align="center"><b>{{ $i }}.</b></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ $i }}.</td>
            </tr>
        @endfor
    </table>
    <table border="0" width="100%" align="center"cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td width="66%">&nbsp;</td>
            <td width="33%" align="center">Mengetahui,</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center">Ketua Kelompok {{ $pinkel->kelompok->nama_kelompok }}</td>
        </tr>
        <tr>
            <td colspan="2" height="50">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center">
                <u>
                    <b>{{ $ketua }}</b>
                </u>
            </td>
        </tr>
    </table>
@endsection
