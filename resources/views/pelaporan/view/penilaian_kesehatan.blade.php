@php
    use App\Utils\Keuangan;
    $keuangan = new Keuangan();
@endphp
@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>PENILAIAN KESEHATAN</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($kec->nama_lembaga_sort) }}</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>

    </table>

    @php
        $biaya = $keuangan->biaya($tgl_kondisi);
        $pendapatan = $keuangan->pendapatan($tgl_kondisi);
        $surplus = $pendapatan - $biaya;
        
        $aset = $keuangan->aset($tgl_kondisi);
        $aset_produktif = $aset['aset_produktif'];
        $aset_ekonomi = $aset['aset_ekonomi'];
        $modal_awal = $keuangan->modal_awal($tgl_kondisi);
        
        $tk = $keuangan->tingkat_kesehatan($tgl_kondisi);
        $ckp = $aset['cadangan_piutang'];
        
        $_risiko_kolek = $tk['sum_kolek'] == 0 ? $ckp : $tk['sum_kolek'];
        
        $saldo_piutang_berisiko = @round(($tk['nunggak_pokok'] / $tk['saldo_pokok']) * 100, 2);
        $cadangan_kerugian = @round(($ckp / $_risiko_kolek) * 100, 2);
        $laba_bersih = @round((($surplus - ($tk['sum_kolek'] - $ckp)) / $aset_produktif) * 100, 2);
        $beban_operasional = @round(($biaya / $pendapatan) * 100);
        $saldo_piuang = @round(($tk['saldo_pokok'] / $aset_ekonomi) * 100);
        $kekayaan_bersih = @round((($aset_ekonomi - $tk['sum_kolek']) / $modal_awal) * 100);
        
        // Skor Baris 1
        if ($saldo_piutang_berisiko < 5) {
            $skor_b1 = 40;
            $status_b1 = 'Sehat';
        } elseif ($saldo_piutang_berisiko < 10) {
            $skor_b1 = 35;
            $status_b1 = 'Sehat';
        } elseif ($saldo_piutang_berisiko < 12.5) {
            $skor_b1 = 30;
            $status_b1 = 'Cukup Sehat';
        } elseif ($saldo_piutang_berisiko < 15) {
            $skor_b1 = 25;
            $status_b1 = 'Cukup Sehat';
        } elseif ($saldo_piutang_berisiko < 17.5) {
            $skor_b1 = 20;
            $status_b1 = 'Kurang Sehat';
        } elseif ($saldo_piutang_berisiko < 20) {
            $skor_b1 = 15;
            $status_b1 = 'Kurang Sehat';
        } elseif ($saldo_piutang_berisiko < 25) {
            $skor_b1 = 10;
            $status_b1 = 'Tidak Sehat';
        } else {
            $skor_b1 = 0;
            $status_b1 = 'Tidak Sehat';
        }
        
        // Skor Baris 2
        if ($cadangan_kerugian >= 100) {
            $skor_b2 = 20;
            $status_b2 = 'Sehat';
        } elseif ($cadangan_kerugian > 90) {
            $skor_b2 = 15;
            $status_b2 = 'Cukup Sehat';
        } elseif ($cadangan_kerugian > 80) {
            $skor_b2 = 12.5;
            $status_b2 = 'Cukup Sehat';
        } elseif ($cadangan_kerugian > 70) {
            $skor_b2 = 10;
            $status_b2 = 'Kurang Sehat';
        } elseif ($cadangan_kerugian > 60) {
            $skor_b2 = 7.5;
            $status_b2 = 'Kurang Sehat';
        } elseif ($cadangan_kerugian > 50) {
            $skor_b2 = 5;
            $status_b2 = 'Tidak Sehat';
        } else {
            $skor_b2 = 0;
            $status_b2 = 'Tidak Sehat';
        }
        
        // Skor Baris 3
        if ($laba_bersih >= 1) {
            $skor_b3 = 10;
            $status_b3 = 'Sehat';
        } elseif ($laba_bersih > 0.75) {
            $skor_b3 = 8.75;
            $status_b3 = 'Sehat';
        } elseif ($laba_bersih > 0.6) {
            $skor_b3 = 7.5;
            $status_b3 = 'Cukup Sehat';
        } elseif ($laba_bersih > 0.45) {
            $skor_b3 = 6.25;
            $status_b3 = 'Cukup Sehat';
        } elseif ($laba_bersih > 0.3) {
            $skor_b3 = 5;
            $status_b3 = 'Kurang Sehat';
        } elseif ($laba_bersih > 0.15) {
            $skor_b3 = 3.75;
            $status_b3 = 'Kurang Sehat';
        } elseif ($laba_bersih > 0) {
            $skor_b3 = 2.5;
            $status_b3 = 'Tidak Sehat';
        } else {
            $skor_b3 = 0;
            $status_b3 = 'Tidak Sehat';
        }
        
        // Skor Baris 4
        if ($beban_operasional < 60) {
            $skor_b4 = 10;
            $status_b4 = 'Sehat';
        } elseif ($beban_operasional < 65) {
            $skor_b4 = 8.75;
            $status_b4 = 'Sehat';
        } elseif ($beban_operasional < 70) {
            $skor_b4 = 5.75;
            $status_b4 = 'Cukup Sehat';
        } elseif ($beban_operasional < 75) {
            $skor_b4 = 6.25;
            $status_b4 = 'Cukup Sehat';
        } elseif ($beban_operasional < 80) {
            $skor_b4 = 5;
            $status_b4 = 'Kurang Sehat';
        } elseif ($beban_operasional < 85) {
            $skor_b4 = 3.75;
            $status_b4 = 'Kurang Sehat';
        } elseif ($beban_operasional < 95) {
            $skor_b4 = 2.5;
            $status_b4 = 'Tidak Sehat';
        } else {
            $skor_b4 = 0;
            $status_b4 = 'Tidak Sehat';
        }
        
        // Skor Baris 5
        if ($saldo_piuang >= 85) {
            $skor_b5 = 10;
            $status_b5 = 'Sehat';
        } elseif ($saldo_piuang > 80) {
            $skor_b5 = 7.5;
            $status_b5 = 'Cukup Sehat';
        } elseif ($saldo_piuang > 75) {
            $skor_b5 = 6.25;
            $status_b5 = 'Cukup Sehat';
        } elseif ($saldo_piuang > 70) {
            $skor_b5 = 5;
            $status_b5 = 'Kurang Sehat';
        } elseif ($saldo_piuang > 65) {
            $skor_b5 = 3.75;
            $status_b5 = 'Kurang Sehat';
        } elseif ($saldo_piuang > 65) {
            $skor_b5 = 2.5;
            $status_b5 = 'Tidak Sehat';
        } else {
            $skor_b5 = 0;
            $status_b5 = 'Tidak Sehat';
        }
        
        // Skor Baris 6
        if ($kekayaan_bersih >= 110) {
            $skor_b6 = 10;
            $status_b6 = 'Sehat';
        } elseif ($kekayaan_bersih > 107.5) {
            $skor_b6 = 8.75;
            $status_b6 = 'Sehat';
        } elseif ($kekayaan_bersih > 105) {
            $skor_b6 = 7.5;
            $status_b6 = 'Cukup Sehat';
        } elseif ($kekayaan_bersih > 102) {
            $skor_b6 = 6.25;
            $status_b6 = 'Cukup Sehat';
        } elseif ($kekayaan_bersih > 100) {
            $skor_b6 = 5;
            $status_b6 = 'Kurang Sehat';
        } elseif ($kekayaan_bersih > 97.5) {
            $skor_b6 = 3.75;
            $status_b6 = 'Kurang Sehat';
        } elseif ($kekayaan_bersih > 95) {
            $skor_b6 = 2.5;
            $status_b6 = 'Tidak Sehat';
        } else {
            $skor_b6 = 0;
            $status_b6 = 'Tidak Sehat';
        }
        
        // Total Bawah
        $skor = $skor_b1 + $skor_b2 + $skor_b3 + $skor_b4 + $skor_b5 + $skor_b6;
        if ($skor > 87.5) {
            $status = 'Sehat';
        } elseif ($skor > 62.5) {
            $status = 'Cukup Sehat';
        } elseif ($skor > 37.5) {
            $status = 'Kurang Sehat';
        } else {
            $status = 'Tidak Sehat';
        }
    @endphp

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr style="background: rgb(232, 232, 232)">
            <th rowspan="2" width="10">No</th>
            <th rowspan="2" width="160">Rasio</th>
            <th rowspan="2" width="170">Parameter</th>
            <th colspan="4">Klasifikasi Tingkat Kesehatan</th>
            <th rowspan="2">Skor</th>
            <th rowspan="2">Status</th>
        </tr>
        <tr style="background: rgb(232, 232, 232)">
            <th width="75">Sehat</th>
            <th width="75">Cukup Sehat</th>
            <th width="75">Kurang Sehat</th>
            <th width="75">Tidak Sehat</th>
        </tr>

        <tr>
            <td align="center" rowspan="2">1</td>
            <td rowspan="2" align="center">Rasio Saldo Piutang Berisiko</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. Tunggakan</td>
                        <td align="right">{{ number_format($tk['nunggak_pokok']) }}</td>
                    </tr>
                    <tr>
                        <td>b. Saldo piutang</td>
                        <td align="right">{{ number_format($tk['saldo_pokok']) }}</td>
                    </tr>
                </table>
            </td>
            <td rowspan="2">
                <div>{{ 'R < 5% skor 40' }}</div>
                <div>{{ 'R < 10% skor 35' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R < 12.5% skor 30' }}</div>
                <div>{{ 'R < 15% skor 25' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R < 17.5% skor 20' }}</div>
                <div>{{ 'R < 20% skor 15' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R < 25% skor 10' }}</div>
                <div>{{ 'R >= 25% skor 0' }}</div>
            </td>
            <td rowspan="2" align="center">{{ $skor_b1 }}</td>
            <td rowspan="2" align="center">{{ $status_b1 }}</td>
        </tr>
        <tr>
            <td height="8">
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>Persentase (a/bx100%)</td>
                        <td align="right">{{ number_format($saldo_piutang_berisiko) }}%</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td align="center" rowspan="2">2</td>
            <td rowspan="2" align="center">Rasio Cadangan Kerugian Piutang</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. CKP yang dimiliki</td>
                        <td align="right">{{ number_format($ckp) }}</td>
                    </tr>
                    <tr>
                        <td>b. Risiko kolektibilitas</td>
                        <td align="right">{{ number_format($tk['sum_kolek']) }}</td>
                    </tr>
                </table>
            </td>
            <td rowspan="2">
                <div>{{ 'R >= 100% skor 20' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 90% skor 15' }}</div>
                <div>{{ 'R > 80% skor 12.5' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 70% skor 10' }}</div>
                <div>{{ 'R > 60% skor 7.5' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 50% skor 5' }}</div>
                <div>{{ 'R <= 50% skor 0' }}</div>
            </td>
            <td rowspan="2" align="center">{{ $skor_b2 }}</td>
            <td rowspan="2" align="center">{{ $status_b2 }}</td>
        </tr>
        <tr>
            <td height="8">
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>Persentase (a/bx100%)</td>
                        <td align="right">{{ number_format($cadangan_kerugian) }}%</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td align="center" rowspan="2">3</td>
            <td rowspan="2" align="center">Rasio Laba Bersih Terhadap Kekayaan Bumdesma</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. Laba berjalan</td>
                        <td align="right">{{ number_format($surplus) }}</td>
                    </tr>
                    <tr>
                        <td>b. Risiko kolektibilitas</td>
                        <td align="right">{{ number_format($tk['sum_kolek']) }}</td>
                    </tr>
                    <tr>
                        <td>c. CKP yang dimiliki</td>
                        <td align="right">{{ number_format($ckp) }}</td>
                    </tr>
                    <tr>
                        <td>d. Aset produktif</td>
                        <td align="right">{{ number_format($aset_produktif) }}</td>
                    </tr>
                </table>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 1% skor 10' }}</div>
                <div>{{ 'R > 0.75% skor 8.75' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 0.6% skor 7.5' }}</div>
                <div>{{ 'R > 0.45% skor 6.25' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 0.3% skor 5' }}</div>
                <div>{{ 'R > 0.15% skor 3.75' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 0% skor 2.5' }}</div>
                <div>{{ 'R <= 0% skor 0' }}</div>
            </td>
            <td rowspan="2" align="center">{{ $skor_b3 }}</td>
            <td rowspan="2" align="center">{{ $status_b3 }}</td>
        </tr>
        <tr>
            <td height="8">
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>Persentase (a-(b-c)/dx100%)</td>
                        <td align="right">{{ number_format($laba_bersih) }}%</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td align="center" rowspan="2">4</td>
            <td rowspan="2" align="center">Rasio Beban Operasi</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. Akumulasi beban</td>
                        <td align="right">{{ number_format($biaya) }}</td>
                    </tr>
                    <tr>
                        <td>b. Akumulasi pendapatan</td>
                        <td align="right">{{ number_format($pendapatan) }}</td>
                    </tr>
                </table>
            </td>
            <td rowspan="2">
                <div>{{ 'R < 60% skor 10' }}</div>
                <div>{{ 'R < 65% skor 8.75' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R < 70% skor 5.75' }}</div>
                <div>{{ 'R < 75% skor 6.25' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R < 80% skor 5' }}</div>
                <div>{{ 'R < 85% skor 3.75' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R < 95% skor 2.5' }}</div>
                <div>{{ 'R >= 95% skor 0' }}</div>
            </td>
            <td rowspan="2" align="center">{{ $skor_b4 }}</td>
            <td rowspan="2" align="center">{{ $status_b4 }}</td>
        </tr>
        <tr>
            <td height="8">
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>Persentase (a/bx100%)</td>
                        <td align="right">{{ number_format($beban_operasional) }}%</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td align="center" rowspan="2">5</td>
            <td rowspan="2" align="center">Rasio Saldo Piutang Terhadap Kekayaan Bumdesma Non Investasi</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. Saldo piutang usaha</td>
                        <td align="right">{{ number_format($tk['saldo_pokok']) }}</td>
                    </tr>
                    <tr>
                        <td>b. Aset produktif non investasi</td>
                        <td align="right">{{ number_format($aset_ekonomi) }}</td>
                    </tr>
                </table>
            </td>
            <td rowspan="2">
                <div>{{ 'R >= 85% skor 10' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 80% skor 7.5' }}</div>
                <div>{{ 'R > 75% skor 6.5' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 70% skor 5' }}</div>
                <div>{{ 'R > 65% skor 3.75' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 65% skor 2.5' }}</div>
                <div>{{ 'R <= 65% skor 0' }}</div>
            </td>
            <td rowspan="2" align="center">{{ $skor_b5 }}</td>
            <td rowspan="2" align="center">{{ $status_b5 }}</td>
        </tr>
        <tr>
            <td height="8">
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>Persentase (a/bx100%)</td>
                        <td align="right">{{ number_format($saldo_piuang) }}%</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td align="center" rowspan="2">6</td>
            <td rowspan="2" align="center">Rasio Kekayaan Bersih Budesma</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. Aset produktif non investasi </td>
                        <td align="right">{{ number_format($aset_ekonomi) }}</td>
                    </tr>
                    <tr>
                        <td>b. Risiko kolektibilitas </td>
                        <td align="right">{{ number_format($tk['sum_kolek']) }}</td>
                    </tr>
                    <tr>
                        <td>c. Modal disetor bumdesma </td>
                        <td align="right">{{ number_format($modal_awal) }}</td>
                    </tr>
                </table>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 110% skor 10' }}</div>
                <div>{{ 'R > 107.5% skor 8.75' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 105% skor 7.5' }}</div>
                <div>{{ 'R > 102% skor 6.25' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 100% skor 5' }}</div>
                <div>{{ 'R > 97.5% skor 3.75' }}</div>
            </td>
            <td rowspan="2">
                <div>{{ 'R > 95% skor 2.5' }}</div>
                <div>{{ 'R < 95% skor 0' }}</div>
            </td>
            <td rowspan="2" align="center">{{ $skor_b6 }}</td>
            <td rowspan="2" align="center">{{ $status_b6 }}</td>
        </tr>
        <tr>
            <td height="8">
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>Persentase ((a-b)/cx100%)</td>
                        <td align="right">{{ number_format($kekayaan_bersih) }}%</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr style="font-weight: bold;">
            <td colspan="3">
                Komulatif skor dalam parameter Bumdesma dengan usaha utama DBM
            </td>
            <td align="center">{{ '> 87.5 - 100' }}</td>
            <td align="center">{{ '> 62.5 - 87.5' }}</td>
            <td align="center">{{ '> 37.5 - 62.5' }}</td>
            <td align="center">{{ '<= 37.5' }}</td>
            <td align="center">{{ $skor }}</td>
            <td align="center">{{ $status }}</td>
        </tr>
    </table>
@endsection
