@php
    use App\Utils\Tanggal;

    $waktu = '';
    $tempat = '';
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
                    {{ strtoupper($pinkel->kelompok->d->nama_desa) }}
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
                <div>
                    Menindaklanjuti hasil keputusan rapat pendanaan {{ $kec->nama_lembaga_sort }}
                    Tanggal {{ Tanggal::tglLatin($pinkel->tgl_dana) }} dengan ini memberitahukan bahwa akan dilakukan
                    pencairan kredit kepada ;
                </div>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
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
                            {{ ucwords(strtolower($pinkel->kelompok->alamat_kelompok)) }}
                            {{ ucwords(strtolower($pinkel->kelompok->d->sebutan_desa->sebutan_desa)) }}
                            {{ ucwords(strtolower($pinkel->kelompok->d->nama_desa)) }}
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
                        <td>Tempat</td>
                        <td>:</td>
                        <td>{{ $tempat }}</td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td colspan="4">
                            Data pemanfaat dan alokasi pencairannya adalah sebagai berikut :
                        </td>
                    </tr>
                </table>

                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;"
                    class="padding">
                    <tr style="background: rgb(233,233,233)">
                        <th class="t l b" width="10" height="20">No</th>
                        <th class="t l b" width="100">Nama Pemanfaat</th>
                        <th class="t l b">Alamat</th>
                        <th class="t l b r" width="80">Alokasi (Rp)</th>
                    </tr>

                    @foreach ($pinkel->pinjaman_anggota as $pa)
                        <tr>
                            <td class="t l b" align="center">{{ $loop->iteration }}</td>
                            <td class="t l b">{{ $pa->anggota->namadepan }}</td>
                            <td class="t l b">{{ $pa->anggota->alamat }}</td>
                            <td class="t l b r" align="right">{{ number_format($pa->alokasi) }}</td>
                        </tr>
                    @endforeach
                </table>

                <p>
                    Demikian surat pemberitahuan ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan
                    terimakasih.
                </p>
            </td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">
        <tr>
            <td width="33%" height="30">&nbsp;</td>
            <td width="33%">&nbsp;</td>
            <td width="33%">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td align="center">{{ $kec->nama_kec }}, {{ Tanggal::tglLatin($pinkel->tgl_cair) }}</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td align="center">
                {{ $kec->sebutan_level_1 }} {{ $kec->nama_lembaga_sort }}
            </td>
        </tr>
        <tr>
            <td colspan="3" height="40">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td align="center">
                <u>
                    <b>{{ $dir->namadepan }} {{ $dir->namabelakang }}</b>
                </u>
            </td>
        </tr>
    </table>
@endsection
