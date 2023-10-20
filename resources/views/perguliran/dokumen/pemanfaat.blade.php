@php
    use App\Utils\Tanggal;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR PENERIMA</b>
                </div>
                <div style="font-size: 16px;">
                    <b>PINJAMAN/PEMANFAAT {{ $pinkel->jpp->nama_jpp }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td width="70">Nama Kelompok</td>
            <td width="5" align="right">:</td>
            <td>{{ $pinkel->kelompok->nama_kelompok }} - {{ $pinkel->id }}</td>
            <td width="70">Alokasi Pinjaman</td>
            <td width="5" align="right">:</td>
            <td>Rp. {{ number_format($pinkel->proposal) }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td align="right">:</td>
            <td>{{ $pinkel->kelompok->alamat_kelompok }}</td>
            <td>Sistem Angsuran</td>
            <td align="right">:</td>
            <td>{{ $pinkel->sis_pokok->nama_sistem }}</td>
        </tr>
        <tr>
            <td>Tanggal Proposal</td>
            <td align="right">:</td>
            <td>{{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
            <td>Prosentase Jasa</td>
            <td align="right">:</td>
            <td>{{ $pinkel->pros_jasa }}% / {{ $pinkel->jangka }} bulan</td>
        </tr>
        <tr>
            <td>Nomor SPK</td>
            <td align="right">:</td>
            <td>{{ $pinkel->spk_no }}</td>
            <td>Pinjaman Ke-</td>
            <td align="right">:</td>
            <td>0</td>
        </tr>
    </table>
    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: rgb(232, 232, 232)">
            <th height="20" width="10">No</th>
            <th width="60">Nik</th>
            <th width="60">Nama Anggota</th>
            <th width="10">JK</th>
            <th width="50">Nomor HP</th>
            <th>Alamat</th>
            <th width="60">Penjamin</th>
            <th width="50">Pengajuan</th>
            <th width="20">Ttd</th>
        </tr>

        @php
            $proposal = 0;
        @endphp
        @foreach ($pinkel->pinjaman_anggota as $pa)
            <tr>
                <td height="15" align="center">{{ $loop->iteration }}</td>
                <td>{{ $pa->anggota->nik }}</td>
                <td>{{ $pa->anggota->namadepan }}</td>
                <td align="center">{{ $pa->anggota->jk }}</td>
                <td align="center">{{ $pa->anggota->hp }}</td>
                <td>{{ $pa->anggota->alamat }}</td>
                <td>{{ $pa->anggota->penjamin }}</td>
                <td align="right">{{ number_format($pa->proposal) }}</td>
                <td>&nbsp;</td>
            </tr>
            @php
                $proposal += $pa->proposal;
            @endphp
        @endforeach

        <tr style="font-weight: bold;">
            <td height="15" colspan="7" align="center">JUMLAH</td>
            <td align="right">{{ number_format($proposal) }}</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td width="60%">&nbsp;</td>
            <td width="60">Diterima Di</td>
            <td width="2">:</td>
            <td>{{ substr($pinkel->wt_cair, 6) }}</td>
        </tr>
        <tr>
            <td width="60%">&nbsp;</td>
            <td width="60">Pada Tanggal</td>
            <td width="2">:</td>
            <td>{{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="2" height="20">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" width="50%">Mengetahui,</td>
            <td align="center" width="50%">&nbsp;</td>
        </tr>
        <tr>
            <td align="center">{{ $kec->sebutan_level_1 }} {{ $kec->nama_lembaga_sort }}</td>
            <td align="center">Ketua Kelompok</td>
        </tr>
        <tr>
            <td align="center" colspan="2" height="30">&nbsp;</td>
        </tr>
        <tr style="font-weight: bold;">
            <td align="center">{{ $dir->namadepan }} {{ $dir->namabelakang }}</td>
            <td align="center">{{ $pinkel->kelompok->ketua }}</td>
        </tr>
    </table>
@endsection
