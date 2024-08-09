@php
    use App\Utils\Tanggal;

    if (Request::get('status') == 'P') {
        $alokasi = $pinkel->proposal;
        $tanggal = 'Tanggal Proposal';
        $tgl = $pinkel->tgl_proposal;
    }

    if (Request::get('status') == 'V') {
        $alokasi = $pinkel->verifikasi;
        $tanggal = 'Tanggal Verifikasi';
        $tgl = $pinkel->tgl_verifikasi;
    }

    if (Request::get('status') == 'W') {
        $alokasi = $pinkel->alokasi;
        $tanggal = 'Tanggal Cair';
        $tgl = $pinkel->tgl_cair;
    }

    if (Request::get('status') == 'A') {
        $alokasi = $pinkel->alokasi;
        $tanggal = 'Tanggal Cair';
        $tgl = $pinkel->tgl_cair;
    }
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr class="b">
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR PESERTA ASURANSI {{ strtoupper($kec->nama_asuransi_p) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>

    <table border="0" width="100%" align="center"cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td width="20%">Kelompok</td>
            <td align="center" width="2%">:</td>
            <td width="28%">
                <b>{{ $pinkel->kelompok->nama_kelompok }} / {{ $pinkel->id }}</b>
            </td>
            <td width="20%">Tanggal Cair</td>
            <td align="center" width="2%">:</td>
            <td width="28%">
                <b>{{ Tanggal::tglLatin($pinkel->tgl_cair) }}</b>
            </td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->kelompok->alamat_kelompok }}</b>
            </td>
            <td>Alokasi Pinjaman</td>
            <td align="center">:</td>
            <td>
                <b>Rp. {{ number_format($pinkel->alokasi) }}</b>
            </td>
        </tr>
        <tr>
            <td>
                {{ $pinkel->kelompok->d->sebutan_desa->sebutan_desa }}
            </td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->kelompok->d->nama_desa }}</b>
            </td>
            <td>Alokasi Pinjaman</td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->sis_pokok->nama_sistem }} ({{ $pinkel->jangka }} Bulan)</b>
            </td>
        </tr>
        <tr>
            <td>Ketua</td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->kelompok->ketua }}</b>
            </td>
            <td>Sistem Bagi Hasil</td>
            <td align="center">:</td>
            <td>
                <b>{{ number_format($pinkel->pros_jasa / $pinkel->jangka, 2) }}%/Bulan, {{ $pinkel->jasa->nama_jj }}</b>
            </td>
        </tr>
    </table>

    <table border="1" width="100%" align="center"cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nama Anggota</th>
            <th rowspan="2">TTL</th>
            <th colspan="2">Pinjaman</th>
            <th rowspan="2">Jumlah</th>
            <th rowspan="2">Premi ({{ $kec->besar_premi }}%)</th>
            <th rowspan="2">Keterangan</th>
            <th rowspan="2">TTD</th>
        </tr>
        <tr>
            <td>Pokok</td>
            <td>Jasa</td>
        </tr>

        @php
            $t_jasa = 0;
            $t_pokok = 0;
            $t_asuransi = 0;
        @endphp
        @foreach ($pinkel->pinjaman_anggota as $pa)
            @php
                $pokok = $pa->alokasi;
                $jasa = $pa->alokasi * ($pa->pros_jasa / 100);

                $asuransi = $pokok * ($kec->besar_premi / 100);
                if ($kec->pengaturan_asuransi == 2) {
                    $asuransi = ($pokok + $jasa) * ($kec->besar_premi / 100);
                }

                $tgl_lahir = new DateTime($pa->anggota->tgl_lahir);
                $tgl_cair = new DateTime($pa->tgl_cair);

                $jarak = $tgl_cair->diff($tgl_lahir);
                if ($jarak->y > $kec->usia_mak) {
                    continue;
                }

                $t_jasa += $jasa;
                $t_pokok += $pokok;
                $t_asuransi += $asuransi;
            @endphp
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td>{{ $pa->anggota->namadepan }}</td>
                <td>
                    {{ $pa->anggota->tempat_lahir }}, {{ Tanggal::tglLatin($pa->anggota->tgl_lahir) }}
                </td>
                <td align="right">{{ number_format($pokok) }}</td>
                <td align="right">{{ number_format($jasa) }}</td>
                <td align="right">{{ number_format($pokok + $jasa) }}</td>
                <td align="right">{{ number_format($asuransi) }}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3" align="center">Total</th>
            <th align="right">{{ number_format($t_pokok) }}</th>
            <th align="right">{{ number_format($t_jasa) }}</th>
            <th align="right">{{ number_format($t_pokok + $t_jasa) }}</th>
            <th align="right">{{ number_format($t_asuransi) }}</th>
            <th></th>
            <th></th>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" colspan="5">&nbsp;</td>
            <td align="center" colspan="3">
                {{ $kec->nama_kec }}, {{ Tanggal::tglLatin($tgl) }}
            </td>
        </tr>
        <tr>
            <td align="center" colspan="5">
                {{ $kec->sebutan_level_1 }}
            </td>
            <td align="center" colspan="3">
                Ketua Kelompok {{ $pinkel->kelompok->nama_kelompok }}
            </td>
        </tr>
        <tr>
            <td align="center" colspan="8" height="40">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" colspan="5">
                <b>{{ $dir->namadepan }} {{ $dir->namabelakang }}</b>
            </td>
            <td align="center" colspan="3">
                <b>{{ $pinkel->kelompok->ketua }}</b>
            </td>
        </tr>
    </table>
@endsection
