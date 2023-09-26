<div class="row">
    <div class="col-md-8 mb-3">
        <div class="card">
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
                            <input autocomplete="off" type="text" name="pokok" id="pokok" class="form-control">
                            <small class="text-danger" id="msg_pokok"></small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group input-group-static my-3">
                            <label for="jasa">Jasa</label>
                            <input autocomplete="off" type="text" name="jasa" id="jasa" class="form-control">
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
                    <button type="button" class="btn btn-primary btn-sm">Posting</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body pb-2">
                <canvas id="chart"></canvas>

                <div class="row mt-2">
                    <div class="col-12">
                        <div class="d-grid">
                            <button class="btn btn-success btn-sm">Kartu</button>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-grid">
                            <button class="btn btn-danger btn-sm">Detail</button>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-grid">
                            <button class="btn btn-info btn-sm">LPP per bulan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

    var ctx = document.getElementById('chart');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                'Sisa Saldo',
                'Total Pengembalian'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [0, 0],
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
