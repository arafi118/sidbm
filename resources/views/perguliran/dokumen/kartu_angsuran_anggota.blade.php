@php
    use App\Utils\Tanggal;

    $rowspan = 19;
    if ($pinkel->real_count > 16) {
        $rowspan = $pinkel->real_count + 3;
    }

    $no = 0;
    $ketua = $pinkel->kelompok->ketua;
    $sekretaris = $pinkel->kelompok->sekretaris;
    $bendahara = $pinkel->kelompok->bendahara;
    if ($pinkel->struktur_kelompok) {
        $struktur_kelompok = json_decode($pinkel->struktur_kelompok, true);
        $ketua = isset($struktur_kelompok['ketua']) ? $struktur_kelompok['ketua'] : '';
        $sekretaris = isset($struktur_kelompok['sekretaris']) ? $struktur_kelompok['sekretaris'] : '';
        $bendahara = isset($struktur_kelompok['bendahara']) ? $struktur_kelompok['bendahara'] : '';
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ ucwords(str_replace('_', ' ', $laporan)) }}</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        html {
            /* margin-left: 90px; */
            /* margin-right: 0px; */
            margin-bottom: 100px;
        }

        ul,
        ol {
            margin-left: -10px;
            page-break-inside: auto !important;
        }

        header {
            position: fixed;
            top: -10px;
            left: 0px;
            right: 0px;
        }

        table tr th,
        table tr td {
            padding: 2px 4px;
        }

        table tr th {
            font-size: 12px;
        }

        .break {
            page-break-after: always;
        }

        li {
            text-align: justify;
        }

        .l {
            border-left: 1px solid #000;
        }

        .t {
            border-top: 1px solid #000;
        }

        .r {
            border-right: 1px solid #000;
        }

        .b {
            border-bottom: 1px solid #000;
        }
    </style>
</head>

