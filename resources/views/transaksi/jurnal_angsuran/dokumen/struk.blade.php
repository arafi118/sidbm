@php
    use App\Utils\Tanggal;
    
    $keterangan = '';
    $denda = 0;
    $idt = 0;
    
    $tunggakan_pokok = $ra->target_pokok - $real->sum_pokok;
    if ($tunggakan_pokok < 0) {
        $tunggakan_pokok = 0;
    }
    $tunggakan_jasa = $ra->target_jasa - $real->sum_jasa;
    if ($tunggakan_jasa < 0) {
        $tunggakan_jasa = 0;
    }
    
    $jum_angsuran = $pinkel->jangka / $pinkel->sis_pokok->sistem;
    
    $pokok_bulan_depan = $ra->target_pokok;
    $jasa_bulan_depan = $ra->target_jasa;
    if ($ra->angsuran_ke >= $jum_angsuran) {
        $pokok_bulan_depan = $pinkel->alokasi - $real->sum_pokok;
        $jasa_bulan_depan = ($pinkel->alokasi * $pinkel->pros_jasa) / 100 - $real->sum_jasa;
    }
@endphp
@foreach ($real->trx as $trx)
    @php
        $keterangan .= $trx->keterangan_transaksi . '<br>';
        if ($trx->rekening_kredit == '4.1.01.04' || $trx->rekening_kredit == '4.1.01.05' || $trx->rekening_kredit == '4.1.01.06') {
            $denda += $trx->jumlah;
        }
        
        $idt = $trx->idt;
    @endphp
@endforeach

<style type="text/css">
    .style1 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
    }

    .style2 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 8px;
    }

    .top {
        border-top: 1px solid #000000;
    }

    .bottom {
        border-bottom: 1px solid #000000;
    }

    .left {
        border-left: 1px solid #000000;
    }

    .right {
        border-right: 1px solid #000000;
    }

    .allborder {
        border: 1px solid #000000;
    }

    .style26 {
        font-family: Verdana, Arial, Helvetica, sans-serif
    }

    .style27 {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 11px;
        font-weight: bold;
    }

    .center {
        text-align: center;
    }
</style>

