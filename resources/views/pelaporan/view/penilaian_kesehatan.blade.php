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
        
    @endphp

    <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
        <tr>
            <th rowspan="2" width="10">No</th>
            <th rowspan="2" width="180">Rasio</th>
            <th rowspan="2" width="150">Parameter</th>
            <th colspan="4">Klasifikasi Tingkat Kesehatan</th>
            <th rowspan="2">Skor</th>
            <th rowspan="2">Status</th>
        </tr>
        <tr>
            <th width="75">Sehat</th>
            <th width="75">Cukup Sehat</th>
            <th width="75">Kurang Sehat</th>
            <th width="75">Tidak Sehat</th>
        </tr>

        <tr>
            <td align="center" rowspan="2">1</td>
            <td rowspan="2">Rasio Saldo Piutang Berisiko</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. Tunggakan</td>
                        <td align="right">ds</td>
                    </tr>
                    <tr>
                        <td>b. Saldo piutang</td>
                        <td align="right">ds</td>
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
            <td rowspan="2"></td>
            <td rowspan="2"></td>
        </tr>
        <tr>
            <td height="8">Persentase (a/bx100%)</td>
        </tr>

        <tr>
            <td align="center" rowspan="2">2</td>
            <td rowspan="2">Rasio Cadangan Kerugian Piutang</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. CKP yang dimiliki</td>
                        <td align="right">ds</td>
                    </tr>
                    <tr>
                        <td>b. Risiko kolektibilitas</td>
                        <td align="right">ds</td>
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
            <td rowspan="2"></td>
            <td rowspan="2"></td>
        </tr>
        <tr>
            <td height="8">Persentase (a/bx100%)</td>
        </tr>

        <tr>
            <td align="center" rowspan="2">3</td>
            <td rowspan="2">Rasio Laba Bersih Terhadap Kekayaan Bumdesma</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. Laba berjalan</td>
                        <td align="right">ds</td>
                    </tr>
                    <tr>
                        <td>b. Risiko kolektibilitas</td>
                        <td align="right">ds</td>
                    </tr>
                    <tr>
                        <td>c. CKP yang dimiliki</td>
                        <td align="right">ds</td>
                    </tr>
                    <tr>
                        <td>d. Aset produktif</td>
                        <td align="right">ds</td>
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
            <td rowspan="2"></td>
            <td rowspan="2"></td>
        </tr>
        <tr>
            <td height="8">Persentase (a-(b-c)/dx100%)</td>
        </tr>

        <tr>
            <td align="center" rowspan="2">4</td>
            <td rowspan="2">Rasio Beban Operasi</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. Akumulasi beban</td>
                        <td align="right">ds</td>
                    </tr>
                    <tr>
                        <td>b. Akumulasi pendapatan</td>
                        <td align="right">ds</td>
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
            <td rowspan="2"></td>
            <td rowspan="2"></td>
        </tr>
        <tr>
            <td height="8">Persentase (a/bx100%)</td>
        </tr>

        <tr>
            <td align="center" rowspan="2">5</td>
            <td rowspan="2">Rasio Saldo Piutang Terhadap Kekayaan Bumdesma Non Investasi</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. Saldo piutang usaha</td>
                        <td align="right">ds</td>
                    </tr>
                    <tr>
                        <td>b. Aset produktif non investasi</td>
                        <td align="right">ds</td>
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
            <td rowspan="2"></td>
            <td rowspan="2"></td>
        </tr>
        <tr>
            <td height="8">Persentase (a/bx100%)</td>
        </tr>

        <tr>
            <td align="center" rowspan="2">6</td>
            <td rowspan="2">Rasio Kekayaan Bersih Budesma</td>
            <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                    <tr>
                        <td>a. Aset produktif non investasi </td>
                        <td align="right">ds</td>
                    </tr>
                    <tr>
                        <td>b. Risiko kolektibilitas </td>
                        <td align="right">ds</td>
                    </tr>
                    <tr>
                        <td>c. Modal disetor bumdesma </td>
                        <td align="right">ds</td>
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
            <td rowspan="2"></td>
            <td rowspan="2"></td>
        </tr>
        <tr>
            <td height="8">Persentase ((a-b)/cx100%)</td>
        </tr>

        <tr>
            <td colspan="3">
                Komulatif skor dalam parameter Bumdesma dengan usaha utama DBM
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
@endsection
