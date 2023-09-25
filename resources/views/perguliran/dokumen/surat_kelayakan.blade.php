@php
    use App\Utils\Tanggal;
    $wt_cair = explode('_', $pinkel->wt_cair);
    if (count($wt_cair) == 1) {
        $waktu = $wt_cair[0];
    }
    
    if (count($wt_cair) == 2) {
        $waktu = $wt_cair[0];
        $tempat = $wt_cair[1];
    }
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
        <tr>
            <td width="50">Nomor</td>
            <td width="10" align="center">:</td>
            <td colspan="2">
                <b>______/DBM/{{ Tanggal::tglRomawi($pinkel->tgl_dana) }}</b>
            </td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td align="center">:</td>
            <td colspan="2">
                <b>{{ Tanggal::tglLatin($pinkel->tgl_dana) }}</b>
            </td>
        </tr>
        <tr>
            <td>Sifat</td>
            <td align="center">:</td>
            <td colspan="2">
                <b>Penting dan Rahasia</b>
            </td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td align="center">:</td>
            <td colspan="2">
                <b>Kelayakan Pinjaman</b>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td align="left" width="140">
                <div>KEPADA YTH.</div>
                <div style="font-weight: bold;">
                    {{ $pinkel->kelompok->d->sebutan_desa->sebutan_kades }} {{ $pinkel->kelompok->d->nama_desa }}
                </div>
                <div style="font-weight: bold;">
                    {{ $kec->sebutan_kec }} {{ $kec->nama_kec }}
                </div>
                <div style="font-weight: bold;">Di</div>
                <div style="font-weight: bold; text-align: center;">
                    {{ strtoupper($pinkel->kelompok->d->nama_desa) }} {{ strtoupper($kec->nama_kec) }}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="3">
                <div>Dengan hormat,</div>
                <div style="text-align: justify;">
                    Dengan ini memberitahukan bahwa keputusan rapat pendanaan Perguliran {{ $kec->nama_lembaga_sort }}
                    Tanggal {{ Tanggal::tglLatin($pinkel->tgl_dana) }}. yang merupakan tindak lanjut hasil verifikasi atas
                    Proposal Permohonan Kredit dari ;
                </div>
                <table>
                    <tr>
                        <td width="10">1.</td>
                        <td width="120">Nama Kelompok</td>
                        <td width="5">:</td>
                        <td>{{ $pinkel->kelompok->nama_kelompok }}</td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>
                            {{ $pinkel->kelompok->alamat_kelompok }}
                            {{ $pinkel->kelompok->d->sebutan_desa->sebutan_kades }} {{ $pinkel->kelompok->d->nama_desa }}
                        </td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Tanggal Proposal</td>
                        <td>:</td>
                        <td>{{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>Jumlah Permohonan</td>
                        <td>:</td>
                        <td>Rp {{ number_format($pinkel->proposal) }}</td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Jumlah Pemanfaat</td>
                        <td>:</td>
                        <td>{{ $pinkel->pinjaman_anggota_count }} orang</td>
                    </tr>
                </table>

                <div style="text-align: justify;">
                    Dinyatakan Layak/Tidak Layak didanai sebesar Rp. {{ number_format($pinkel->alokasi) }}
                    ({{ $keuangan->terbilang($pinkel->alokasi) }}) dan dengan
                    jadwal pencairan besok pada tanggal {{ Tanggal::tglLatin($pinkel->tgl_cair) }} bertempat di
                    {{ $tempat }}.
                </div>

                <div style="text-align: justify;">
                    Demikian surat pemberitahuan ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan
                    terimakasih.
                </div>
            </td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
        <tr>
            <td colspan="2" height="24">&nbsp;</td>
        </tr>
        <tr>
            <td width="50%">&nbsp;</td>
            <td width="50%" align="center">
                {{ $kec->nama_kec }}, {{ Tanggal::tglLatin($pinkel->tgl_dana) }}
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center">{{ $kec->sebutan_level_1 }} DBM</td>
        </tr>
        <tr>
            <td colspan="2" height="40">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center">{{ $dir->namadepan }} {{ $dir->namabelakang }}</td>
        </tr>
    </table>
@endsection
