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
        {{ Tanggal::namaHari($pinj->tgl_tunggu) }} Tanggal {{ Tanggal::hari($pinj->tgl_tunggu) }} bulan
        {{ Tanggal::bulan($pinj->tgl_tunggu) }} tahun {{ Tanggal::tahun($pinj->tgl_tunggu) }}
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
@endsection
