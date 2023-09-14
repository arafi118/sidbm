@extends('layouts.base')

@section('content')
    <div class="card mb-3">
        <div class="card-body" id="SelectKelompok"></div>
    </div>

    <div class="mt-4 pt-1" id="RegisterProposal"></div>
@endsection

@section('script')
    <script>
        $.get('/daftar_kelompok?id_kel={{ $id_kel }}', async (result) => {
            await $('#SelectKelompok').html(result)

            var id_kel = $('#kelompok').val()
            formRegister(id_kel)
        })

        $(document).on('change', '#kelompok', function(e) {
            e.preventDefault()

            var id_kel = $(this).val()
            formRegister(id_kel)
        })

        $(document).on('click', '#SimpanProposal', function(e) {
            e.preventDefault()
            $('small').html('')

            var form = $('#FormRegisterProposal')
            $.ajax({
                type: 'post',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    Swal.fire('Berhasil', result.msg, 'success').then(() => {
                        //
                    })
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

        function formRegister(id_kel) {
            $.get('/register_proposal/' + id_kel, async (result) => {
                await $('#RegisterProposal').html(result)
            })
        }
    </script>
@endsection
