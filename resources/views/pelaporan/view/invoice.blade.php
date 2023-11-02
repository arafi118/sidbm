@php
    use App\Utils\Tanggal;

    $dari = Tanggal::tahun($inv->tgl_invoice) . '-' . Tanggal::bulan($inv->kec->tgl_pakai) . '-' . Tanggal::hari($inv->kec->tgl_pakai);
    $sampai = date('Y-m-d', strtotime('+1 years', strtotime($dari)));

    if ($inv->status == 'UNPAID') {
        $tanggal = $inv->tgl_invoice;
        $keterangan_tanggal = 'Jatuh Tempo';
        $status = 'U N P A I D';
        $keterangan = 'Masa Pakai Sejak Tanggal ' . Tanggal::tglIndo($inv->kec->tgl_pakai);
    } elseif ($inv->status == 'PAID') {
        $tanggal = $inv->tgl_lunas;
        $keterangan_tanggal = 'Tanggal Lunas';
        $status = 'P A I D';
        $keterangan = 'Masa Aktif Tanggal ' . Tanggal::tglIndo($dari) . ' - ' . Tanggal::tglIndo($sampai);
    }

    $batas_waktu = date('Y-m-d', strtotime('+1 month', strtotime($inv->tgl_invoice)));
    $total = 0;

    $kecamatan = $inv->kec->sebutan_kec . ' ' . $inv->kec->nama_kec;
    if (Keuangan::startWith($inv->kec->kabupaten->nama_kab, 'KOTA') || Keuangan::startWith($inv->kec->kabupaten->nama_kab, 'KAB')) {
        $kecamatan .= ' ' . ucwords(strtolower($inv->kec->kabupaten->nama_kab));
    } else {
        $kecamatan .= ' Kabupaten ' . ucwords(strtolower($inv->kec->kabupaten->nama_kab));
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INVOICE #{{ $inv->nomor }}</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        .line {
            width: 100%;
            border-top: 1px solid rgb(21, 85, 92);
        }

        main {
            padding-top: 12px;
            padding-left: 32px;
            padding-right: 14px;
        }

        table tr th,
        table tr td,
        table tr td table.p tr td {
            padding: 4px 6px !important;
            vertical-align: middle;
        }

        table tr.top th,
        table tr.top td,
        table tr.top td table.p tr td {
            padding: unset !important;
            vertical-align: top !important;
        }

        .title {
            font-size: 18px;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            color: rgb(21, 85, 92);
        }

        .subtitle {
            font-size: 12px;
            font-family: 'Courier New', Courier, monospace;
            font-style: italic;
            color: rgb(21, 85, 92);
            margin-bottom: 4px;
        }

        .desc {
            font-size: 11px;
            font-family: Arial, Helvetica, sans-serif;
            font-style: italic;
            color: rgb(21, 85, 92);
        }

        footer {
            width: 100%;
            position: fixed;
            bottom: -12px;
            left: 0;
            text-align: center;
            border-top: 1px solid #000;
        }

        footer * {
            font-size: 10px !important;
        }
    </style>
</head>

