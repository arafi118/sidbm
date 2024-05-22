@extends('layouts.base')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-flush table-hover table-click" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kode</th>
                            <th>Nama Lembaga</th>
                            <th>Alamat</th>
                            <th>Telpon</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="text-sm">
                <span class="badge badge-secondary">
                    (N) Belum ada pinjaman
                </span>
                @foreach ($status_pinjaman as $status)
                    <span class="badge badge-{{ $status->warna_status }}">
                        ({{ $status->kd_status }})
                        {{ $status->nama_status }}
                    </span>
                @endforeach
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
            ajax: "/database/lembaga_lain",
            columns: [{
                    data: 'id',
                    name: 'id',
                    visible: false,
                    searchable: false
                }, {
                    data: 'kd_kelompok',
                    name: 'kd_kelompok'
                },
                {
                    data: 'nama_kelompok',
                    name: 'nama_kelompok'
                },
                {
                    data: 'alamat_kelompok',
                    name: 'alamat_kelompok'
                },
                {
                    data: 'telpon',
                    name: 'telpon'
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

        $('.table').on('click', 'tbody tr', function(e) {
            var data = table.row(this).data();

            window.location.href = '/database/lembaga_lain/' + data.kd_kelompok
        })
    </script>
@endsection