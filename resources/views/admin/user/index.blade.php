@extends('admin.layout.base')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-flush table-hover table-click" width="100%">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>lokasi</th>
                            <th>Level</th>
                            <th>Jabatan</th>
                            <th>Username</th>
                            <th>Password</th>
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
            ajax: "/master/users",
            columns: [{
                    data: 'namadepan',
                    name: 'namadepan'
                },
                {
                    data: 'kec.nama_kec',
                    name: 'kec.nama_kec'
                },
                {
                    data: 'l.deskripsi_level',
                    name: 'l.deskripsi_level'
                },
                {
                    data: 'j.nama_jabatan',
                    name: 'j.nama_jabatan'
                },
                {
                    data: 'uname',
                    name: 'uname'
                },
                {
                    data: 'pass',
                    name: 'pass'
                }
            ]
        });

        $('.table').on('click', 'tbody tr', function(e) {
            var data = table.row(this).data();

            window.location.href = '/master/users/' + data.id
        })
    </script>
@endsection
