@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr class="b">
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR PESERTA ASURANSI {{ strtoupper($kec->nama_asuransi_p) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>

    <table border="0" width="100%" align="center"cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td width="20%">Kelompok</td>
            <td align="center" width="2%">:</td>
            <td width="28%">
                <b>{{ $pinkel->kelompok->nama_kelompok }} / {{ $pinkel->id }}</b>
            </td>
            <td width="20%">Tanggal Cair</td>
            <td align="center" width="2%">:</td>
            <td width="28%">
                <b>{{ Tanggal::tglLatin($pinkel->tgl_cair) }}</b>
            </td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->kelompok->alamat_kelompok }}</b>
            </td>
            <td>Alokasi Pinjaman</td>
            <td align="center">:</td>
            <td>
                <b>Rp. {{ number_format($pinkel->alokasi) }}</b>
            </td>
        </tr>
        <tr>
            <td>
                {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
            </td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->kelompok->d->nama_desa }}</b>
            </td>
            <td>Alokasi Pinjaman</td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->sis_pokok->nama_sistem }} ({{ $pinkel->jangka }} Bulan)</b>
            </td>
        </tr>
        <tr>
            <td>Ketua</td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->kelompok->ketua }}</b>
            </td>
            <td>Sistem Bagi Hasil</td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->pros_jasa / $pinkel->jangka }}%/Bulan, {{ $pinkel->jasa->nama_jj }}</b>
            </td>
        </tr>
    </table>

    <table border="1" width="100%" align="center"cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nama Anggota</th>
            <th rowspan="2">TTL</th>
            <th rowspan="2">Nama Penjamin</th>
            <th rowspan="2">Masa Pinjaman</th>
            <th colspan="2">Pinjaman</th>
            <th rowspan="2">Jumlah</th>
            <th rowspan="2">Premi ({{ $kec->besar_premi }}%)</th>
            <th rowspan="2">Keterangan</th>
            <th rowspan="2">TTD</th>
        </tr>
        <tr>
            <td>Pokok</td>
            <td>Jasa</td>
        </tr>
    </table>
@endsection
