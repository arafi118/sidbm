@php
    use App\Utils\Tanggal;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR PENERIMA</b>
                </div>
                <div style="font-size: 16px; text-decoration: underline;">
                    <b>PIUTANG/PEMANFAAT {{ $pinkel->jpp->nama_jpp }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tr>
            <td width="70">Kelompok</td>
            <td width="5" align="right">:</td>
            <td>{{ $pinkel->kelompok->nama_kelompok }} - {{ $pinkel->id }}</td>
            <td width="70">Alokasi</td>
            <td width="5" align="right">:</td>
            <td>Rp. {{ number_format($pinkel->proposal) }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td align="right">:</td>
            <td>{{ $pinkel->kelompok->alamat_kelompok }}</td>
            <td>Sistem Angs.</td>
            <td align="right">:</td>
            <td>{{ $pinkel->sis_pokok->nama_sistem }}</td>
        </tr>
        <tr>
            <td>Tgl. Proposal</td>
            <td align="right">:</td>
            <td>{{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
            <td>Pros. Jasa</td>
            <td align="right">:</td>
            <td>{{ $pinkel->pros_jasa }}% / {{ $pinkel->jangka }} bulan</td>
        </tr>
        <tr>
            <td>Nomor SPK</td>
            <td align="right">:</td>
            <td>{{ $pinkel->spk_no }}</td>
            <td>Piutang Ke-</td>
            <td align="right">:</td>
            <td>0</td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px; table-layout: fixed;">
        <tr style="background: rgb(232, 232, 232)">
            <th class="t l b" height="20" width="5%">No</th>
            <th class="t l b" width="19%">Nik</th>
            <th class="t l b" width="15%">Nama Anggota</th>
            <th class="t l b" width="3%">JK</th>
            <th class="t l b" width="23%">Alamat</th>
            <th class="t l b" width="15%">Penjamin</th>
            <th class="t l b" width="15%">Pengajuan</th>
            <th class="t l b r" width="5%">Ttd</th>
        </tr>

        @php
            $proposal = 0;
        @endphp
        @foreach ($pinkel->pinjaman_anggota as $pa)
            <tr>
                <td class="t l b" height="15" align="center">{{ $loop->iteration }}</td>
                <td class="t l b">{{ $pa->anggota->nik }}</td>
                <td class="t l b">{{ $pa->anggota->namadepan }}</td>
                <td class="t l b" align="center">{{ $pa->anggota->jk }}</td>
                <td class="t l b">{{ $pa->anggota->alamat }}</td>
                <td class="t l b">{{ $pa->anggota->penjamin }}</td>
                <td class="t l b" align="right">{{ number_format($pa->proposal) }}</td>
                <td class="t l b r">&nbsp;</td>
            </tr>
            @php
                $proposal += $pa->proposal;
            @endphp
        @endforeach

        <tr>
            <td colspan="8" style="padding: 0px !important;">
                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="table-layout: fixed;">
                    <tr style="font-weight: bold;">
                        <td class="t l b" height="15" align="center" width="80%">JUMLAH</td>
                        <td class="t l b" align="right" width="15%">{{ number_format($proposal) }}</td>
                        <td class="t l b r" width="5%">&nbsp;</td>
                    </tr>
                </table>

                <table class="p0" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 14px;">
                    <tr>
                        <td colspan="4" height="10"></td>
                    </tr>
                    <tr>
                        <td width="50%">&nbsp;</td>
                        <td width="16%">Diterima Di</td>
                        <td width="2%">:</td>
                        <td width="32%">{{ substr($pinkel->wt_cair, 6) }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>Pada Tanggal</td>
                        <td>:</td>
                        <td>{{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
                    </tr>
                </table>

                <table class="p0" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 14px;">
                    <tr>
                        <td colspan="2" height="10">&nbsp;</td>
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
            </td>
        </tr>
    </table>
@endsection
