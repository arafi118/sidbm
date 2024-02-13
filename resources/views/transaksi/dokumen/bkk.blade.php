<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUKTI KAS KELUAR</title>
    <style>
        body {
            font-size: 10px;
            color: rgba(0, 0, 0, 0.8);
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            width: 100%;
            overflow: auto;
            margin: auto;
        }

        .box {
            display: inline-block;
            box-sizing: border-box;
            vertical-align: top;
            width: 47%;
            height: 8cm;
            border: 2px solid #000;
            padding: 10px;
            margin-bottom: 4px;
        }

        .box-body {
            padding-top: 0px;
            padding-left: 20px;
            padding-right: 20px;
        }

        .keterangan {
            padding: 1.5px 4px;
            font-weight: bold;
        }

        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="box">
            <table border="0" width="100%" style="border-bottom: 1px solid #000;">
                <tr>
                    <td width="40">
                        <img src="../storage/app/public/logo/{{ $gambar }}" width="50" height="50">
                    </td>
                    <td>
                        <div class="fw-bold">{{ strtoupper($kec->nama_lembaga_sort) }}</div>
                        <div class="fw-bold">
                            {{ strtoupper('Kec. ' . $kec->nama_kec . ' Kab. ' . $kec->kabupaten->nama_kab . ' ' . $kec->kabupaten->nama_prov) }}
                        </div>
                        <div style="font-size: 8px;">{{ 'SK Kemenkumham RI No. ' . $kec->nomor_bh }}</div>
                        <div style="font-size: 8px;">{{ $kec->alamat_kec . ', Telp. ' . $kec->telpon_kec }}</div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; font-size: 8px;">
                            <table>
                                <tr>
                                    <td>Nomor</td>
                                    <td>:</td>
                                    <td><?php echo $trx->idt . '/BKK'; ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal</td>
                                    <td>:</td>
                                    <td>{{ Tanggal::tglIndo($trx->tgl_transaksi) }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="box-body">
                <table width="100%">
                    <tr>
                        <td colspan="5" class="fs-10" align="center">
                            <h1>BUKTI KAS KELUAR</h1>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Dibayar Kepada</td>
                        <td width="2%">:</td>
                        @if ($trx->id_pinj > 0)
                            <td colspan="3" class="keterangan">{{ ucwords('Kelompok ' . $trx->relasi) }}</td>
                        @else
                            <td colspan="3" class="keterangan">{{ ucwords($trx->relasi) }}</td>
                        @endif
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
                        <td colspan="3">&nbsp;</td>
                    </tr>
                </table>

                <table width="100%">
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
