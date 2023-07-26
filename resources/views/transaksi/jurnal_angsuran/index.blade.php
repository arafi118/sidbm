@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-md-5 mb-3">
        <div class="card">
            <div class="card-body py-2">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group input-group-static my-3">
                            <label for="tgl_transaksi">Tgl Transaksi</label>
                            <input autocomplete="off" type="text" name="tgl_transaksi" id="tgl_transaksi"
                                class="form-control date">
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
            </div>
        </div>
    </div>
    <div class="col-md-7 mb-3">
        <div class="card">
            <div class="card-body pb-2"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(".date").flatpickr({
        dateFormat: "d/m/Y"
    })

</script>
@endsection
