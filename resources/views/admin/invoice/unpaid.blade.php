@extends('admin.layout.base')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-flush table-hover table-click" width="100%" id="TbInvoiceUnpaid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lokasi/Kecamatan</th>
                            <th>Jenis Pembayaran</th>
                            <th>tgl Invoice</th>
                            <th>Tagihan</th>
                            <th>Saldo</th>
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
        var table = $('#TbInvoiceUnpaid').DataTable({
            language: {
                paginate: {
                    previous: "&laquo;",
                    next: "&raquo;"
                }
            },
            processing: true,
            serverSide: true,
            ajax: '/master/unpaid',
            columns: [{
                data: 'idv',
                name: 'idv'
            }, {
                data: 'kec.nama_kec',
                name: 'kec.nama_kec'
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
            }],
            order: [
                [0, 'desc']
            ]
        })

        $('#TbInvoiceUnpaid').on('click', 'tbody tr', function(e) {
            var data = table.row(this).data();

            window.location.href = '/master/' + data.idv + '/unpaid'
        })
    </script>
@endsection
