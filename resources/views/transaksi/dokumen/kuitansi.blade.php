@php
    use App\Utils\Tanggal;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K U I T A N S I</title>
    <style>
        body {
            font-size: 12px;
            color: rgba(0, 0, 0, 0.8);
            /* width: 20cm; */
            font-family: Arial, Helvetica, sans-serif;
            font-weight: medium;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .font-roboto {
            font-family: Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        .container {
            position: relative;
            width: 20cm;
            height: 10cm;
            padding: 20px;
            /*border: 1px solid rgba(0, 0, 0, 0.1);*/
        }

        .box {
            border: 2px solid #000;
            padding-top: 16px;
            padding-bottom: 12px;
            padding-right: 22px;
            padding-left: 12px;
        }

        .box-header {
            padding-left: 16px;
            padding-right: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.5);
        }

        .flex {
            display: flex;
        }

        .block {
            display: block;
        }

        .inline-block {
            display: inline-block;
        }

        .fw-bold {
            font-weight: bold;
        }

        .fs-8 {
            font-size: 8px;
        }

        .fs-10 {
            font-size: 10px;
        }

        .fs-12 {
            font-size: 12px;
        }

        .fs-14 {
            font-size: 14px;
        }

        .fs-16 {
            font-size: 16px;
        }

        .-mt-2 {
            margin-top: -2px;
        }

        .ml-4 {
            margin-left: 4px;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .box-body {
            padding-top: 0px;
            padding-left: 24px;
            padding-right: 24px;
        }

        .keterangan {
            padding: 6px;
            padding-top: 4px;
            padding-bottom: 4px;
        }

        .jajargenjang {
            background-color: rgb(204, 204, 204);
            -ms-transform: skew(-20deg);
            -webkit-transform: skew(-20deg);
            transform: skew(-20deg);
            text-align: center;
        }

        .terbilang {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        @media print {
            .jajargenjang {
                background-color: rgb(204, 204, 204);
                -ms-transform: skew(-20deg);
                -webkit-transform: skew(-20deg);
                transform: skew(-20deg);
                text-align: center;
            }
        }

        h4 {
            margin: 14px 0;
            width: 100%;
            text-align: center;
            border-bottom: 1px dashed #000;
            line-height: 0px;
            font-style: italic;
        }

        h4 span {
            background: rgb(204, 204, 204);
            padding: 0 10px;
        }

        .border-b {
            border-bottom: 1px dashed rgba(0, 0, 0, 0.4);
        }

        .text-left {
            padding-left: 6px;
            padding-right: 6px;
            padding-top: 2px;
            padding-bottom: 4px;
            text-align: left;
        }

        .fw-medium {
            font-weight: 600;
        }

        .tanggal {
            font-size: 8px;
            margin-left: 16px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container">
        <div class="box">
            <div class="box-header flex align-items-center justify-content-between">
                <div class="flex align-items-center">
                    <img src="{{ $gambar }}" width="50" height="50">
                    <div class="ml-4">
                        <div class="block fw-bold">{{ strtoupper($kec->nama_lembaga_sort) }}</div>
                        <div class="block fw-bold">
                            {{ strtoupper('Kec. ' . $kec->nama_kec . ' Kab. ' . $kec->kabupaten->nama_kab . ' ' . $kec->kabupaten->nama_prov) }}
                        </div>
                        <div class="block fs-10">{{ 'SK Kemenkumham RI No. ' . $kec->nomor_bh }}</div>
                        <div class="block fs-10">{{ $kec->alamat_kec . ', Telp. ' . $kec->telpon_kec }}</div>
                    </div>
                </div>
                <div class="fw-medium">
                    Nomor &nbsp; &nbsp; : {{ $trx->idt . '/' . $jenis }}
                </div>
            </div>
            <div class="box-body fs-14">
                <table width="100%">
                    <tr>
                        <td colspan="5" class="fs-10" align="center">
                            <h1 class="font-roboto">K U I T A N S I</h1>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Telah Terima Dari</td>
                        <td width="2%">:</td>
                        <td colspan="3" class="keterangan">{{ $dari }}</td>
                    </tr>
                    <tr>
                        <td width="30%">Uang Sebanyak</td>
                        <td width="2%">:</td>
                        <td colspan="3" class="keterangan fw-medium terbilang jajargenjang">
                            <h4 {!! strlen($keuangan->terbilang($trx->jumlah)) > 30 ? 'style="font-size: 8px;"' : '' !!}>
                                <span>{{ ucwords($keuangan->terbilang($trx->jumlah)) }} Rupiah</span>
                            </h4>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Untuk Pembayaran</td>
                        <td width="2%">:</td>
                        <td colspan="3" class="keterangan border-b">
                            {{ ucwords($trx->keterangan_transaksi) }}
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">&nbsp;</td>
                        <td width="2%">&nbsp;</td>
                        <td colspan="3" class="keterangan border-b"></td>
                    </tr>
                </table>

                <table width="100%" class="fs-12" style="margin-top: 8px;">
                    <tr>
                        <td width="40%" align="center" rowspan="6">
                            <i>
                                <h3 class="flex" style="padding-left: 18px;">
                                    Terbilang Rp. &nbsp; <div class="jajargenjang text-left">
                                        {{ number_format($trx->jumlah, 2) }}</div>
                                </h3>
                            </i>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td align="center">{{ $kec->nama_kec . ', ' . Tanggal::tglLatin($trx->tgl_transaksi) }}</td>
                    </tr>
                    <tr>
                        <td width="30%" align="center">Dibayar Oleh</td>
                        <td width="30%" align="center">Diterima Oleh</td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center">{{ $dibayar }}</td>
                        <td align="center">{{ $oleh }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="tanggal">Dicetak pada {{ date('Y-m-d H:i:s') }}</div>
    </div>
</body>

</html>
