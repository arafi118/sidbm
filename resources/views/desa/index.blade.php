@extends('layouts.base')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-flush table-hover table-click" width="100%">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Desa</th>
                        <th>Alamat</th>
                        <th>Telpon</th>
                        <th>Kades</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="EditDesa" tabindex="-1" aria-labelledby="EditDesaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">


    </div>
</div>

@endsection

@section('script')
<script>
    var table = $('.table').DataTable({
        language: {
            paginate: {
                previous: "&laquo;",
                next: "&raquo;"
            }
        },
        processing: true,
        serverSide: true,
        ajax: "/database/desa",
        columns: [{
                data: 'kd_desa',
                name: 'kd_desa'
            },
            {
                data: 'nama_desa',
                name: 'nama_desa'
            },
            {
                data: 'alamat_desa',
                name: 'alamat_desa'
            },
            {
                data: 'telp_desa',
                name: 'telp_desa'
            },
            {
                data: 'kades',
                name: 'kades'
            }
        ]
    });

    $('.table').on('click', 'tbody tr', function (e) {
        var data = table.row(this).data();

        $.get('/database/desa/' + data.kd_desa + "/edit", function (result) {
            $('#EditDesa .modal-dialog').html(result)
        })

        $('#EditDesa').modal('show')
    })

    $(document).on('click', '#simpanDesa', function (e) {
        e.preventDefault()

        var form = $('#FormEditDesa')
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            success: function (result) {
                Swal.fire('Berhasil', result.msg, 'success').then(async (result) => {
                    await $('#EditDesa').modal('toggle')
                    table.ajax.reload();
                })
            },
            error: function (result) {
                const respons = result.responseJSON;

                Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error')
                $.map(respons, function (res, key) {
                    $('#' + key).parent('.input-group').addClass('is-invalid')
                    $('#msg_' + key).html(res)
                })
            }
        })

    })

</script>
@endsection
