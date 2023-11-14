<form action="" method="post" id="FormAngsuranAnggota">
    <table class="table table-striped">
        <thead class="bg-dark text-white">
            <tr>
                <th>Nama</th>
                <th>Kom Pokok/Jasa</th>
                <th>Pokok</th>
                <th>Jasa</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pinkel->pinjaman_anggota as $pinj)
                @php
                    $kom_pokok = 0;
                    $kom_jasa = 0;

                    if ($pinj->kom_pokok) {
                        $angsuran_pokok = json_decode($pinj->kom_pokok, true);
                        foreach ($angsuran_pokok as $pokok => $jumlah) {
                            $kom_pokok += $jumlah;
                        }
                    }

                    if ($pinj->kom_jasa) {
                        $angsuran_jasa = json_decode($pinj->kom_jasa, true);
                        foreach ($angsuran_jasa as $jasa => $jumlah) {
                            $kom_jasa += $jumlah;
                        }
                    }

                @endphp
                <tr>
                    <td>
                        <div class="fw-bold">{{ $pinj->anggota->namadepan }}</div>
                        <div>Rp. {{ number_format($pinj->alokasi, 2) }}</div>
                    </td>
                    <td>
                        <div>Rp. {{ number_format($kom_pokok, 2) }}</div>
                        <div>Rp. {{ number_format($kom_jasa, 2) }}</div>
                    </td>
                    <td>
                        <div class="input-group input-group-dynamic mb-0">
                            <input autocomplete="off" type="text" name="pokok_anggota[{{ $pinj->id }}]"
                                id="p-{{ $pinj->id }}" class="form-control nominal pokok">
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-dynamic mb-0">
                            <input autocomplete="off" type="text" name="jasa_anggota[{{ $pinj->id }}]"
                                id="j-{{ $pinj->id }}" class="form-control nominal jasa">
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-dynamic mb-0">
                            <input autocomplete="off" type="text" name="denda_anggota[{{ $pinj->id }}]"
                                id="d-{{ $pinj->id }}" class="form-control nominal denda">
                        </div>
                    </td>
                </tr>
            @endforeach

            <tr>
                <th colspan="2">Jumlah</th>
                <th align="right">
                    <div id="_total_pokok_anggota">Rp. {{ number_format(0, 2) }}</div>
                    <input type="hidden" name="total_pokok_anggota" id="total_pokok_anggota">
                </th>
                <th align="right">
                    <div id="_total_jasa_anggota">Rp. {{ number_format(0, 2) }}</div>
                    <input type="hidden" name="total_jasa_anggota" id="total_jasa_anggota">
                </th>
                <th align="right">
                    <div id="_total_denda_anggota">Rp. {{ number_format(0, 2) }}</div>
                    <input type="hidden" name="total_denda_anggota" id="total_denda_anggota">
                </th>
            </tr>
        </tbody>
    </table>
</form>

<script>
    var formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })

    $(".nominal").maskMoney({
        allowNegative: true
    });

    $(document).on('change', '.pokok', function(e) {
        var total = 0;
        $('.pokok').map(function() {
            var value = $(this).val()
            if (value == '') {
                value = 0
            } else {
                value = value.split(',').join('')
                value = value.split('.00').join('')
            }

            value = parseFloat(value)
            total += value

            $('#pokok').val(formatter.format(total))
            $('#_total_pokok_anggota').text(formatter.format(total))
            $('#total_pokok_anggota').val(total)
        })
    })

    $(document).on('change', '.jasa', function(e) {
        var total = 0;
        $('.jasa').map(function() {
            var value = $(this).val()
            if (value == '') {
                value = 0
            } else {
                value = value.split(',').join('')
                value = value.split('.00').join('')
            }

            value = parseFloat(value)
            total += value

            $('#jasa').val(formatter.format(total))
            $('#_total_jasa_anggota').text(formatter.format(total))
            $('#total_jasa_anggota').val(total)
        })
    })

    $(document).on('change', '.denda', function(e) {
        var total = 0;
        $('.denda').map(function() {
            var value = $(this).val()
            if (value == '') {
                value = 0
            } else {
                value = value.split(',').join('')
                value = value.split('.00').join('')
            }

            value = parseFloat(value)
            total += value

            $('#denda').val(formatter.format(total))
            $('#_total_denda_anggota').text(formatter.format(total))
            $('#total_denda_anggota').val(total)
        })
    })
</script>
