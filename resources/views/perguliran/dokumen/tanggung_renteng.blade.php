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
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px; text-decoration: underline;">
                    <b>SURAT PERNYATAAN TANGGUNG RENTENG</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>


    <div>Yang bertanda tangan di bawah ini,</div>
    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tr style="background: rgb(232,232,232)">
            <th width="10">No</th>
            <th width="60">Nik</th>
            <th width="130">Nama Anggota</th>
            <th width="10">JK</th>
            <th width="70">Tanda Tangan</th>
        </tr>
        @php
            $nomor = 1;
        @endphp
        @foreach ($pinkel->pinjaman_anggota as $pa)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td align="center">{{ $pa->anggota->nik }}</td>
                <td>{{ $pa->anggota->namadepan }}</td>
                <td align="center">{{ $pa->anggota->jk }}</td>
                <td>{{ $nomor }}.</td>
            </tr>

            @php
                $nomor++;
            @endphp
        @endforeach
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
        <tr>
            <td colspan="2">
                <div style="text-align: justify;">
                    Selaku anggota pemanfaat dari Nama Kelompok {{ $pinkel->kelompok->nama_kelompok }} yang beralamatkan di
                    {{ $pinkel->kelompok->alamat_kelompok }} {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
                    {{ $pinkel->kelompok->d->nama_desa }}
                </div>
                <div style="text-align: justify;">
                    Dengan ini menyatakan, apabila terjadi tunggakan angsuran piutang {{ $pinkel->jpp->nama_jpp }}
                    {{ $kec->nama_lembaga_sort }} yang disebabkan adanya anggota pemanfaat yang belum mampu melunasi
                    kewajibannya sesuai jadwal angsuran yang ditetapkan, maka masing-masing pemanfaat dalam kedudukan
                    sebagai pribadi anggota kelompok, secara sadar dan penuh tanggung jawab menyatakan :

                    <ol style="margin-bottom: 0; padding-bottom: 0;">
                        @if (str_contains($kec->tanggung_renteng, 'li'))
                            {!! json_decode($kec->tanggung_renteng, true) !!}
                        @endif
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
                    style="font-size: 14px;">
                    <tr>
                        <td colspan="2" style="padding-top: 0px;">
                            <div style="text-align: justify;">
                                Demikian surat pernyataan Kesanggupan Tanggung Renteng ini dibuat dengan penuh kesadaran dan
                                tanpa paksaan dari pihak manapun serta untuk dipergunakan dan/atau dilaksanakan sebagaimana
                                mestinya.
                            </div>
                        </td>
                    </tr>
                </table>

                @if ($tanda_tangan)
                    {!! $tanda_tangan !!}
                @else
                    <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                        style="font-size: 14px;">
                        <tr>
                            <td width="50%">&nbsp;</td>
                            <td width="50%" align="center">{{ $kec->nama_kec }},
                                {{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
                        </tr>
                        <tr>
                            <td align="center" width="50%">
                                {!! Session::get('lokasi') == '188' ? '&nbsp;' : 'Mengetahui,' !!}
                            </td>
                            <td align="center" width="50%">Kelompok</td>
                        </tr>
                        <tr>
                            <td align="center">
                                @if (Session::get('lokasi') != '188')
                                    {{ $pinkel->kelompok->d->sebutan_desa->sebutan_kades }}
                                    {{ $pinkel->kelompok->d->nama_desa }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td align="center">{{ $pinkel->kelompok->nama_kelompok }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" height="30"></td>
                        </tr>
                        <tr>
                            <td align="center">
                                @if (Session::get('lokasi') != '188')
                                    <b>{{ $pinkel->kelompok->d->kades }}</b>
                                    @if ($pinkel->kelompok->d->nip)
                                        <div><small>NIP. {{ $pinkel->kelompok->d->nip }}</small></div>
                                    @endif
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td align="center">
                                <b>{{ $ketua }}</b>
                                <div>Ketua</div>
                            </td>
                        </tr>
                    </table>
                @endif
            </td>
        </tr>
    </table>
@endsection
