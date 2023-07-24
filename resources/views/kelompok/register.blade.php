@extends('layouts.base')

@section('content')
<div class="card">
    <div class="card-body" id="RegisterKelompok">

    </div>
</div>

@endsection

@section('script')
<script>
    $.get('/database/kelompok/create', function (result) {
        $('#RegisterKelompok').html(result)
    })

    $(document).on('change', '#desa', function (e) {
        e.preventDefault()

        var kd_desa = $(this).val()
        $.get('/database/kelompok/generatekode?kode=' + kd_desa, function (result) {
            $('#kode_kelompok').val(result.kd_kelompok)
        })
    })

    $(document).on('click', '#SimpanKelompok', function (e) {
        e.preventDefault()
        $('small').html('')

        var form = $('#FormRegistrasiKelompok')
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function (result) {
                var desa = result.desa
                Swal.fire('Berhasil', result.msg, 'success').then(() => {
                    Swal.fire({
                        title: 'Tambah Kelompok Baru?',
                        text: "",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak'
                    }).then((res) => {
                        if (res.isConfirmed) {
                            $.get('/database/kelompok/create?desa=' + desa,
                                function (result) {
                                    $('#RegisterKelompok').html(result)
                                })
                        } else {
                            window.location.href = '/database/kelompok'
                        }
                    })
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
