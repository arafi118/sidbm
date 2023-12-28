@php
    use App\Utils\Tanggal;
    $data = ['Cover/Sampul', 'Daftar isi', 'Surat Pengajuan Pinjaman', 'Surat Rekomendasi Kredit', 'Profil Kelompok', 'Susunan Pengurus', 'Daftar Anggota Kelompok', 'Daftar Pemanfaat', 'Surat Pernyataan Tanggung Renteng', 'FC KTP Pemanfaat dan Penjamin', 'Surat Pernyataan Peminjam', 'Rencana Angsuran Kredit Ke Kelompok', 'BA Musyawarah Kelompok'];
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr class="b">
            <td colspan="3" align="center">
                <div style="font-size: 20px;">
                    <b>CHECK LIST</b>
                </div>
                <div style="font-size: 18px;">
                    KELENGKAPAN PROPOSAL {{ strtoupper($pinkel->jpp->nama_jpp) }}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>

    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td width="100">Kode Kelompok</td>
            <td width="5">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->kd_kelompok }}</td>

            <td width="80">&nbsp;</td>

            <td width="100">Tanggal</td>
            <td width="5">:</td>
            <td style="font-weight: bold;">{{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
        </tr>

        <tr>
            <td>Nama Kelompok</td>
            <td width="5">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->nama_kelompok }}</td>

            <td>&nbsp;</td>

            <td>Ketua</td>
            <td width="5">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->ketua }}</td>
        </tr>

        <tr>
            <td>Desa/Kelurahan</td>
            <td width="5">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->d->nama_desa }}</td>

            <td>&nbsp;</td>

            <td>Telpon</td>
            <td width="5">:</td>
            <td style="font-weight: bold;">{{ $pinkel->kelompok->telpon }}</td>
        </tr>
    </table>

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px; margin-top: 12px;">
        <tr style="background: rgb(232,232,232);">
            <th rowspan="2" width="10">No</th>
            <th rowspan="2">Nama Dokumen</th>
            <th colspan="3">Status</th>
            <th rowspan="2" width="150">Catatan</th>
        </tr>
        <tr style="background: rgb(232,232,232);">
            <th width="30">C</th>
            <th width="30">K</th>
            <th width="30">TA</th>
        </tr>

        @foreach ($data as $dt => $v)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td>{{ $v }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6">
                <b>Catatan :</b>
                <br>
                <br>
                <br>
                <br>
                <br>
            </td>
        </tr>
    </table>
    <div style="font-size: 8px; margin-bottom: 16px;">Keterangan: C = Cukup | K = Kurang | TA = Tidak Ada</div>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px; margin-top: 12px;">
        <tr>
            <td width="60%"></td>
            <td width="40%" align="center">Diperika tanggal, ___________________</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center">Diperiksa Oleh</td>
        </tr>
        <tr>
            <td colspan="2" height="40">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center"><b>_____________________</b></td>
        </tr>
    </table>
@endsection
