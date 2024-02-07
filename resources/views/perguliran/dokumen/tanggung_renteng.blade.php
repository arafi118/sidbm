@php
    use App\Utils\Tanggal;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr class="b">
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>SURAT PERNYATAAN TANGGUNG RENTENG</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>


    <div>Yang bertanda tangan di bawah ini,</div>
    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: rgb(232,232,232)">
            <th width="10">No</th>
            <th width="60">Nik</th>
            <th width="130">Nama Anggota</th>
            <th width="10">JK</th>
            <th>Alamat</th>
            <th width="50">Tanda Tangan</th>
        </tr>
        @foreach ($pinkel->pinjaman_anggota as $pa)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td align="center">{{ $pa->anggota->nik }}</td>
                <td>{{ $pa->anggota->namadepan }}</td>
                <td align="center">{{ $pa->anggota->jk }}</td>
                <td>{{ $pa->anggota->alamat }}</td>
                <td>&nbsp;</td>
            </tr>
        @endforeach
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="2">
                <div style="text-align: justify;">
                    Selaku anggota pemanfaat dari Nama Kelompok {{ $pinkel->kelompok->nama_kelompok }} yang beralamatkan di
                    {{ $pinkel->kelompok->alamat_kelompok }} {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
                    {{ $pinkel->kelompok->d->nama_desa }}
                </div>
                <div style="text-align: justify;">
                    Dengan ini menyatakan, apabila terjadi tunggakan angsuran pinjaman {{ $pinkel->jpp->nama_jpp }}
                    {{ $kec->nama_lembaga_sort }} yang disebabkan adanya anggota pemanfaat yang belum mampu melunasi
                    kewajibannya sesuai jadwal angsuran yang ditetapkan, maka masing-masing pemanfaat dalam kedudukan
                    sebagai pribadi anggota kelompok, secara sadar dan penuh tanggung jawab menyatakan :

                    <ol style="margin-bottom: 0; padding-bottom: 0;">
                        <li>
                            Memberikan kuasa kepada ketua kelompok untuk menarik tabungan anggota yang dikelola
                            kelompok guna melunasi tunggakan angsuran, apabila terjadi tunggakan angsuran dari satu,
                            beberapa atau seluruh anggota kelompok;
                        </li>
                        <li>
                            Memberikan kuasa kepada ketua kelompok untuk mengambil dan atau menjual jaminan anggota
                            yang tidak memenuhi kewajibannya tersebut dan akan memperhitungkan hasilnya untuk melunasi sisa
                            pokok dan jasa kredit. Kelebihan dari jumlah tersebut akan dikembalikan kepada masing-masing
                            yang bersangkutan;
                        </li>
                        <li>
                            Sanggup menanggung pelunasan sisa angsuran dengan sistem tanggung renteng yang
                            pelaksanaannya dikoordinir oleh ketua kelompok demi kelancaran penyetoran angsuran dengan batas
                            waktu yang telah disepakati dengan penuh tanggung jawab apabila seluruh tabungan anggota dan
                            hasil penjualan jaminan belum mencukupi jumlah kewajiban pelunasan angsuran.
                        </li>
                        <li>
                            Sanggup menerima sanksi dari {{ $kec->nama_lembaga_sort }} yang disepakati dalam forum
                            Musyawarah Antar Desa (MAD) dan/atau penyelesaian secara hukum yang berlaku, apabila kami ingkar
                            terhadap pernyataan ini.
                        </li>
                    </ol>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 0px !important;">
                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px;">
                    <tr>
                        <td colspan="2" style="padding-top: 0px;">
                            <div style="text-align: justify;">
                                Demikian surat pernyataan Kesanggupan Tanggung Renteng ini dibuat dengan penuh kesadaran dan
                                tanpa paksaan dari pihak manapun serta untuk dipergunakan dan/atau dilaksanakan sebagaimana
                                mestinya.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">&nbsp;</td>
                        <td width="50%" align="center">{{ $kec->nama_kec }},
                            {{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
                    </tr>
                    <tr>
                        <td align="center">Mengetahui,</td>
                        <td align="center">Dikuatkan Oleh,</td>
                    </tr>
                    <tr>
                        <td align="center">
                            {{ $pinkel->kelompok->d->sebutan_desa->sebutan_kades }} {{ $pinkel->kelompok->d->nama_desa }}
                        </td>
                        <td align="center">Ketua Kelompok {{ $pinkel->kelompok->nama_kelompok }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" height="50">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center">
                            <b>{{ $pinkel->kelompok->d->kades }}</b>
                            @if ($pinkel->kelompok->d->nip)
                                <div><small>NIP. {{ $pinkel->kelompok->d->nip }}</small></div>
                            @endif
                        </td>
                        <td align="center">
                            <b>{{ $pinkel->kelompok->ketua }}</b>
                            @if ($pinkel->kelompok->d->nip)
                                <div>&nbsp;</div>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
