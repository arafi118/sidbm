@extends('admin.layout.base')

@section('content')
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <form action="/master/buat_invoice" method="post" id="FormInvoice">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label for="tgl_invoice">Tgl Invoice</label>
                                    <input autocomplete="off" type="text" name="tgl_invoice" id="tgl_invoice"
                                        class="form-control date" value="{{ date('d/m/Y') }}">
                                    <small class="text-danger" id="msg_tgl_invoice"></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label for="nomor_invoice">No. Invoice</label>
                                    <input autocomplete="off" type="text" name="nomor_invoice" id="nomor_invoice"
                                        class="form-control" value="{{ $nomor_invoice }}" readonly>
                                    <small class="text-danger" id="msg_nomor_invoice"></small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="my-2">
                                    <label class="form-label" for="kecamatan">Kecamatan</label>
                                    <select class="form-control" name="kecamatan" id="kecamatan">
                                        @foreach ($kecamatan as $kec)
                                            <option value="{{ $kec->id }}">
                                                {{ $kec->kd_kec }}. {{ $kec->nama_kec }} &mdash;
                                                @if ($kec->kabupaten)
                                                    {{ $kec->kabupaten->nama_kab }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger" id="msg_kecamatan"></small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="my-2">
                                    <label class="form-label" for="jenis_pembayaran">Jenis Pembayaran</label>
                                    <select class="form-control" name="jenis_pembayaran" id="jenis_pembayaran">
                                        @foreach ($jenis_bayar as $jp)
                                            <option value="{{ $jp->id }}">
                                                {{ $jp->nama_jp }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger" id="msg_jenis_pembayaran"></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label for="tgl_awal">Tgl Awal Pakai</label>
                                    <input autocomplete="off" type="text" name="tgl_awal" id="tgl_awal"
                                        class="form-control" value="{{ date('d/m/Y') }}" readonly>
                                    <small class="text-danger" id="msg_tgl_awal"></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label for="jumlah">Jumlah</label>
                                    <input autocomplete="off" type="text" name="jumlah" id="jumlah"
                                        class="form-control">
                                    <small class="text-danger" id="msg_jumlah"></small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" id="SimpanInvoice" class="btn btn-github btn-sm mb-0">
                                Buat Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="input-group input-group-static">
                        <label for="pesan">Pesan</label>
                        <textarea class="form-control" rows="10" name="pesan" id="pesan"></textarea>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-sm btn-info mb-0" type="button">Kirim Pesan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var pembayaran, tagihan, nama_lembaga, batas_waktu = '';
        var batas_waktu = '{{ $batas_waktu }}'

        function pesan() {
            var pesan =
                `*${pembayaran}*\n\n*Yth. ${nama_lembaga}*\nDimohon segera melakukan pembayaran *${pembayaran}* sebesar *Rp. ${tagihan}* paling lambat tanggal *${batas_waktu}*`

            pesan = pesan.replace(/undefined/g, '')
            $('#pesan').val(pesan)
        }

        new Choices($('#kecamatan')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })

        new Choices($('#jenis_pembayaran')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })

        $(".date").flatpickr({
            dateFormat: "d/m/Y"
        })

        $("#jumlah").maskMoney();

        $(document).on('change', '#tgl_invoice', function(e) {
            var tgl = $(this).val()

            $.get('/master/nomor_invoice', {
                'tgl_invoice': tgl
            }, function(result) {
                $('#nomor_invoice').val(result.nomor)
                batas_waktu = result.batas_waktu

                pesan()
            })
        })

        $(document).on('change', '#kecamatan, #jenis_pembayaran', function(e) {
            var kecamatan = $('#kecamatan').val()
            var jenis_pembayaran = $('#jenis_pembayaran').val()

            $.get('/master/jumlah_tagihan', {
                kecamatan,
                jenis_pembayaran
            }, function(result) {
                $('#jumlah').val(result.jumlah)
                $('#tgl_awal').val(result.tgl_pakai)

                pembayaran = result.jenis_pembayaran
                tagihan = result.jumlah
                nama_lembaga = result.nama_lembaga

                pesan()
            })
        })

        $(document).on('click', '#SimpanInvoice', function(e) {
            e.preventDefault()

            var form = $('#FormInvoice')
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        var id = result.id
                        Swal.fire('Berhasil', result.msg, 'success').then(() => {
                            Swal.fire({
                                title: 'Tambah Invoice Baru?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Tidak'
                            }).then((res) => {
                                if (res.isConfirmed) {
                                    $('#nomor_invoice').val(result.nomor)
                                } else {
                                    window.location.href = '/master/' + id + '/unpaid'
                                }
                            })
                        })
                    }
                },
                error: function(result) {
                    const respons = result.responseJSON;

                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error')
                    $.map(respons, function(res, key) {
                        $('#' + key).parent('.input-group.input-group-static').addClass(
                            'is-invalid')
                        $('#msg_' + key).html(res)
                    })
                }
            })
        })
    </script>
@endsection
