@php
    use App\Utils\Tanggal;

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
@endphp

<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-body pt-4 p-3">
                <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                    {{ $trx->keterangan_transaksi }}
                </h6>
                <ul class="list-group">
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <button
                                class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 me-3 p-3 btn-sm d-flex align-items-center justify-content-center"><i
                                    class="material-icons text-lg">expand_more</i></button>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">
                                    {{ $trx->rek_kredit->kode_akun }} - {{ $trx->rek_kredit->nama_akun }}
                                </h6>
                                <span class="text-xs">{{ Tanggal::tglLatin($trx->tgl_transaksi) }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-danger text-gradient text-sm font-weight-bold">
                            - Rp. {{ number_format($trx->jumlah, 2) }}
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <button
                                class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 p-3 btn-sm d-flex align-items-center justify-content-center"><i
                                    class="material-icons text-lg">expand_less</i></button>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">
                                    {{ $trx->rek_debit->kode_akun }} - {{ $trx->rek_debit->nama_akun }}
                                </h6>
                                <span class="text-xs">{{ Tanggal::tglLatin($trx->tgl_transaksi) }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-success text-gradient text-sm font-weight-bold">
                            + Rp. {{ number_format($trx->jumlah, 2) }}
                        </div>
                    </li>
                </ul>

                <div class="d-flex justify-content-end" style="gap: 1rem">
                    @if ($kuitansi)
                        <button type="button" data-action="/transaksi/dokumen/kuitansi/{{ $trx->idt }}"
                            class="btn btn-sm btn-linkedin btn-link">Kuitansi</button>
                    @endif
                    <button type="button" data-action="/transaksi/dokumen/{{ $files }}/{{ $trx->idt }}"
                        class="btn btn-sm btn-instagram btn-link">{{ $files }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
