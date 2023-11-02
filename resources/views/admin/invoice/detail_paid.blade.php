@extends('admin.layout.base')

@section('content')
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body">

                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="input-group input-group-static">
                        <label for="pesan">Pesan</label>
                        <textarea class="form-control" rows="6" name="pesan" id="pesan"></textarea>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-sm btn-info mb-0" type="button">Kirim Pesan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var tgl = "{{ date('d/m/Y') }}"
        var jumlah = '{{ number_format($invoice->jumlah - $invoice->trx_sum_jumlah, 2) }}'
        var keterangan = '{{ $invoice->jp->nama_jp }} Kec. {{ $invoice->kec->nama_kec }}'
        var lembaga = '{{ $invoice->kec->nama_lembaga_sort }}'

        pesan()

        function pesan() {
            var pesan =
                `*${keterangan}*\n\n*Yth. ${lembaga}*\nTerima kasih telah melakukan pembayaran *${keterangan}* sebesar *Rp. ${jumlah}* pada tanggal *${tgl}*. Silakan cetak Invoice Paid di menu Pengaturan - Invoice.\n\nSalam,\nPT. Asta Brata Teknologi`

            $('#pesan').val(pesan)
        }
    </script>
@endsection
