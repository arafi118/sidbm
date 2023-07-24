@extends('layouts.base')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-flush table-hover table-click" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>Telpon</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
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
        ajax: "/database/penduduk",
        columns: [{
                data: 'id',
                name: 'id',
                visible: false,
                searchable: false
            }, {
                data: 'nik',
                name: 'nik'
            },
            {
                data: 'namadepan',
                name: 'namadepan'
            },
            {
                data: 'alamat',
                name: 'alamat'
            },
            {
                data: 'hp',
                name: 'hp'
            },
            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false
            }
        ],
        order: [
            [0, 'desc']
        ]
    });

    $('.table').on('click', 'tbody tr', function (e) {
        var data = table.row(this).data();

        window.location.href = '/database/penduduk/' + data.nik
    })

</script>
@endsection
