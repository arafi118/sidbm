@php
    $saldo_pokok = $ra->target_pokok - $real->sum_pokok > 0 ? $ra->target_pokok - $real->sum_pokok : 0;
    $saldo_jasa = $ra->target_jasa - $real->sum_jasa > 0 ? $ra->target_jasa - $real->sum_jasa : 0;
@endphp

<div class="row">
    <div class="col-md-8 mb-3">
        <div class="card mb-3">
            <div class="card-body py-2">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group input-group-static my-3">
                            <label for="tgl_transaksi">Tanggal Transaksi</label>
                            <input autocomplete="off" type="text" name="tgl_transaksi" id="tgl_transaksi"
                                class="form-control date" value="{{ date('d/m/Y') }}">
                            <small class="text-danger" id="msg_tgl_transaksi"></small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group input-group-static my-3">
                            <label for="pokok">Pokok</label>
                            <input autocomplete="off" type="text" name="pokok" id="pokok" class="form-control"
                                value="{{ number_format($saldo_pokok, 2) }}">
                            <small class="text-danger" id="msg_pokok"></small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group input-group-static my-3">
                            <label for="jasa">Jasa</label>
                            <input autocomplete="off" type="text" name="jasa" id="jasa" class="form-control"
                                value="{{ number_format($saldo_jasa, 2) }}">
                            <small class="text-danger" id="msg_jasa"></small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group input-group-static my-3">
                            <label for="denda">Denda</label>
                            <input autocomplete="off" type="text" name="denda" id="denda" class="form-control">
                            <small class="text-danger" id="msg_denda"></small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group input-group-static my-3">
                            <label for="total">Total Bayar</label>
                            <input autocomplete="off" readonly disabled type="text" name="total" id="total"
                                class="form-control">
                            <small class="text-danger" id="msg_total"></small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-info btn-sm me-3">Detail Kelompok</button>
                    <button type="button" class="btn btn-primary btn-sm">Posting</button>
                </div>
            </div>
        </div>

        <div class="card card-body p-2 pb-0 mb-3">
            <div class="row">
                <div class="col-4">
                    <div class="d-grid">
                        <button class="btn btn-success btn-sm mb-2">Kartu</button>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-grid">
                        <button class="btn btn-danger btn-sm mb-2">Detail</button>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-grid">
                        <button class="btn btn-info btn-sm mb-2">LPP per bulan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="nav-wrapper position-relative end-0">
            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#Pokok" role="tab"
                        aria-controls="Pokok" aria-selected="true">
                        Pokok
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#Jasa" role="tab"
                        aria-controls="Jasa" aria-selected="false">
                        Jasa
                    </a>
                </li>
            </ul>

            <div class="tab-content mt-2">
                <div class="tab-pane fade show active" id="Pokok" role="tabpanel" aria-labelledby="Pokok">
                    <div class="card card-body p-2">
                        <canvas id="chartP"></canvas>
                    </div>
                </div>
                <div class="tab-pane fade" id="Jasa" role="tabpanel" aria-labelledby="Jasa">
                    <div class="card card-body p-2">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $alokasi_jasa = $pinkel->alokasi * ($pinkel->pros_jasa / 100);
@endphp

<script>
    $("#pokok").maskMoney({
        allowNegative: true
    });

    $("#jasa").maskMoney({
        allowNegative: true
    });

    $("#denda").maskMoney({
        allowNegative: true
    });

    $("#total").maskMoney({
        allowNegative: true
    });


    $(".date").flatpickr({
        dateFormat: "d/m/Y"
    })

    var pokok = document.getElementById('chartP');

    new Chart(pokok, {
        type: 'doughnut',
        data: {
            labels: [
                'Sisa Saldo',
                'Total Pengembalian'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: ['{{ $pinkel->alokasi - $real->sum_pokok }}', '{{ $real->sum_pokok }}'],
                backgroundColor: [
                    '#e3316e',
                    '#3A416F'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
