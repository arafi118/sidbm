<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUKTI MEMORIAL</title>
    <style>
        body {
            font-size: 9px;
            color: rgba(0, 0, 0, 0.8);
            font-family: Arial, Helvetica, sans-serif;
            padding: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .box {
            width: 14cm;
            height: 9cm;
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
            padding: 2px 4px;
            font-weight: normal;
        }

        .jajargenjang {
            background-color: rgba(0, 0, 0, 0.2);
            -ms-transform: skew(-20deg);
            -webkit-transform: skew(-20deg);
            transform: skew(-20deg);
            text-align: center;
        }

        .border-b {
            border-bottom: 1px solid rgba(0, 0, 0, 0.4);
        }

        .text-left {
            padding-left: 6px;
            padding-right: 6px;
            padding-top: 2px;
            padding-bottom: 4px;
            text-align: left;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container">
        <div class="box">
            <div class="box-header flex align-items-center justify-content-between fs-10">
                <div class="flex align-items-center">
                    <img src="<?php echo $gambar; ?>" width="50" height="50">
                    <div class="ml-4">
                        <div class="block fw-bold">{{ strtoupper($kec->nama_lembaga_sort) }}</div>
                        <div class="block fw-bold">
                            {{ strtoupper('Kec. ' . $kec->nama_kec . ' Kab. ' . $kec->kabupaten->nama_kab . ' ' . $kec->kabupaten->nama_prov) }}
                        </div>
                        <div class="block fs-10">{{ 'SK Kemenkumham RI No. ' . $kec->nomor_bh }}</div>
                        <div class="block fs-10">{{ $kec->alamat_kec . ', Telp. ' . $kec->telpon_kec }}</div>
                    </div>
                </div>
                <div class="justify-right">
                    <table>
                        <tr>
                            <td>Nomor</td>
                            <td>:</td>
                            <td><?php echo $trx->idt . '/BM'; ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>{{ Tanggal::tglIndo($trx->tgl_transaksi) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="box-body fs-12">
                <table width="100%">
                    <tr>
                        <td colspan="5" class="fs-10" align="center">
                            <h1>BUKTI MEMORIAL</h1>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Keterangan</td>
                        <td width="2%">:</td>
                        <td colspan="3" class="keterangan">
                            {{ ucwords($trx->keterangan_transaksi) }}
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Jumlah</td>
                        <td width="2%">:</td>
                        <td colspan="3" class="keterangan">
                            Rp. {{ number_format($trx->jumlah, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Kode Akun (D/K)</td>
                        <td width="2%">&nbsp;</td>
                        <td colspan="3" class="keterangan">
                            Debit {{ ucwords($trx->rekening_debit . ' - ' . $trx->rek_debit->nama_akun) }}
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">&nbsp;</td>
                        <td width="2%">&nbsp;</td>
                        <td colspan="3" class="keterangan">
                            Kredit {{ ucwords($trx->rekening_kredit . ' - ' . $trx->rek_kredit->nama_akun) }}
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">&nbsp;</td>
                        <td width="2%">&nbsp;</td>
                        <td colspan="3" class="keterangan">&nbsp;</td>
                    </tr>
                </table>

                <table width="100%" class="fs-12" style="margin-top: 12px;">
                    <tr>
                        <td align="center">Disetujui,</td>
                        <td align="center">Diverifikasi,</td>
                        <td align="center">Disiapkan Oleh :</td>
                    </tr>
                    <tr>
                        <td align="center"><?php echo $kec->sebutan_level_1; ?></td>
                        <td align="center"><?php echo $kec->sebutan_level_3; ?></td>
                        <td align="center">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center">{{ $dir->namadepan . ' ' . $dir->namabelakang }}</td>
                        <td align="center">{{ $sekr->namadepan . ' ' . $sekr->namabelakang }}</td>
                        <td align="center"><?php echo $kec->disiapkan; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
