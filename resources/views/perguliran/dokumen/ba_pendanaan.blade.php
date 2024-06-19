@php
    use App\Utils\Tanggal;

    $jumlah = 0;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>BERITA ACARA</b>
                </div>
                <div style="font-size: 18px;">
                    <b>RAPAT PENETAPAN PENDANAAN</b>
                </div>
            </td>
        </tr>
    </table>

    <p style="text-align: justify;">
        Dalam rangka menindak lanjuti proses tahapan perguliran atas kelompok kelompok permohonan pinjaman
        {{ $kec->nama_lembaga_sort }} yang sudah diterbitkan rekomendasi pada tahapan verifikasi maka pada hari ini
        {{ Tanggal::namaHari($pinj->tgl_tunggu) }} tanggal {{ Tanggal::hari($pinj->tgl_tunggu) }} bulan
        {{ Tanggal::namaBulan($pinj->tgl_tunggu) }} tahun {{ Tanggal::tahun($pinj->tgl_tunggu) }} bertempat di kantor
        {{ $kec->nama_lembaga_sort }} telah dilakukan pembahasan dan ditetapkan alokasi pendanaan dan rencana tanggal
        pencairan kepada kelompok sebagai berikut:
    </p>

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px; table-layout: fixed;">
        <thead>
            <tr style="background: rgb(232,232,232)">
                <th width="4%">No</th>
                <th width="21%">Nama Kelompok</th>
                <th width="">Alamat</th>
                <th width="5%">Jenis</th>
                <th width="8%">Anggota</th>
                <th width="17%">Ketua Kelompok</th>
                <th width="13%">Alokasi Pendanaan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($pinjaman as $pinkel)
                @php
                    $jumlah += $pinkel->alokasi;
                @endphp
                <tr>
                    <td align="center">
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $pinkel->kelompok->nama_kelompok }}
                    </td>
                    <td>
                        {{ $pinkel->kelompok->alamat_kelompok }} {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
                        {{ $pinkel->kelompok->d->nama_desa }}
                    </td>
                    <td align="center">
                        {{ $pinkel->jpp->nama_jpp }}
                    </td>
                    <td align="center">
                        {{ $pinkel->pinjaman_anggota_count }}
                    </td>
                    <td>
                        {{ $pinkel->kelompok->ketua }}
                    </td>
                    <td align="right">
                        {{ number_format($pinkel->alokasi) }}
                    </td>
                </tr>
            @endforeach

            <tr style="font-weight: bold;">
                <td colspan="6" align="center">
                    JUMLAH
                </td>
                <td align="right">
                    {{ number_format($jumlah) }}
                </td>
            </tr>
        </tbody>
    </table>

    <p style="text-align: justify;">
        Demikian Berita Acara ini dibuat dan ditanda tangani untuk menjadi dasar pencairan pinjaman kepada kelompok kelompok
        tersebut diatas.
    </p>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px; table-layout: fixed;">
        <tr>
            <td width="25%">&nbsp;</td>
            <td width="25%">&nbsp;</td>
            <td width="25%">Ditanda tangani di</td>
            <td width="25%">: {{ $kec->sebutan_kec }} {{ $kec->nama_kec }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Pada tanggal</td>
            <td>: {{ Tanggal::tglLatin($pinj->tgl_tunggu) }}</td>
        </tr>

        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
            <td height="20">
                <div>{{ $pendanaan->namadepan }} {{ $pendanaan->namabelakang }}</div>
                <div>
                    <b>{{ $pendanaan->j->nama_jabatan }}</b>
                </div>
            </td>
            <td align="right" style="vertical-align: bottom;">___________________________</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td height="20">
                <div>{{ $direktur->namadepan }} {{ $direktur->namabelakang }}</div>
                <div>
                    <b>{{ $kec->sebutan_level_1 }}</b>
                </div>
            </td>
            <td align="right" style="vertical-align: bottom;">___________________________</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td height="20">
                <div>{{ $sekretaris->namadepan }} {{ $sekretaris->namabelakang }}</div>
                <div>
                    <b>{{ $kec->sebutan_level_2 }}</b>
                </div>
            </td>
            <td align="right" style="vertical-align: bottom;">___________________________</td>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>
@endsection
