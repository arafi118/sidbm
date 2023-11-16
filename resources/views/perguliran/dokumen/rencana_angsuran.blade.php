@php
    use App\Utils\Tanggal;
    $jumlah_angsuran = 0;

    $alokasi = $pinkel->proposal;
    $alokasi_pinjaman = $alokasi;
    $tgl = $pinkel->tgl_proposal;
    $tanggal = 'Tanggal Proposal';
    if (Request::get('status') == 'A') {
        $alokasi = $pinkel->alokasi;

        $alokasi_pinjaman = $alokasi;
        if ($pinkel->saldo_pinjaman) {
            $alokasi = $pinkel->saldo_pinjaman->saldo_pinjaman;
        }
        $tgl = $pinkel->tgl_cair;
        $tanggal = 'Tanggal Cair';
    }

    $saldo_pokok = $alokasi;
    $saldo_jasa = ($saldo_pokok * $pinkel->pros_jasa) / 100;
@endphp

@extends('perguliran.dokumen.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr class="b">
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>RENCANA ANGSURAN PINJAMAN SPP</b>
                </div>
                <div style="font-size: 16px;">
                    <b>KELOMPOK {{ strtoupper($pinkel->kelompok->nama_kelompok) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
    </table>
    <table border="0" width="100%" align="center"cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td width="90">Loan ID.</td>
            <td width="5" align="center">:</td>
            <td>
                <b>{{ $pinkel->kelompok->nama_kelompok }} &mdash; {{ $pinkel->id }}</b>
            </td>
            <td width="90">Jangka waktu</td>
            <td width="5" align="center">:</td>
            <td>
                <b>{{ $pinkel->jangka }} Bulan</b>
            </td>
        </tr>
        <tr>
            <td>No. SPK</td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->spk_no }}</b>
            </td>
            <td>Sistem Angsuran</td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->sis_pokok->nama_sistem }} {{ $pinkel->jangka / $pinkel->sis_pokok->sistem }} Kali</b>
            </td>
        </tr>
        <tr>
            <td>{{ $tanggal }}</td>
            <td align="center">:</td>
            <td>
                <b>{{ Tanggal::tglLatin($tgl) }}</b>
            </td>
            <td>Jenis Jasa</td>
            <td align="center">:</td>
            <td>
                <b>{{ $pinkel->jasa->nama_jj }}</b>
            </td>
        </tr>
        <tr>
            <td>Alokasi Pinjaman</td>
            <td align="center">:</td>
            <td>
                <b>Rp. {{ number_format($alokasi_pinjaman) }}</b>
            </td>
            <td>Prosentase Jasa</td>
            <td align="center">:</td>
            <td>
                <b>{{ round($pinkel->pros_jasa / $pinkel->jangka, 2) }}% per bulan</b>
            </td>
        </tr>

        <tr>
            <td colspan="6">&nbsp;</td>
        </tr>
    </table>
    <table border="0" width="100%" align="center"cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: rgb(232, 232, 232)">
            <th class="l t b" height="20" width="10" align="center">Ke</th>
            <th class="l t b" width="50" align="center">Tanggal</th>
            <th class="l t b" width="60" align="center">Pokok</th>
            <th class="l t b" width="60" align="center">Jasa</th>
            <th class="l t b" align="center">Jumlah</th>
            <th class="l t b" width="60" align="center">Total Target</th>
            <th class="l t b" width="60" align="center">Saldo Pokok</th>
            <th class="l t b r" align="center">Saldo Jasa</th>
        </tr>
        @foreach ($rencana as $ra)
            @php
                $wajib_angsur = $ra->wajib_pokok + $ra->wajib_jasa;
                $jumlah_angsuran += $wajib_angsur;
                $saldo_pokok -= $ra->wajib_pokok;
                $saldo_jasa -= $ra->wajib_jasa;
            @endphp
            <tr>
                <td class="l" align="center">{{ $ra->angsuran_ke }}</td>
                <td class="l" align="center">{{ Tanggal::tglIndo($ra->jatuh_tempo) }}</td>
                <td class="l" align="right">{{ number_format($ra->wajib_pokok) }}</td>
                <td class="l" align="right">{{ number_format($ra->wajib_jasa) }}</td>
                <td class="l" align="right">{{ number_format($wajib_angsur) }}</td>
                <td class="l" align="right">{{ number_format($jumlah_angsuran) }}</td>
                <td class="l" align="right">{{ number_format($saldo_pokok) }}</td>
                <td class="l r" align="right">{{ number_format($saldo_jasa) }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td class="l t b" height="15" align="center" colspan="2">Jumlah</td>
            <td class="l t b" align="right">{{ number_format($alokasi) }}</td>
            <td class="l t b" align="right">{{ number_format(($alokasi * $pinkel->pros_jasa) / 100) }}</td>
            <td class="l t b" align="right">{{ number_format($jumlah_angsuran) }}</td>
            <td class="l t b" align="right">{{ number_format($jumlah_angsuran) }}</td>
            <td class="l t b" align="right">{{ number_format($saldo_pokok) }}</td>
            <td class="l t b r" align="right">{{ number_format($saldo_jasa) }}</td>
        </tr>

        <tr>
            <td align="center" colspan="8" height="20">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" colspan="5">&nbsp;</td>
            <td align="center" colspan="3">
                {{ $kec->nama_kec }}, {{ Tanggal::tglLatin($tgl) }}
            </td>
        </tr>
        <tr>
            <td align="center" colspan="5">
                {{ $kec->sebutan_level_1 }} {{ $kec->nama_lembaga_sort }}
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
