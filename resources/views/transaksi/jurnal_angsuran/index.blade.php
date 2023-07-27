@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-md-8 mb-3">
        <div class="card mb-3">
            <div class="card-body py-2">
                <form action="/transaksi/angsuran" method="post" id="FormAngsuran">
                    @csrf

                    <input type="hidden" name="id" id="id" value="{{ Request::get("pinkel") ?: 0 }}">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group input-group-static my-3">
                                <label for="tgl_transaksi">Tgl Transaksi</label>
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
                </form>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-info btn-sm me-3">Detail Kelompok</button>
                    <button type="button" id="SimpanAngsuran" class="btn btn-primary btn-sm">Posting</button>
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
                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#Jasa" role="tab" aria-controls="Jasa"
                        aria-selected="false">
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
                        <canvas id="chartJ"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })

    var pokok, jasa = 0;

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

    var id_pinkel = '{{ Request::get("pinkel") ?: 0 }}'

    if (id_pinkel != 0) {
        var ch_pokok = document.getElementById('chartP').getContext("2d");
        var ch_jasa = document.getElementById('chartJ').getContext("2d");

        $.get('/transaksi/form_angsuran/' + id_pinkel, function (result) {
            $('#pokok').val(formatter.format(result.saldo_pokok))
            $('#jasa').val(formatter.format(result.saldo_jasa))
            $('#id').val(result.pinkel.id)

            makeChart('pokok', ch_pokok, result.sisa_pokok, result.sum_pokok)
            makeChart('jasa', ch_jasa, result.sisa_jasa, result.sum_jasa)

            $('#pokok,#jasa,#denda').trigger('change')
        })
    }

    $(document).on('change', '#pokok,#jasa,#denda', function (e) {
        var pokok = $('#pokok').val()
        var jasa = $('#jasa').val()
        var denda = $('#denda').val()

        pokok = parseInt(pokok.split(',').join('').split('.00').join(''))
        if (!pokok) pokok = 0;

        jasa = parseInt(jasa.split(',').join('').split('.00').join(''))
        if (!jasa) jasa = 0;

        denda = parseInt(denda.split(',').join('').split('.00').join(''))
        if (!denda) {
            $('#denda').val(formatter.format('0'))
            denda = 0;
        }

        var total = pokok + jasa + denda
        $('#total').val(formatter.format(total))
    })

    $(document).on('click', '#SimpanAngsuran', function (e) {
        e.preventDefault()

        var form = $('#FormAngsuran')
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function (result) {
                //
            }
        })
    })

</script>
@endsection
