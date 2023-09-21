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
            <td width="60">Nama Kelompok</td>
            <td width="5" align="right">:</td>
            <td width="150">{{ $pinkel->kelompok->nama_kelompok }} - {{ $pinkel->id }}</td>
            <td width="60">Alokasi Pinjaman</td>
            <td width="5" align="right">:</td>
            <td width="150">Rp. {{ number_format($pinkel->alokasi) }}</td>
        </tr>
        <tr>
            <td width="60">Alamat</td>
            <td width="5" align="right">:</td>
            <td width="150">{{ $pinkel->kelompok->alamat_kelompok }}</td>
            <td width="60">Sistem Angsuran</td>
            <td width="5" align="right">:</td>
            <td width="150">{{ $pinkel->jangka }} bulan, {{ $pinkel->jasa->nama_jj }}</td>
        </tr>
        <tr>
            <td width="60">Tanggal Proposal</td>
            <td width="5" align="right">:</td>
            <td width="150">{{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
            <td width="60">Prosentase Jasa</td>
            <td width="5" align="right">:</td>
            <td width="150">{{ $pinkel->pros_jasa / $pinkel->jangka }}% / bulan</td>
        </tr>
        <tr>
            <td width="60">Nomor SPK</td>
            <td width="5" align="right">:</td>
            <td width="150">{{ $pinkel->spk_no }}</td>
            <td width="60">Pinjaman Ke-</td>
            <td width="5" align="right">:</td>
            <td width="150">0</td>
        </tr>
    </table>
    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: rgb(232, 232, 232)">
            <th height="20" width="10">No</th>
            <th width="60">Nik</th>
            <th width="80">Nama Anggota</th>
            <th width="10">JK</th>
            <th width="50">Nomor HP</th>
            <th>Alamat</th>
            <th width="80">Penjamin</th>
            <th width="60">Pengajuan</th>
            <th width="10">Ttd</th>
        </tr>

        @php
            $proposal = 0;
        @endphp
        @foreach ($pinkel->pinjaman_anggota as $pa)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
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
            <td align="center">{{ $kec->sebutan_level_1 }}</td>
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
