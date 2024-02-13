@extends('layouts.base')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form action="/transaksi/anggaran" method="post" id="formAnggaran">
                @csrf

                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group input-group-static my-3">
                            <label for="tahun">Tahun</label>
                            <input autocomplete="off" type="number" name="tahun" id="tahun" class="form-control"
                                value="{{ date('Y') }}">
                            <small class="text-danger" id="msg_tahun"></small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group input-group-static my-3">
                            <label for="bulan">Bulan</label>
                            <input autocomplete="off" type="number" name="bulan" id="bulan" class="form-control"
                                value="{{ date('m') }}" max="12" min="01">
                            <small class="text-danger" id="msg_bulan"></small>
                        </div>
                    </div>
                </div>
            </form>

            <div class="d-flex justify-content-end">
                <button type="button" id="anggaran" class="btn btn-sm btn-github mb-0">Tentukan Rencana Anggaran</button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div id="formEbudgeting"></div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '#anggaran', function(e) {
            e.preventDefault()

            var form = $('#formAnggaran')
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        $('#formEbudgeting').html(result.view)
                    }
                }
            })
        })

        $(document).on('click', '#SimpanAnggaran', function(e) {
            e.preventDefault()


            var form = $('#formRencanaAnggaran')
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        Toastr('success', result.msg)

                        location.reload();
                    }
                }
            })
        })

        $("#nominal").maskMoney({
            allowNegative: true
        });
    </script>
@endsection
