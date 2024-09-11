@php
    use App\Utils\Tanggal;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>SURAT PERJANJIAN IURAN TANGGUNG RENTENG</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>

    <div style="text-align: justify;">
        Yang bertanda tangan di bawah ini,
        <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr style="background: rgb(233,233,233);">
                <th width="10">No</th>
                <th width="80">NIK</th>
                <th width="100">Nama Anggota</th>
                <th width="10">JK</th>
                <th>Alamat</th>
                <th width="70">Tanda Tangan</th>
            </tr>

            @foreach ($pinkel->pinjaman_anggota as $pa)
                <tr>
                    <td align="center">{{ $loop->iteration }}.</td>
                    <td align="center">{{ $pa->anggota->nik }}</td>
                    <td>{{ $pa->anggota->namadepan }}</td>
                    <td align="center">{{ $pa->anggota->jk }}</td>
                    <td>
                        {{ $pa->anggota->alamat }} {{ $pa->anggota->d->sebutan_desa->sebutan_desa }}
                        {{ $pa->anggota->d->nama_desa }}
                    </td>
                    <td>&nbsp;</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div style="text-align: justify; font-size: 11px;">
        Selaku anggota pemanfaat dari Nama Kelompok {{ $pinkel->kelompok->nama_kelompok }} yang beralamatkan di
        {{ $pinkel->kelompok->alamat_kelompok }} {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
        {{ $pinkel->kelompok->d->nama_desa }}.
    </div>
    <div style="text-align: justify; font-size: 11px;">
        Dengan ini menyatakan, apabila terjadi tunggakan angsuran pinjaman {{ $pinkel->jpp->nama_jpp }}
        {{ $kec->nama_lembaga_sort }} yang disebabkan adanya anggota pemanfaat yang belum mampu melunasi kewajibannya
        sesuai jadwal angsuran yang ditetapkan, maka masing-masing pemanfaat dalam kedudukan sebagai pribadi anggota
        kelompok, secara sadar dan penuh tanggung jawab menyatakan :

        <ol style="font-size: 11px;">
            @if (str_contains($kec->tanggung_renteng, 'li'))
                {!! json_decode($kec->tanggung_renteng, true) !!}
            @endif
            <li>
                Sanggup menanggung pelunasan sisa angsuran dengan sistem tanggung renteng yang pelaksanaannya dikoordinir
                oleh ketua kelompok demi kelancaran penyetoran angsuran dengan batas waktu yang telah disepakati dengan
                penuh tanggung jawab apabila seluruh tabungan anggota dan hasil penjualan jaminan belum mencukupi jumlah
                kewajiban pelunasan angsuran.
            </li>
            <li>
                Sanggup menerima sanksi dari {{ $kec->nama_lembaga_sort }} yang disepakati dalam forum Musyawarah Antar
                {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
                (MA{{ strtoupper(substr($pinkel->kelompok->d->sebutan_desa->sebutan_desa, 0, 1)) }}) dan/atau penyelesaian
                secara hukum
                yang berlaku, apabila kami ingkar terhadap pernyataan ini.
            </li>
        </ol>
    </div>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px; padding: 0px;">
        <tr>
            <td style="padding: 0px !important; text-align: justify;">
                <div style="margin: 0px; padding: 0px;">
                    Demikian surat pernyataan Kesanggupan Tanggung Renteng ini dibuat dengan penuh kesadaran dan tanpa
                    paksaan
                    dari
                    pihak manapun serta untuk dipergunakan dan/ atau dilaksanakan sebagaimana mestinya.
                </div>

                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px;">
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td align="center">{{ $kec->nama_kec }}, {{ Tanggal::tglLatin($pinkel->tgl_cair) }}</td>
                    </tr>
                    <tr>
                        <td align="center" width="50%">
                            Mengetahui,
                        </td>
                        <td align="center" width="50%">Ketua Kelompok</td>
                    </tr>
                    <tr>
                        <td align="center">
                            {{ $pinkel->kelompok->d->sebutan_desa->sebutan_kades }} {{ $pinkel->kelompok->d->nama_desa }}
                        </td>
                        <td align="center">{{ $pinkel->kelompok->nama_kelompok }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" height="30"></td>
                    </tr>
                    <tr>
                        <td align="center">
                            <b>{{ $pinkel->kelompok->d->kades }}</b>
                        </td>
                        <td align="center">
                            <b>{{ $pinkel->kelompok->ketua }}</b>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
