@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-lg-9 mb-3">
        <div class="card">
            <div class="card-body py-2">
                <form action="/transaksi" method="post" id="FormTransaksi">
                    @csrf

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-group input-group-static my-3">
                                <label for="tgl_transaksi">Tgl Transaksi</label>
                                <input autocomplete="off" type="text" name="tgl_transaksi" id="tgl_transaksi"
                                    class="form-control date" value="{{ date('d/m/Y'); }}">
                                <small class="text-danger" id="msg_tgl_transaksi"></small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="my-2">
                                <label class="form-label" for="jenis_transaksi">Jenis Transaksi</label>
                                <select class="form-control" name="jenis_transaksi" id="jenis_transaksi">
                                    <option value="">-- Pilih Jenis Transaksi --</option>
                                    @foreach ($jenis_transaksi as $jt)
                                    <option value="{{ $jt->id }}">
                                        {{ $jt->nama_jt }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="text-danger" id="msg_jenis_transaksi"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="kd_rekening">
                        <div class="col-sm-6">
                            <div class="my-2">
                                <label class="form-label" for="sumber_dana">Sumber Dana</label>
                                <select class="form-control" name="sumber_dana" id="sumber_dana">
                                    <option value="">-- Sumber Dana --</option>
                                </select>
                                <small class="text-danger" id="msg_sumber_dana"></small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="my-2">
                                <label class="form-label" for="disimpan_ke">Disimpan Ke</label>
                                <select class="form-control" name="disimpan_ke" id="disimpan_ke">
                                    <option value="">-- Disimpan Ke --</option>
                                </select>
                                <small class="text-danger" id="msg_disimpan_ke"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="form_nominal">
                        <div class="col-sm-12">
                            <div class="input-group input-group-static my-3">
                                <label for="keterangan">Keterangan</label>
                                <input autocomplete="off" type="text" name="keterangan" id="keterangan"
                                    class="form-control">
                                <small class="text-danger" id="msg_keterangan"></small>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="input-group input-group-static my-3">
                                <label for="nominal">Nominal Rp.</label>
                                <input autocomplete="off" type="text" name="nominal" id="nominal" class="form-control">
                                <small class="text-danger" id="msg_nominal"></small>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="d-flex justify-content-end">
                    <button type="button" id="SimpanTransaksi" class="btn btn-sm btn-primary">Simpan Transaksi</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body p-3 pb-0">
                <div class="d-flex justify-content-between">
                    <div class="text-sm">Saldo:</div>
                    <div class="text-sm fw-bold">
                        Rp. <span id="saldo">0.00</span>
                    </div>
                </div>

                <hr class="horizontal dark">
                <div class="text-sm fw-bold text-center">Cetak Buku Bantu</div>
                <hr class="horizontal dark mb-0">

                <div class="row">
                    <div class="col-12">
                        <div class="my-2">
                            <label class="form-label" for="tahun">Tahunan</label>
                            <select class="form-control" name="tahun" id="tahun">
                                @php
                                $tgl_pakai = $kec->tgl_pakai;
                                $th_pakai = explode('-',$tgl_pakai)[0];
                                @endphp
                                @for ($i=$th_pakai; $i<=$th_pakai; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                            </select>
                            <small class="text-danger" id="msg_tahun"></small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="my-2">
                            <label class="form-label" for="bulan">Bulanan</label>
                            <select class="form-control" name="bulan" id="bulan">
                                <option value="">--</option>
                                <option value="01">01. JANUARI</option>
                                <option value="02">02. FEBRUARI</option>
                                <option value="03">03. MARET</option>
                                <option value="04">04. APRIL</option>
                                <option value="05">05. MEI</option>
                                <option value="06">06. JUNI</option>
                                <option value="07">07. JULI</option>
                                <option value="08">08. AGUSTUS</option>
                                <option value="09">09. SEPTEMBER</option>
                                <option value="10">10. OKTOBER</option>
                                <option value="11">11. NOVEMBER</option>
                                <option value="12">12. DESEMBER</option>
                            </select>
                            <small class="text-danger" id="msg_bulan"></small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="my-2">
                            <label class="form-label" for="tanggal">Tanggal</label>
                            <select class="form-control" name="tanggal" id="tanggal">
                                <option value="">--</option>
                                @for ($j=1; $j<=31; $j++) @php $no=str_pad($j, 2, "0" , STR_PAD_LEFT) @endphp <option
                                    value="{{ $no }}">{{ $no }}</option>
                                    @endfor
                            </select>
                            <small class="text-danger" id="msg_tanggal"></small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-success">
                        Detail Transaksi
                    </button>
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

    var jenis_transaksi = new Choices($('#jenis_transaksi')[0])
    new Choices($('#sumber_dana')[0])
    new Choices($('#disimpan_ke')[0])
    new Choices($('#tahun')[0])
    new Choices($('#bulan')[0])
    new Choices($('#tanggal')[0])

    $(".date").flatpickr({
        dateFormat: "d/m/Y"
    })

    $("#nominal").maskMoney({
        allowNegative: true
    });

    $(document).on('change', '#jenis_transaksi', function (e) {
        e.preventDefault()

        if ($(this).val().length > 0) {
            $.get('/transaksi/ambil_rekening/' + $(this).val(), function (result) {
                $('#kd_rekening').html(result)
            })
        }
    })

    $(document).on('change', '#sumber_dana,#disimpan_ke', function (e) {
        e.preventDefault()

        var jenis_transaksi = $('#jenis_transaksi').val()
        var sumber_dana = $('#sumber_dana').val()
        var disimpan_ke = $('#disimpan_ke').val()

        $.get('/transaksi/form_nominal/', {
            jenis_transaksi,
            sumber_dana,
            disimpan_ke
        }, function (result) {
            $('#form_nominal').html(result)
        })
    })

    $(document).on('change', '#harga_satuan,#jumlah', function (e) {
        var harga = ($('#harga_satuan').val()) ? $('#harga_satuan').val() : 0
        var jumlah = ($('#jumlah').val()) ? $('#jumlah').val() : 0

        harga = parseInt(harga.split(',').join('').split('.00').join(''))

        var harga_perolehan = harga * jumlah
        $('#harga_perolehan').val(formatter.format(harga_perolehan))
    })

    $(document).on('click', '#SimpanTransaksi', function (e) {
        e.preventDefault()
        $('small').html('')

        var form = $('#FormTransaksi')
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function (result) {
                Swal.fire('Berhasil', result.msg, 'success').then(() => {
                    jenis_transaksi.setChoiceByValue('')
                })
            },
            error: function (result) {
                const respons = result.responseJSON;

                Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error')
                $.map(respons, function (res, key) {
                    $('#' + key).parent('.input-group.input-group-static').addClass(
                        'is-invalid')
                    $('#msg_' + key).html(res)
                })
            }
        })
    })

</script>
@endsection
