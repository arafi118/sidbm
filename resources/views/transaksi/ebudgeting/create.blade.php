<form action="/transaksi/simpan_anggaran" method="post" id="formRencanaAnggaran">
    @csrf

    <input type="hidden" name="bulan" id="bulan" value="{{ $bulan }}">
    <input type="hidden" name="tahun" id="tahun" value="{{ $tahun }}">
    <table class="table table-striped">
        <thead class="bg-dark text-white">
            <tr>
                <th>Kode Akun</th>
                <th>Rencana</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($akun1 as $lev1)
                @php
                    $total = 0;
                @endphp
                <tr class="bg-secondary">
                    <td colspan="2" class="text-white fw-bold">
                        <b>{{ $lev1->kode_akun }}. {{ $lev1->nama_akun }}</b>
                    </td>
                </tr>
                @foreach ($lev1->akun2 as $lev2)
                    @foreach ($lev2->akun3 as $lev3)
                        @foreach ($lev3->rek as $rek)
                            @php
                                $nominal = 0;
                                if ($jumlah > 0) {
                                    $nominal = $rek->eb->jumlah;
                                }

                                $group = 'pendapatan';
                                if ($lev1->lev1 == '5') {
                                    $group = 'beban';
                                }

                                $total += $nominal;
                            @endphp
                            <tr>
                                <td style="vertical-align: middle;">{{ $rek->kode_akun }}. {{ $rek->nama_akun }}</td>
                                <td style="vertical-align: middle;">
                                    <div class="input-group input-group-dynamic mb-0">
                                        <input data-group="{{ $group }}" autocomplete="off" type="text"
                                            name="jumlah[{{ $rek->kode_akun }}]" id="{{ $rek->kode_akun }}"
                                            class="form-control nominal" value="{{ number_format($nominal, 2) }}">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach

                <tr class="bg-dark text-white">
                    <td>
                        <b>Total Rencana {{ $lev1->nama_akun }}</b>
                    </td>
                    <td>
                        <div id="{{ strtolower($lev1->nama_akun) }}">{{ number_format($total, 2) }}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</form>

<div class="d-flex justify-content-end">
    <button id="SimpanAnggaran" class="btn btn-sm btn-github mb-0">Simpan Rencana Anggaran</button>
</div>

<script>
    var formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })

    $(".nominal").maskMoney({
        allowNegative: true
    });

    $(document).on('change', '[data-group=pendapatan]', function() {
        var total = 0;
        $.each($('[data-group=pendapatan]'), function(key, value) {
            if ($(this).val() == '') $(this).val('0.00')
            var nominal = $(this).val().split(',').join('')

            total += parseInt(nominal)
        })

        $('#pendapatan').html(formatter.format(total))
    })

    $(document).on('change', '[data-group=beban]', function() {
        var total = 0;
        $.each($('[data-group=beban]'), function(key, value) {
            if ($(this).val() == '') $(this).val('0.00')
            var nominal = $(this).val().split(',').join('')

            total += parseInt(nominal)
        })

        $('#beban').html(formatter.format(total))
    })
</script>
