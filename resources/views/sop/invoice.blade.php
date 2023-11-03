@extends('layouts.base')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-flush table-hover table-click" width="100%" id="TbInvoice">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jenis Pembayaran</th>
                            <th>tgl Invoice</th>
                            <th>Tagihan</th>
                            <th>Saldo</th>
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
        var table = $('#TbInvoice').DataTable({
            language: {
                paginate: {
                    previous: "&laquo;",
                    next: "&raquo;"
                }
            },
            processing: true,
            serverSide: true,
            ajax: '/pengaturan/invoice',
            columns: [{
                data: 'idv',
                name: 'idv'
            }, {
                data: 'jp.nama_jp',
                name: 'jp.nama_jp'
            }, {
                data: 'tgl_invoice',
                name: 'tgl_invoice'
            }, {
                data: 'jumlah',
                name: 'jumlah'
            }, {
                data: 'saldo',
                name: 'saldo',
                orderable: false,
                searchable: false
            }, {
                data: 'status',
                name: 'status'
            }],
            order: [
                [0, 'desc']
            ]
        })

        $('#TbInvoice').on('click', 'tbody tr', function(e) {
            var data = table.row(this).data();

            window.location.href = '/pengaturan/' + data.idv + '/invoice'
        })
    </script>
@endsection
