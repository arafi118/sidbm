@php
    use App\Utils\Tanggal;

    $keterangan = '';
    $denda = 0;
    $idt = 0;

    $angsur_bulan_depan = true;

    $target_pokok = 0;
    $target_jasa = 0;
    $angsuran_ke = 1;
    $wajib_pokok = 0;
    $wajib_jasa = 0;
    if ($ra_bulan_ini) {
        $wajib_pokok = $ra_bulan_ini->wajib_pokok;
        $wajib_jasa = $ra_bulan_ini->wajib_jasa;
        $target_pokok = $ra_bulan_ini->target_pokok;
        $target_jasa = $ra_bulan_ini->target_jasa;
        $angsuran_ke = $ra_bulan_ini->angsuran_ke;
    }

    $angsuran_ke = 1;
    $jum_angsuran = $pinkel->jangka / $pinkel->sis_pokok->sistem;
    if ($ra_bulan_ini->angsuran_ke > 0) {
        $angsuran_ke = $ra_bulan_ini->angsuran_ke;
    }

    $nama_user = '';
    $no_kuitansi = '';
@endphp
@foreach ($real->trx as $trx)
    @php
        $keterangan .= $trx->keterangan_transaksi . '<br>';
        if (
            $trx->rekening_kredit == '4.1.01.04' ||
            $trx->rekening_kredit == '4.1.01.05' ||
            $trx->rekening_kredit == '4.1.01.06'
        ) {
            $denda += $trx->jumlah;
        }

        $no_kuitansi .= $trx->idt . '/';

        $nama_user = $trx->user->namadepan . ' ' . $trx->user->namabelakang;
    @endphp
@endforeach

@if ($kertas == '80')
    <style type="text/css">
        @media print {
            @page {
                size: 80mm 90mm;
            }

            body {
                padding: 4px;
            }
        }

        .style1 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
        }

        .style2 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 6px;
        }
    </style>
@else
    <style type="text/css">
        @media print {
            @page {
                size: 58mm 68mm;
            }

            body {
                padding: 4px;
            }
        }

        .style1 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 6px;
        }

        .style2 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 4px;
        }
    </style>
@endif

<style type="text/css">
    .top {
        border-top: thin ridge #000000;
    }

    .bottom {
        border-bottom: thin ridge #000000;
    }

    .left {
        border-left: thin ridge #000000;
    }

    .right {
        border-right: thin ridge #000000;
    }

    .allborder {
        border: thin ridge #000000;
    }

    .center {
        text-align: center;
    }
</style>

<body onload="window.print()">
    <table width="100%" action="" border="0" align="center" cellpadding="1" cellspacing="0" class="style1">

        <tr>
            <td colspan="5" class="bottom" align="center">
                <b>{{ strtoupper($kec->nama_lembaga_sort . ' ' . $kec->nama_kec) }}</b>
                <br>
                <b>{{ $kec->alamat_kec }}</b>
                <br>
                {{ $kec->nomor_bh }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="bottom" align="center">
                <b>K U I T A N S I</b>
            </td>
        </tr>

        <tr>
            <td width="24%">No</td>
            <td width="2%" align="center">:</td>
            <td width="24%">{{ substr($no_kuitansi, 0, -1) }}</td>
            <td colspan="2" width="50%">
                <div align="right">
                    Angsuran ke: {{ $angsuran_ke }}
                    dari {{ $jum_angsuran }}
                </div>
            </td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td align="center">:</td>
            <td>{{ Tanggal::tglLatin($real->tgl_transaksi) }}</td>
            <td colspan="2" width="50%">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td>Loan ID</td>
            <td align="center">:</td>
            <td colspan="3">{{ $pinkel->id }} - {{ $pinkel->jpp->nama_jpp }}</td>
        </tr>
        <tr>
            <td>Nama Kelompok</td>
            <td align="center">:</td>
            <td colspan="3">{{ $pinkel->kelompok->nama_kelompok }} - {{ $pinkel->kelompok->ketua }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td align="center">:</td>
            <td colspan="3">{{ $pinkel->kelompok->d->nama_desa }}</td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td>Pokok</td>
            <td align="center">:</td>
            <td>&nbsp;</td>
            <td colspan="2" width="10%">
                <div align="right">{{ number_format($real->realisasi_pokok) }}</div>
            </td>
        </tr>
        <tr>
            <td>Jasa</td>
            <td align="center">:</td>
            <td>&nbsp;</td>
            <td colspan="2">
                <div align="right">{{ number_format($real->realisasi_jasa) }}</div>
            </td>
        </tr>
        <tr>
            <td class="bottom">Denda</td>
            <td class="bottom" align="center">:</td>
            <td class="bottom">&nbsp;</td>
            <td colspan="2" class="bottom">
                <div align="right">{{ number_format($denda) }}</div>
            </td>
        </tr>
        <tr>
            <th height="18">JUMLAH </th>
            <td align="center">:</td>
            <td>&nbsp;</td>
            <td colspan="2">
                <div align="right">
                    <b>
                        {{ number_format($real->realisasi_pokok + $real->realisasi_jasa + $denda) }}
                    </b>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="5">Terbilang : </td>
        </tr>
        <tr>
            <th colspan="5" class="style2">
                {{ strtoupper($keuangan->terbilang($real->realisasi_pokok + $real->realisasi_jasa + $denda)) }} RUPIAH
            </th>
        </tr>

        <tr>
            <td colspan="5" height="10">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="3" align="center">
                Dibayar Oleh
            </td>
            <td colspan="2" align="center">
                Diterima Oleh
            </td>
        </tr>

        <tr>
            <td colspan="5" height="30">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="3" align="center">
                <b> ............................ </b>
            </td>
            <td colspan="2" align="center">
                <b>( {{ $nama_user }} )</b>
            </td>
        </tr>
    </table>

    <title>Struk Angsuran Kelompok {{ $pinkel->kelompok->nama_kelompok }} &mdash; {{ $pinkel->id }}</title>
</body>
