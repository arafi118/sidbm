@extends('layouts.base')

@section('content')
    @if (in_array('data_kelompok.export_excel', Session::get('tombol')))
        <div class="card mb-3">
            <div class="card-body p-2">
                <div class="d-flex justify-content-end align-items-center">
                    <button type="submit" class="btn btn-success btn-sm mb-0" id="ExportExcel">
                        Export Excel
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-flush table-hover table-click" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kode</th>
                            <th>Nama Kelompok</th>
                            <th>Kegiatan</th>
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
    @if (in_array('data_kelompok.riwayat_piutang', Session::get('tombol')) &&
            in_array('data_kelompok.detail_kelompok', Session::get('tombol')))
        <script>
            $('.table').on('click', 'tbody tr', function(e) {
                var data = table.row(this).data();

                window.location.href = '/database/kelompok/' + data.kd_kelompok
            })
        </script>
    @endif

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
            ajax: "/database/kelompok",
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
                    data: 'kegiatan.nama_jk',
                    name: 'kegiatan.nama_jk'
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

        $(document).on('click', '#ExportExcel', function(e) {
            e.preventDefault()

            $('input#laporan').val('kelompok')
            $('input#type').val('excel')
            $('#FormLaporanSisipan').submit()
        })
    </script>
@endsection
