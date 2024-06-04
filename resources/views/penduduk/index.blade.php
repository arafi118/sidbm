@extends('layouts.base')

@section('content')
    <div class="card mb-3">
        <div class="card-body p-2">
            <div class="d-flex justify-content-end align-items-center">
                <button type="submit" class="btn btn-success btn-sm mb-0" id="ExportExcel">
                    Export Excel
                </button>
            </div>
        </div>
    </div>

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

        $('.table').on('click', 'tbody tr', function(e) {
            var data = table.row(this).data();

            window.location.href = '/database/penduduk/' + data.nik
        })

        $(document).on('click', '#ExportExcel', function(e) {
            e.preventDefault()

            $('input#laporan').val('penduduk')
            $('input#type').val('excel')
            $('#FormLaporanSisipan').submit()
        })
    </script>
@endsection