<body onload="window.print()">
    @foreach ($pinkel->pinjaman_anggota as $pinj)
        @php
            if ($nia != null) {
                if ($nia != $pinj->nia) {
                    continue;
                }
            }

            $keyId = 'id' . $pinj->id;
            $rencana_angsuran = $generate->rencana_angsuran;
            $rencana_angsuran_anggota = $generate->rencana_angsuran_anggota->$keyId;

            $rencana_pokok = $rencana_angsuran_anggota->pokok;
            $rencana_jasa = $rencana_angsuran_anggota->jasa;

            $pros_jasa_anggota = $pinj->pros_jasa;
            if (Session::get('lokasi') == '522') {
                $pros_jasa_kelompok = $pinkel->pros_jasa / $pinkel->jangka + 0.2;
                if ($pinkel->pinj >= '3') {
                    $pros_jasa_anggota = $pros_jasa_kelompok * $pinkel->jangka;
                }
            }

            $jatuh_tempo = [];
            $no++;
        @endphp

        @if ($no > 1)
            <div class="break"></div>
        @endif
        <main style="position: relative; font-size: 12px;">
            <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
                <tr>
                    <td rowspan="7" align="center" width="400">
                        <div style="font-size: 14px; font-weight: bold;">
                            {{ $kec->nama_lembaga_sort }} {{ $kec->nama_kec }}
                        </div>
                        <div>
                            {{ $kec->alamat_kec }}
                        </div>
                        <div>
                            Telp. {{ $kec->telpon_kec }}
                        </div>
                        <div style="margin-top: 8px;">
                            <img width="150" src="data:image/png;base64,{{ $barcode }}"
                                alt="{{ $pinkel->kelompok->kd_kelompok }}">
                        </div>
                        <div style="font-size: 14px;">{{ $pinkel->kelompok->kd_kelompok }}</div>
                    </td>
                    <td width="150">Jenis Piutang</td>
                    <td width="5" align="center">:</td>
                    <td width="200">{{ $pinkel->jpp->nama_jpp }}</td>
                    <td width="150">Loan Id.</td>
                    <td width="5" align="center">:</td>
                    <td width="200">{{ $pinkel->id }}</td>
                </tr>
                <tr>
                    <td>Nama Kelompok</td>
                    <td align="center">:</td>
                    <td style="font-weight: bold;" colspan="4">{{ $pinkel->kelompok->nama_kelompok }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td align="center">:</td>
                    <td colspan="4">{{ $pinkel->kelompok->alamat_kelompok }}</td>
                </tr>
                <tr>
                    <td>Telpon/SMS</td>
                    <td align="center">:</td>
                    <td>{{ $pinj->anggota->hp }}</td>
                    <td>Pemanfaat</td>
                    <td align="center">:</td>
                    <td style="font-weight: bold;">{{ $pinj->anggota->namadepan }}</td>
                </tr>
                <tr>
                    <td>Tgl Cair</td>
                    <td align="center">:</td>
                    <td>{{ Tanggal::tglLatin($pinkel->tgl_cair) }}</td>
                    <td>Jangka</td>
                    <td align="center">:</td>
                    <td>{{ $pinkel->jangka }} {{ $pinkel->sis_pokok->id == '12' ? 'Minggu' : 'Bulan' }}</td>
                </tr>
                <tr>
                    <td>Alokasi</td>
                    <td align="center">:</td>
                    <td>{{ number_format($pinj->alokasi) }}</td>
                    <td>Jasa</td>
                    <td align="center">:</td>
                    <td>{{ number_format($pros_jasa_anggota / $pinj->jangka, 2) . '%' }}</td>
                </tr>
                <tr>
                    <td>Angsuran</td>
                    <td align="center">:</td>

                    <td style="display: inline-block;">
                        {{ number_format($rencana_angsuran_anggota->jumlah_angsuran) }} /
                        {{ $pinkel->sis_pokok->nama_sistem }}
                    </td>
                    <td colspan="3">
                        Angsuran pada tanggal {{ explode('-', $pinkel->target->jatuh_tempo)[2] }}
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="b t" style="font-weight: bold; font-size: 24px;" align="center">
                        KARTU ANGSURAN ANGGOTA
                    </td>
                </tr>
            </table>

            @php
                $baris_angsuran = ceil(count($rencana_angsuran) / 2);
            @endphp

            <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
                <tr>
                    <td width="5%">&nbsp;</td>
                    <td colspan="9" style="font-weight: bold;" height="30">TABEL KEWAJIBAN PEMBAYARAN ANGSURAN
                    </td>
                    <td width="5%">&nbsp;</td>
                </tr>

                <tr style="font-weight: bold;">
                    <th rowspan="{{ $baris_angsuran + 1 }}">&nbsp;</th>
                    <th height="30" class="l t b" align="center">Ke</th>
                    <th class="l t b" align="center">Tanggal</th>
                    <th class="l t b" align="center">Pokok</th>
                    <th class="l t b r" align="center">Jasa</th>

                    <th>&nbsp;</th>

                    <th class="l t b" align="center">Ke</th>
                    <th class="l t b" align="center">Tanggal</th>
                    <th class="l t b" align="center">Pokok</th>
                    <th class="l t b r" align="center">Jasa</th>
                    <th rowspan="{{ $baris_angsuran + 1 }}">&nbsp;</th>
                </tr>

                @php
                    $target_pokok1 = 0;
                    $target_pokok2 = 0;
                    $target_jasa1 = 0;
                    $target_jasa2 = 0;
                @endphp
                @for ($j = 1; $j <= $baris_angsuran; $j++)
                    @php
                        $i = $j - 1;
                        $ra = $rencana_angsuran[$i];
                        $angsuran_ke = $ra->angsuran_ke;
                        $wajib_pokok = $rencana_pokok->$angsuran_ke;
                        $wajib_jasa = $rencana_jasa->$angsuran_ke;

                        $target_pokok1 += $wajib_pokok;
                        $target_jasa1 += $wajib_jasa;
                        $jatuh_tempo[strtotime($ra->jatuh_tempo)] = [
                            'pokok' => $target_pokok1,
                            'jasa' => $target_jasa1,
                            'jatuh_tempo' => $ra->jatuh_tempo,
                        ];
                    @endphp
                    <tr>
                        <td class="l {{ $j == $baris_angsuran ? 'b' : '' }}" align="center">
                            {{ $ra->angsuran_ke }}
                        </td>
                        <td class="l {{ $j == $baris_angsuran ? 'b' : '' }}" align="center">
                            {{ Tanggal::tglIndo($ra->jatuh_tempo) }}
                        </td>
                        <td class="l {{ $j == $baris_angsuran ? 'b' : '' }}" align="right">
                            {{ number_format($wajib_pokok) }}
                        </td>
                        <td class="l {{ $j == $baris_angsuran ? 'b' : '' }} r" align="right">
                            {{ number_format($wajib_jasa) }}
                        </td>

                        <td>&nbsp;</td>

                        @if (isset($rencana_angsuran[$i + $baris_angsuran]))
                            @php
                                $ra = $rencana_angsuran[$i + $baris_angsuran];
                                $angsuran_ke = $ra->angsuran_ke;
                                $wajib_pokok = $rencana_pokok->$angsuran_ke;
                                $wajib_jasa = $rencana_jasa->$angsuran_ke;

                                $target_pokok2 += $wajib_pokok;
                                $target_jasa2 += $wajib_jasa;
                                $jatuh_tempo[strtotime($ra->jatuh_tempo)] = [
                                    'pokok' => $target_pokok2,
                                    'jasa' => $target_jasa2,
                                    'jatuh_tempo' => $ra->jatuh_tempo,
                                ];
                            @endphp
                            <td class="l {{ $j == $baris_angsuran ? 'b' : '' }}" align="center">
                                {{ $ra->angsuran_ke }}
                            </td>
                            <td class="l {{ $j == $baris_angsuran ? 'b' : '' }}" align="center">
                                {{ Tanggal::tglIndo($ra->jatuh_tempo) }}
                            </td>
                            <td class="l {{ $j == $baris_angsuran ? 'b' : '' }}" align="right">
                                {{ number_format($wajib_pokok) }}
                            </td>
                            <td class="l {{ $j == $baris_angsuran ? 'b' : '' }} r" align="right">
                                {{ number_format($wajib_jasa) }}
                            </td>
                        @else
                            <td class="l {{ $j == $baris_angsuran ? 'b' : '' }}" align="center">

                            </td>
                            <td class="l {{ $j == $baris_angsuran ? 'b' : '' }}" align="center">

                            </td>
                            <td class="l {{ $j == $baris_angsuran ? 'b' : '' }}" align="right">

                            </td>
                            <td class="l {{ $j == $baris_angsuran ? 'b' : '' }} r" align="right">

                            </td>
                        @endif
                    </tr>
                @endfor

            </table>

            <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
                <tr>
                    <td width="5%" rowspan="{{ $rowspan }}">&nbsp;</td>
                    <td width="90%" colspan="9" style="font-weight: bold;" height="30">REALISASI PEMBAYARAN
                        ANGSURAN</td>
                    <td width="5%" rowspan="{{ $rowspan }}">&nbsp;</td>
                </tr>
                <tr>
                    <th width="3%" class="l t b" rowspan="2">No</th>
                    <th width="10%" class="l t b" rowspan="2">Tanggal</th>
                    <th width="22%" class="l t" colspan="2">Pokok</th>
                    <th width="22%" class="l t" colspan="2">Jasa</th>
                    <th width="22%"class="l t" colspan="2">Saldo Piutang</th>
                    <th width="11%" class="l t r b" rowspan="2">Sign</th>
                </tr>
                <tr>
                    <th width="12%" class="l b t">Dibayar</th>
                    <th width="10%" class="l b t">Tunggakan</th>
                    <th width="12%" class="l b t">Dibayar</th>
                    <th width="10%" class="l b t">Tunggakan</th>
                    <th width="11%" class="l b t">Pokok</th>
                    <th width="11%" class="l b t">Jasa</th>
                </tr>

                @php
                    $jumlah = 0;

                    $kom_pokok = json_decode($pinj->kom_pokok, true);
                    $kom_jasa = json_decode($pinj->kom_jasa, true);

                    if (!is_array($kom_pokok)) {
                        $kom_pokok = [];
                    }

                    if (!is_array($kom_jasa)) {
                        $kom_jasa = [];
                    }

                    $alokasi_pokok = $pinj->alokasi;
                    $alokasi_jasa = ($pinkel->pros_jasa / 100) * $alokasi_pokok;

                    $sum_pokok = 0;
                    $sum_jasa = 0;

                    $target_pokok = 0;
                    $target_jasa = 0;
                @endphp

                @if ($angsuran)
                    @foreach ($pinkel->real as $real)
                        @php
                            $jumlah++;
                            $nomor = $loop->iteration;

                            $b = $nomor + 3 == $rowspan ? 'b' : '';

                            $tgl_transaksi = $real->tgl_transaksi;
                            $waktu_transaksi = strtotime($real->tgl_transaksi);
                            ksort($jatuh_tempo);
                            foreach ($jatuh_tempo as $key => $jt) {
                                if ($key <= $waktu_transaksi) {
                                    $target_pokok = $jt['pokok'];
                                    $target_jasa = $jt['jasa'];
                                }
                            }

                            $pokok = 0;
                            $jasa = 0;
                            $tunggakan_pokok = 0;
                            $tunggakan_jasa = 0;

                            if (!array_key_exists($real->id, $kom_pokok)) {
                                $pros_pokok_anggota = ($pinj->alokasi / $pinkel->alokasi) * 100;
                                $pokok = ($pros_pokok_anggota / 100) * $real->realisasi_pokok;
                            } else {
                                $pokok = $kom_pokok[$real->id];
                            }

                            if (!array_key_exists($real->id, $kom_jasa)) {
                                $jasa_pinjaman = ($pinkel->pros_jasa / 100) * $pinkel->alokasi;

                                $pros_jasa_anggota =
                                    ((($pinj->pros_jasa / 100) * $pinj->alokasi) / $jasa_pinjaman) * 100;
                                $jasa = ($pros_jasa_anggota / 100) * $real->realisasi_jasa;
                            } else {
                                $jasa = $kom_jasa[$real->id];
                            }

                            $sum_pokok += $pokok;
                            $sum_jasa += $jasa;

                            $tunggakan_pokok = $target_pokok - $sum_pokok;
                            if ($tunggakan_pokok < 0) {
                                $tunggakan_pokok = 0;
                            }

                            $tunggakan_jasa = $target_jasa - $sum_jasa;
                            if ($tunggakan_jasa < 0) {
                                $tunggakan_jasa = 0;
                            }

                            $saldo_pokok = $alokasi_pokok - $sum_pokok;
                            if ($saldo_pokok < 0) {
                                $saldo_pokok = 0;
                            }
                            $saldo_jasa = $alokasi_jasa - $sum_jasa;
                            if ($saldo_jasa < 0) {
                                $saldo_jasa = 0;
                            }

                            $sign = 'TF';
                            if ($real->transaksi->rekening_debit == '1.1.01.01') {
                                $sign = 'TN';
                            }
                        @endphp
                        <tr>
                            <td class="l {{ $b }}" align="center">{{ $nomor }}</td>
                            <td class="l {{ $b }}" align="center">
                                {{ Tanggal::tglIndo($real->tgl_transaksi) }}
                            </td>
                            <td class="l {{ $b }}" align="right">{{ number_format($pokok) }}
                            </td>
                            <td class="l {{ $b }}" align="right">
                                {{ number_format($tunggakan_pokok) }}
                            </td>
                            <td class="l {{ $b }}" align="right">{{ number_format($jasa) }}
                            </td>
                            <td class="l {{ $b }}" align="right">
                                {{ number_format($tunggakan_jasa) }}
                            </td>
                            <td class="l {{ $b }}" align="right">{{ number_format($saldo_pokok) }}
                            </td>
                            <td class="l {{ $b }}" align="right">{{ number_format($saldo_jasa) }}</td>
                            <td class="l {{ $b }} r" align="center">
                                {{ $sign }}-{{ $real->id }}
                            </td>
                        </tr>
                    @endforeach
                @endif

                @if ($jumlah < 16)
                    @for ($i = 1; $i <= 16 - $jumlah; $i++)
                        <tr>
                            <td class="l {{ $i == 16 - $jumlah ? 'b' : '' }}" align="center">&nbsp;</td>
                            <td class="l {{ $i == 16 - $jumlah ? 'b' : '' }}" align="center">&nbsp;</td>
                            <td class="l {{ $i == 16 - $jumlah ? 'b' : '' }}" align="right">&nbsp;</td>
                            <td class="l {{ $i == 16 - $jumlah ? 'b' : '' }}" align="right">&nbsp;</td>
                            <td class="l {{ $i == 16 - $jumlah ? 'b' : '' }}" align="right">&nbsp;</td>
                            <td class="l {{ $i == 16 - $jumlah ? 'b' : '' }}" align="right">&nbsp;</td>
                            <td class="l {{ $i == 16 - $jumlah ? 'b' : '' }}" align="right">&nbsp;</td>
                            <td class="l {{ $i == 16 - $jumlah ? 'b' : '' }}" align="right">&nbsp;</td>
                            <td class="l {{ $i == 16 - $jumlah ? 'b' : '' }} r" align="center">&nbsp;</td>
                        </tr>
                    @endfor
                @endif
            </table>

            <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
                <tr>
                    <td width="5%" rowspan="5">&nbsp;</td>
                    <td colspan="3" style="font-weight: bold;" height="30">&nbsp;</td>
                    <td width="5%" rowspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td width="350" rowspan="3">
                        <div>Lembar 1 : Untuk Kelompok</div>
                        <div>Lembar 2 : Arsip Lembaga</div>
                    </td>
                    <td style="font-weight: bold; font-size: 12px;" width="350" align="center">Ketua Kelompok</td>
                    <td style="font-weight: bold; font-size: 12px;" width="350" align="center">
                        <div>Anggota Pemanfaat</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" height="50"></td>
                </tr>
                <tr style="font-weight: bold; font-size: 12px; text-transform: uppercase;">
                    <td width="350" align="center">
                        {{ $ketua }}
                    </td>
                    <td width="350" align="center">
                        <div>{{ $pinj->anggota->namadepan }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;" height="10">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5">
                        <ol>
                            <b>Perhatian:</b>
                            <li>Bayarlah angsuran tepat waktu sesuai dengan jadwal diatas</li>
                            <li>Untuk memudahkan pelayanan, bawalah kartu ini dan slip pembayaran terakhir setiap
                                melakukan
                                angsuran</li>
                            <li>Jagalah keutuhan kartu dan tidak melipatnya, jika hilang segera lapor
                                {{ $kec->nama_lembaga_sort }}</li>
                            <li>Jika lembar ini tidak mencukupi, cetak pada lembar baliknya dengan dibubuhi stempel
                                {{ $kec->nama_lembaga_sort }}</li>
                        </ol>
                    </td>
                </tr>
            </table>
        </main>
    @endforeach
</body>

</html>
