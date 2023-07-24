@extends('layouts.base')

@section('content')
<div class="card">
    <div class="card-body" id="RegisterPenduduk">

    </div>
</div>
@endsection

@section('script')
<script>
    $.get('/database/penduduk/create', function (result) {
        $('#RegisterPenduduk').html(result)
    })

    $(document).on('keyup', '#nik', function (e) {
        e.preventDefault()

        var nik = $(this).val()
        if (nik.length == 16) {
            $.get('/database/penduduk/create?nik=' + nik, function (result) {
                $('#RegisterPenduduk').html(result)
            })
        }
    })

    $(document).on('click', '#SimpanPenduduk', function (e) {
        e.preventDefault()
        $('small').html('')

        var form = $('#Penduduk')
        $.ajax({
            type: 'post',
            url: form.attr('action'),
            data: form.serialize(),
            success: function (result) {
                Swal.fire('Berhasil', result.msg, 'success').then(() => {
                    Swal.fire({
                        title: 'Tambah Penduduk Baru?',
                        text: "",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak'
                    }).then((res) => {
                        if (res.isConfirmed) {
                            $.get('/database/penduduk/create', function (result) {
                                $('#RegisterPenduduk').html(result)
                            })
                        } else {
                            window.location.href = '/database/penduduk'
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
