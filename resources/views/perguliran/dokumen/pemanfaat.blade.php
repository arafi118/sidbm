@php
    use App\Utils\Tanggal;

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
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR CALON PEMANFAAT</b>
                </div>
                <div style="font-size: 16px; text-decoration: underline;">
                    <b>PIUTANG {{ $pinkel->jpp->nama_jpp }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
        <tr>
            <td width="70">Kelompok</td>
            <td width="5" align="right">:</td>
            <td>{{ $pinkel->kelompok->nama_kelompok }} - {{ $pinkel->id }}</td>
            <td width="70">Pengajuan</td>
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
            <td>Piutang Ke-</td>
            <td align="right">:</td>
            <td>{{ $pinjaman_ke + 1 }}</td>
            <td colspan="3"></td>
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
                    style="font-size: 12px;">
                    <tr>
                        <td colspan="3" height="10">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" width="33%">Ketua</td>
                        <td align="center" width="33%">Sekretaris</td>
                        <td align="center" width="33%">Bendahara</td>
                    </tr>
                    <tr>
                        <td align="center" colspan="3" height="30">&nbsp;</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td align="center">{{ $ketua }}</td>
                        <td align="center">{{ $sekretaris }}</td>
                        <td align="center">{{ $bendahara }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
