@php
    use App\Utils\Tanggal;
    $total_saldo = 0;

    if ($rek->jenis_mutasi == 'debet') {
        $saldo_awal_tahun = $saldo['debit'] - $saldo['kredit'];
        $saldo_awal_bulan = $d_bulan_lalu - $k_bulan_lalu;
        $total_saldo = $saldo_awal_tahun + $saldo_awal_bulan;
    } else {
        $saldo_awal_tahun = $saldo['kredit'] - $saldo['debit'];
        $saldo_awal_bulan = $k_bulan_lalu - $d_bulan_lalu;
        $total_saldo = $saldo_awal_tahun + $saldo_awal_bulan;
    }

    $total_debit = 0;
    $total_kredit = 0;
@endphp

<table border="0" width="100%" cellspacing="0" cellpadding="0" class="table table-striped midle">
    <thead class="bg-dark text-white">
        <tr>
            <td height="40" align="center" width="40">No</td>
            <td align="center" width="100">Tanggal</td>
            <td align="center" width="100">Kode Akun</td>
            <td align="center">Keterangan</td>
            <td align="center" width="70">Kode Trx.</td>
            <td align="center" width="140">Debit</td>
            <td align="center" width="140">Kredit</td>
            <td align="center" width="150">Saldo</td>
            <td align="center" width="40">Ins</td>
            <td align="center" width="170">&nbsp;</td>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td align="center"></td>
            <td align="center">{{ Tanggal::tglIndo($tahun . '-01-01') }}</td>
            <td align="center"></td>
            <td>Komulatif Transaksi Awal Tahun {{ $tahun }}</td>
            <td>&nbsp;</td>
            <td align="right">{{ number_format($saldo['debit']) }}</td>
            <td align="right">{{ number_format($saldo['kredit']) }}</td>
            <td align="right">{{ number_format($saldo_awal_tahun) }}</td>
            <td align="center"></td>
            <td align="center"></td>
        </tr>
        <tr>
            <td align="center"></td>
            <td align="center">{{ Tanggal::tglIndo($tahun . '-' . $bulan . '-01') }}</td>
            <td align="center"></td>
            <td>Komulatif Transaksi s/d Bulan Lalu</td>
            <td>&nbsp;</td>
            <td align="right">{{ number_format($d_bulan_lalu) }}</td>
            <td align="right">{{ number_format($k_bulan_lalu) }}</td>
            <td align="right">{{ number_format($total_saldo) }}</td>
            <td align="center"></td>
            <td align="center"></td>
        </tr>

        @foreach ($transaksi as $trx)
            @php
                if ($trx->rekening_debit == $rek->kode_akun) {
                    $ref = $trx->rekening_kredit;
                    $debit = $trx->jumlah;
                    $kredit = 0;
                } else {
                    $ref = $trx->rekening_debit;
                    $debit = 0;
                    $kredit = $trx->jumlah;
                }

                if ($rek->jenis_mutasi == 'debet') {
                    $_saldo = $debit - $kredit;
                } else {
                    $_saldo = $kredit - $debit;
                }

                $total_saldo += $_saldo;
                $total_debit += $debit;
                $total_kredit += $kredit;

                $kuitansi = false;
                $files = 'bm';
                if ($keuangan->startWith($trx->rekening_debit, '1.1.01') && !$keuangan->startWith($trx->rekening_kredit, '1.1.01')) {
                    $files = 'bkm';
                    $kuitansi = true;
                }
                if (!$keuangan->startWith($trx->rekening_debit, '1.1.01') && $keuangan->startWith($trx->rekening_kredit, '1.1.01')) {
                    $files = 'bkk';
                    $kuitansi = true;
                }
                if ($keuangan->startWith($trx->rekening_debit, '1.1.01') && $keuangan->startWith($trx->rekening_kredit, '1.1.01')) {
                    $files = 'bm';
                    $kuitansi = false;
                }
                if ($keuangan->startWith($trx->rekening_debit, '1.1.02') && !($keuangan->startWith($trx->rekening_kredit, '1.1.01') || $keuangan->startWith($trx->rekening_kredit, '1.1.02'))) {
                    $files = 'bkm';
                    $kuitansi = true;
                }
                if ($keuangan->startWith($trx->rekening_debit, '1.1.02') && $keuangan->startWith($trx->rekening_kredit, '1.1.02')) {
                    $files = 'bm';
                    $kuitansi = false;
                }
                if ($keuangan->startWith($trx->rekening_debit, '1.1.02') && $keuangan->startWith($trx->rekening_kredit, '1.1.01')) {
                    $files = 'bm';
                    $kuitansi = false;
                }
                if ($keuangan->startWith($trx->rekening_debit, '1.1.01') && $keuangan->startWith($trx->rekening_kredit, '1.1.02')) {
                    $files = 'bm';
                    $kuitansi = false;
                }
                if ($keuangan->startWith($trx->rekening_debit, '5.') && !($keuangan->startWith($trx->rekening_kredit, '1.1.01') || $keuangan->startWith($trx->rekening_kredit, '1.1.02'))) {
                    $files = 'bm';
                    $kuitansi = false;
                }
                if (!($keuangan->startWith($trx->rekening_debit, '1.1.01') || $keuangan->startWith($trx->rekening_debit, '1.1.02')) && $keuangan->startWith($trx->rekening_kredit, '1.1.02')) {
                    $files = 'bm';
                    $kuitansi = false;
                }
                if (!($keuangan->startWith($trx->rekening_debit, '1.1.01') || $keuangan->startWith($trx->rekening_debit, '1.1.02')) && $keuangan->startWith($trx->rekening_kredit, '4.')) {
                    $files = 'bm';
                    $kuitansi = false;
                }

                $ins = '';
                if (isset($trx->user->ins)) {
                    $ins = $trx->user->ins;
                }
            @endphp

            <tr>
                <td align="center">{{ $loop->iteration }}.</td>
                <td align="center">{{ Tanggal::tglIndo($trx->tgl_transaksi) }}</td>
                <td align="center">{{ $ref }}</td>
                <td>{{ $trx->keterangan_transaksi }}</td>
                <td align="center">{{ $trx->idt }}</td>
                <td align="right">{{ number_format($debit) }}</td>
                <td align="right">{{ number_format($kredit) }}</td>
                <td align="right">{{ number_format($total_saldo) }}</td>
                <td align="center">{{ $ins }}</td>
                <td align="right">
                    <div class="btn-group">
                        @if ($kuitansi)
                            <button type="button" data-action="/transaksi/dokumen/kuitansi/{{ $trx->idt }}"
                                class="btn btn-linkedin btn-icon-only btn-tooltip btn-link" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Kuitansi" data-container="body" data-animation="true">
                                <span class="btn-inner--icon"><i class="fas fa-file"></i></span>
                            </button>
                        @endif
                        @if ($trx->idtp > 0 && $trx->id_pinj != 0)
                            <button type="button"
                                data-action="/transaksi/dokumen/{{ $files }}_angsuran/{{ $trx->idt }}"
                                class="btn btn-instagram btn-icon-only btn-tooltip btn-link" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="{{ $files }}" data-container="body"
                                data-animation="true">
                                <span class="btn-inner--icon"><i class="fas fa-file-circle-exclamation"></i></span>
                            </button>
                        @else
                            <button type="button"
                                data-action="/transaksi/dokumen/{{ $files }}/{{ $trx->idt }}"
                                class="btn btn-instagram btn-icon-only btn-tooltip btn-link" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="{{ $files }}" data-container="body"
                                data-animation="true">
                                <span class="btn-inner--icon"><i class="fas fa-file-circle-exclamation"></i></span>
                            </button>
                        @endif

                        @if ($is_dir)
                            <button type="button" data-idt="{{ $trx->idt }}"
                                class="btn btn-tumblr btn-icon-only btn-tooltip btn-reversal" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Reversal" data-container="body" data-animation="true">
                                <span class="btn-inner--icon"><i class="fas fa-code-pull-request"></i></span>
                            </button>
                            @if (!$is_ben)
                                <button type="button" data-idt="{{ $trx->idt }}"
                                    class="btn btn-github btn-icon-only btn-tooltip btn-delete" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Hapus" data-container="body" data-animation="true">
                                    <span class="btn-inner--icon"><i class="fas fa-trash-can"></i></span>
                                </button>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach

        <tr>
            <td colspan="5">
                <b>Total Transaksi {{ ucwords($sub_judul) }}</b>
            </td>
            <td align="right">
                <b>{{ number_format($total_debit) }}</b>
            </td>
            <td align="right">
                <b>{{ number_format($total_kredit) }}</b>
            </td>
            <td colspan="3" rowspan="3" align="center" style="vertical-align: middle">
                <b>{{ number_format($total_saldo) }}</b>
            </td>
        </tr>

        <tr>
            <td colspan="5">
                <b>Total Transaksi sampai dengan {{ ucwords($sub_judul) }}</b>
            </td>
            <td align="right">
                <b>{{ number_format($d_bulan_lalu + $total_debit) }}</b>
            </td>
            <td align="right">
                <b>{{ number_format($k_bulan_lalu + $total_kredit) }}</b>
            </td>
        </tr>

        <tr>
            <td colspan="5">
                <b>Total Transaksi Komulatif sampai dengan Tahun {{ $tahun }}</b>
            </td>
            <td align="right">
                <b>{{ number_format($saldo['debit'] + $d_bulan_lalu + $total_debit) }}</b>
            </td>
            <td align="right">
                <b>{{ number_format($saldo['kredit'] + $k_bulan_lalu + $total_kredit) }}</b>
            </td>
        </tr>
    </tbody>

</table>

<script>
    $(document).ready(function() {
        initializeBootstrapTooltip()
    })
</script>