<body onLoad="window.print()">
    <table width="100%" action="" border="0" align="center" cellpadding="1" cellspacing="1.5" class="style1">

        <tr>
            <th colspan="4" class="bottom">
                <b>{{ strtoupper($kec->nama_lembaga_sort . '' . $kec->nama_kec) }}</b>
                <br>
                {{ $kec->alamat_kec }}
                <br>
                Telp. {{ $kec->telpon_kec }}
            </th>
            <td width="4%" rowspan="17">&nbsp;</td>
            <td width="19%">
                Kode Transaksi
                <br>
                Tanggal Transasksi
            </td>
            <td width="15%">
                : {{ $idt }}-{{ $pinkel->id }}/{{ $pinkel->jpp->nama_jpp }}
                <br>
                : {{ Tanggal::tglLatin($real->tgl_transaksi) }}
            </td>
            <th width="14%" class="style26">BUKTI ANGSURAN</th>
        </tr>

        <tr>
            <td width="15%">Loan ID</td>
            <td width="11%"><strong>: {{ $pinkel->id }}</strong></td>
            <td colspan="2">
                <div align="right">Angsuran ke: {{ $ra->angsuran_ke }} dari {{ $jum_angsuran }}</div>
            </td>
            <th class="bottom top">STATUS PINJAMAN</th>
            <th class="bottom top">POKOK</th>
            <th class="bottom top">JASA</th>
        </tr>
        <tr>
            <td>ID Kelompok </td>
            <td colspan="3">: {{ $pinkel->kelompok->kd_kelompok }}</td>
            <td>Alokasi Pinjaman </td>
            <td align="right">{{ number_format($pinkel->alokasi) }}</td>
            <td align="right">{{ number_format(($pinkel->alokasi * $pinkel->pros_jasa) / 100) }}</td>
        </tr>
        <tr>
            <td>Nama Kelompok </td>
            <td colspan="3"><b>: {{ $pinkel->kelompok->nama_kelompok }}</b></td>
            <td>Target Pengembalian (x)</td>
            <td align="right">{{ number_format($ra->target_pokok) }}</td>
            <td align="right">{{ number_format($ra->target_jasa) }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td colspan="3">: {{ $pinkel->kelompok->alamat_kelompok }} {{ $kec->nama_kec }}</td>
            <td class="bottom">Realisasi Pengembalian</td>
            <td class="bottom" align="right">{{ number_format($real->sum_pokok) }}</td>
            <td class="bottom" align="right">{{ number_format($real->sum_jasa) }}</td>
        </tr>
        <tr>
            <td>Tanggal Cair </td>
            <td colspan="3">: {{ Tanggal::tglLatin($pinkel->tgl_cair) }}</td>
            <th>Saldo Pinjaman</th>
            <th align="right">{{ number_format($real->saldo_pokok) }}</th>
            <th align="right">{{ number_format($real->saldo_jasa) }}</th>
        </tr>
        <tr>
            <td>Sistem Angsuran </td>
            <td colspan="3">:
                {{ $pinkel->jangka }} {{ '@' . $pinkel->sis_pokok->nama_sistem }} = {{ $jum_angsuran }} x
            </td>

        </tr>
        <tr>
            <td>Pokok</td>
            <td>: </td>
            <td width="10%">
                <div align="right">{{ number_format($real->realisasi_pokok) }}</div>
            </td>
            <td width="12%">&nbsp;</td>
            <th class="bottom top">TAGIHAN BULAN DEPAN</th>
            <th class="bottom top">POKOK</th>
            <th class="bottom top">JASA</th>
        </tr>
        <tr>
            <td>Jasa</td>
            <td>: </td>
            <td>
                <div align="right">{{ number_format($real->realisasi_jasa) }}</div>
            </td>
            <td>&nbsp;</td>
            <td>Tunggakan s.d. Bulan Ini</td>
            <td align="right">{{ number_format($tunggakan_pokok) }}</td>
            <td align="right">{{ number_format($tunggakan_jasa) }}</td>
        </tr>
        <tr>
            <td class="bottom">Denda</td>
            <td class="bottom">: </td>
            <td class="bottom">
                <div align="right">{{ number_format($denda) }}</div>
            </td>
            <td class="bottom">&nbsp;</td>
            <td class="bottom">Angsuran Bulan Depan</td>
            <td align="right" style="border-bottom:1px solid #000;">
                {{ number_format($pokok_bulan_depan) }}
            </td>
            <td align="right" style="border-bottom:1px solid #000;">
                {{ number_format($jasa_bulan_depan) }}
            </td>
        </tr>
        <tr>
            <th height="27">JUMLAH BAYAR </th>
            <td>:</td>
            <td>
                <div align="right">
                    <b>
                        {{ number_format($real->realisasi_pokok + $real->realisasi_jasa + $denda) }}
                    </b>
                </div>
            </td>
            <td>&nbsp;</td>
            <th class="bottom">TOTAL TAGIHAN (M+1)</td>
            <th align="right" style="border-bottom:1px solid #000;">
                {{ number_format($tunggakan_pokok + $pokok_bulan_depan + ($tunggakan_jasa + $jasa_bulan_depan)) }}
            </th>
            <th align="right" style="border-bottom:1px solid #000;">&nbsp;</th>

        </tr>

        <tr>
            <td colspan="4">Terbilang : </td>
            <td>
                <div align="center">Diterima Oleh </div>
            </td>
            <td rowspan="5">&nbsp;</td>
            <td>
                <div align="center">Penyetor,</div>
            </td>
        </tr>
        <tr>
            <th colspan="4" rowspan="3">
                {{ strtoupper($keuangan->terbilang($real->realisasi_pokok + $real->realisasi_jasa + $denda)) }} RUPIAH
            </th>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="2" class="style2 top">
                - <br>
                - Dicetak pada {{ date('Y-m-d H:i:s A') }}<br>
                - Lembar 1 untuk Kelompok, lembar 2 Arsip DBM<br>
                - Bawalah kartu angsuran dan slip ini pada saat mengangsur bulan depan<br>
                - Cek status pinjaman kelompok anda di {{ $kec->web_kec }} </td>
            <th valign="top">
                <div align="center" class="bottom">
                    @if ($user)
                        {{ $user->namadepan . ' ' . $user->namabelakang }}
                    @endif
                </div>
            </th>
            <th valign="top">
                <div align="center" class="bottom">&nbsp;</div>
            </th>
        </tr>
        <tr>
            <th colspan="3" valign="middle">&nbsp;</th>
        </tr>
    </table>

    <title>Struk Angsuran Kelompok {{ $pinkel->kelompok->nama_kelompok }} &mdash; {{ $pinkel->id }}</title>
</body>