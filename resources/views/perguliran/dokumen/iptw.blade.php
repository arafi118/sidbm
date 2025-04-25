@php
    use App\Utils\Tanggal;
    $proposal = 0;
    $jasa = 0;
    $iptw = 0;

    $alokasi_pinjaman = $pinkel->alokasi;

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
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px; text-decoration: underline;">
                    <b>DAFTAR PENERIMA IPTW</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
    <table border="0" width="100%" align="center"cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td width="100">Kode Kelompok/Loan ID</td>
            <td align="center" width="5">:</td>
            <td>
                <b>{{ $pinkel->kelompok->kd_kelompok }} / {{ $pinkel->id }}</b>
            </td>
            <td width="100">Tanggal</td>
            <td align="center" width="5">:</td>
            <td>
                <b>{{ Tanggal::tglLatin($pinkel->tgl_proposal) }}</b>
            </td>
        </tr>
        <tr>
            <td>Kelompok</td>
            <td>:</td>
            <td>
                <b>{{ $pinkel->kelompok->nama_kelompok }}</b>
            </td>
            <td>Alokasi</td>
            <td>:</td>
            <td>
                <b>Rp. {{ number_format($alokasi_pinjaman) }}</b>
            </td>
        </tr>
        <tr>
            <td>{{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}</td>
            <td>:</td>
            <td>
                <b>{{ $pinkel->kelompok->d->nama_desa }}</b>
            </td>
            <td>Nomor SPK</td>
            <td>:</td>
            <td>
                <b>{{ $pinkel->spk_no }}</b>
            </td>
        </tr>
    </table>
    <table border="1" width="100%" align="center"cellspacing="0" cellpadding="0"
        style="font-size: 11px; margin-top: 12px;">
        <tr style="background: rgb(232,232,232)">
            <th height="20" width="10" align="center">No</th>
            <th align="center">Nama Anggota</th>
            <th width="80" align="center">
                @if (Request::get('status') == 'A' || Request::get('status') == 'W' || Request::get('status') == 'L')
                    Alokasi
                @else
                    Pengajuan
                @endif
            </th>
            <th width="80" align="center">Total Jasa</th>
            <th width="80" align="center">Jumlah IPTW</th>
            <th width="70" align="center">Tanda Tangan</th>
        </tr>

        @foreach ($pinkel->pinjaman_anggota as $pa)
            @php
                $_proposal = $pa->proposal;
                if (Request::get('status') == 'A' || Request::get('status') == 'W' || Request::get('status') == 'L') {
                    $_proposal = $pa->alokasi;
                }
                $_jasa = ($_proposal * $pinkel->pros_jasa) / 100;
                $_iptw = ($_jasa * $kec->iptw) / 100;

                $proposal += $_proposal;
                $jasa += $_jasa;
                $iptw += $_iptw;
            @endphp
            <tr>
                <td height="15" align="center">{{ $loop->iteration }}</td>
                <td>{{ $pa->anggota->namadepan }}</td>
                <td align="right">{{ number_format($_proposal) }}</td>
                <td align="right">{{ number_format($_jasa) }}</td>
                <td align="right">{{ number_format($_iptw) }}</td>
                <td align="right">&nbsp;</td>
            </tr>
        @endforeach

        <tr style="font-weight: bold;">
            <td align="center" colspan="2">Total</td>
            <td align="right">{{ number_format($proposal) }}</td>
            <td align="right">{{ number_format($jasa) }}</td>
            <td align="right">{{ number_format($iptw) }}</td>
            <td align="right">&nbsp;</td>
        </tr>
    </table>
    <table border="0" width="100%" align="center"cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td width="50%">&nbsp;</td>
            <td width="50%">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center">Mengetahui,</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center">Ketua Kelompok {{ $pinkel->kelompok->nama_kelompok }}</td>
        </tr>
        <tr>
            <td colspan="2" height="40"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center">
                <b>{{ $ketua }}</b>
            </td>
        </tr>
    </table>
@endsection