<body>

    <main>
        <div class="line"></div>
        <div class="line" style="margin-top: 3px;"></div>

        <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td width="62">
                    <img src="../public/assets/img/abt_logo.png" width="88" alt="">
                </td>
                <td>
                    <div class="title">PT. ASTA BRATA TEKNOLOGI</div>
                    <div class="subtitle">IT Colsulting, System, Training and Digital Audits</div>
                    <div class="desc">
                        SK. Kementerian Hukum dan HAM RI Nomor. AHU-01329.40.10.2014 - NPWP. 66.867.912.9-524.000
                    </div>
                    <div class="desc">
                        Office : Jalan Perintis Kemerdekaan Km 1.5, Banyuurip Tegalrejo Magelang Jawa Tengah Indonesia
                    </div>
                    <div class="desc">
                        Telp.: (0293) 319 555 8 | E-mail: info@astabratagroup.com | Website: astabratagroup.com
                    </div>
                </td>
            </tr>
        </table>
        <div class="line"></div>
        <div class="line" style="margin-top: 3px;"></div>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin-top: 16px;" class="top">
            <tr class="top">
                <td width="70%">
                    <div style="font-size: 16px; font-weight: bold;">INVOICE #{{ $inv->nomor }}</div>
                    <div style="margin-top: 2px; font-size: 10px">Tanggal Invoice :
                        {{ Tanggal::tglIndo($inv->tgl_invoice) }}</div>
                    <div style="font-size: 10px">Jatuh Tempo : {{ Tanggal::tglIndo($batas_waktu) }}</div>
                </td>
                <td width="30%" align="center">
                    <div style="font-size: 16px; font-weight: bold; color: #ff0000">{{ $status }}</div>
                    <img src="../public/assets/img/lunas.png" alt="" height="68"
                        style="margin-top: 8px; {{ $inv->status == 'UNPAID' ? 'visibility: hidden; opacity:0;' : '' }}">
                </td>
            </tr>
            <tr class="top">
                <td colspan="2">
                    <div style="font-weight: bold;">Dikirim Kepada :</div>
                    <div style="font-weight: bold; margin-top: 8px;">
                        {{ strtoupper($inv->kec->nama_lembaga_sort) }}
                    </div>
                    <div style="font-weight: bold;">
                        {{ strtoupper($kecamatan) }}
                    </div>
                    <div>
                        {{ ucwords(strtolower($inv->kec->alamat_kec)) }}
                    </div>
                    <div>Telp. {{ $inv->kec->telpon_kec }}</div>
                </td>
            </tr>
            <tr class="top">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr style="color: #fff;">
                <th height="14" style="background: rgb(29, 97, 122)">DESCRIPTION</th>
                <th style="background: rgb(130, 164, 176)">TOTAL</th>
            </tr>
            <tr style="background: rgb(232, 232, 232)">
                <td height="70" align="center" style="font-size: 11px;">
                    <div>
                        {{ $inv->jp->nama_jp }} SI DBM {{ $inv->kec->sebutan_kec }} {{ $inv->kec->nama_kec }}
                    </div>
                    <div>{{ $keterangan }}</div>
                </td>
                <td align="right" style="font-size: 11px;">{{ number_format($inv->jumlah, 2) }}</td>
            </tr>
            <tr style="font-weight: bold;">
                <td align="right" style="font-size: 11px;">Sub Total</td>
                <td align="right" style="font-size: 11px; color: #fff; background: rgb(130, 164, 176)">
                    {{ number_format($inv->jumlah, 2) }}
                </td>
            </tr>
            <tr style="font-weight: bold;">
                <td align="right" style="font-size: 11px;">PPN 10%</td>
                <td align="right" style="font-size: 11px; color: #fff; background: rgb(130, 164, 176)">
                    {{ number_format(($inv->jumlah * 10) / 100, 2) }}
                </td>
            </tr>
            <tr style="font-weight: bold;">
                <td align="right" style="font-size: 11px;">Discount 10%</td>
                <td align="right" style="font-size: 11px; color: #fff; background: rgb(130, 164, 176)">
                    -{{ number_format(($inv->jumlah * 10) / 100, 2) }}
                </td>
            </tr>
            <tr style="font-weight: bold;">
                <td align="right" style="font-size: 11px;">TOTAL</td>
                <td align="right" style="font-size: 11px; color: #fff; background: rgb(130, 164, 176)">
                    {{ number_format($inv->jumlah, 2) }}
                </td>
            </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin-top: 16px;">
            <tr>
                <td colspan="4" align="left">
                    <div style="font-weight: bold;">Transaksi</div>
                </td>
            </tr>
            <tr style="background: rgb(232, 232, 232)">
                <th width="15%">
                    ID Transaksi
                </th>
                <th width="10%">
                    Tanggal
                </th>
                <th width="60%">Keterangan / Metode Pembayaran</th>
                <th width="15%">Jumlah</th>
            </tr>
            @if (count($inv->trx) > 0)
                @foreach ($inv->trx as $trx)
                    @php
                        $keterangan = '';
                        if ($trx->rekening_debit == '111.1001') {
                            $keterangan = 'Via Kas';
                        } elseif ($trx->rekening_debit == '121.1001') {
                            $keterangan = 'Via Transfer Bank';
                        }
                    @endphp
                    <tr>
                        <td align="center">{{ $trx->idt }}/SI-DBM</td>
                        <td align="center">{{ Tanggal::tglIndo($trx->tgl_transaksi) }}</td>
                        <td align="center">{{ $trx->keterangan_transaksi }} {{ $keterangan }}</td>
                        <td align="right">{{ number_format($trx->jumlah, 2) }}</td>
                    </tr>

                    @php
                        $total += $trx->jumlah;
                    @endphp
                @endforeach
            @else
                <tr>
                    <td colspan="4" align="center">
                        Belum Ada Transaksi Pembayaran
                    </td>
                </tr>
                <tr>
                    <td colspan="4" align="center">
                        Silahkan melakukan pembayaran sebelum Tanggal {{ TanggaL::tglIndo($batas_waktu) }}
                    </td>
                </tr>
            @endif

            <tr style="background: rgb(232, 232, 232)">
                <th width="85%" align="right" colspan="3">Total Pembayaran</th>
                <th width="15%" align="right">{{ number_format($total, 2) }}</th>
            </tr>
            <tr style="background: rgb(232, 232, 232)">
                <th width="85%" align="right" colspan="3">Sisa Tagihan</th>
                <th width="15%" align="right">{{ number_format($inv->jumlah - $total, 2) }}</th>
            </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin-top: 48px;">
            <tr>
                <td width="60%">&nbsp;</td>
                <td width="40%" align="center">
                    <div>Direktur Utama</div>
                    <div>PT. Asta Brata Teknologi</div>
                </td>
            </tr>
            <tr>
                <td height="60">&nbsp;</td>
                <td height="60" align="center" style="position: relative;">
                    <img style="position: absolute; top: -32px; left: 28px;" src="../public/assets/img/ttd.png"
                        width="220" alt="">
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td align="center" style="font-weight: bold;">S A N T O S O</td>
            </tr>
            <tr>
                <td>
                    <div style="font-weight: bold;">
                        Pembayaran Transfer Via :
                    </div>
                    <ul style="padding-left: 16px; margin-top: 0px;">
                        <li>
                            <div>Bank Mandiri Nomor Rekening : 185-000-417-4733</div>
                            <div>an. Santoso</div>
                        </li>
                        <li>
                            <div>Bank BRI Nomor Rekening : 0048-01-057317-50-5</div>
                            <div>an. Santoso</div>
                        </li>
                    </ul>
                </td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </main>

    <footer>
        <div>www.sidbm.net</div>
        <div>&copy; {{ date('Y') }}</div>
    </footer>
</body>

</html>
