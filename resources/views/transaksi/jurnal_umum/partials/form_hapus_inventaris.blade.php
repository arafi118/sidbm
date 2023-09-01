@php
    use App\Utils\Inventaris as Inv;
@endphp

<input type="hidden" name="harsat" id="harsat">
<input type="hidden" name="relasi" id="relasi">
<div class="col-sm-8">
    <div class="my-2">
        <label class="form-label" for="nama_barang">Nama Barang</label>
        <select class="form-control" name="nama_barang" id="nama_barang">
            <option value="">-- Pilih Nama Barang --</option>
            @foreach ($inventaris as $inv)
                @php
                    $nilai_buku = Inv::nilaiBuku($tgl_transaksi, $inv);
                @endphp
                <option value="{{ $inv->id }}#{{ $inv->unit }}#{{ $nilai_buku }}">
                    {{ $inv->nama_barang }} ({{ $inv->unit }} unit x
                    {{ number_format($inv->harsat) }}) | NB. {{ number_format($nilai_buku, 2) }}
                </option>
            @endforeach
        </select>
        <small class="text-danger" id="msg_nama_barang"></small>
    </div>
</div>
<div class="col-sm-4">
    <div class="my-2">
        <label class="form-label" for="alasan">Alasan</label>
        <select class="form-control" name="alasan" id="alasan">
            <option value="">-- Alasan Penghapusan --</option>
            <option value="hapus">Hapus</option>
            <option value="hilang">Hilang</option>
            <option value="rusak">Rusak</option>
            <option value="dijual">Dijual</option>
        </select>
        <small class="text-danger" id="msg_alasan"></small>
    </div>
</div>
<div id="col_unit" class="col-sm-6">
    <div class="input-group input-group-static my-3">
        <label for="unit">Jumlah (unit)</label>
        <input autocomplete="off" type="number" name="unit" id="unit" class="form-control">
        <small class="text-danger" id="msg_unit"></small>
    </div>
</div>
<div id="col_nilai_buku" class="col-sm-6">
    <div class="input-group input-group-static my-3">
        <label for="nilai_buku">Nilai Buku</label>
        <input autocomplete="off" readonly disabled type="text" name="nilai_buku" id="nilai_buku"
            class="form-control">
        <small class="text-danger" id="msg_nilai_buku"></small>
    </div>
</div>
<div id="col_harga_jual" class="col-sm-4" style="display: none">
    <div class="input-group input-group-static my-3">
        <label for="harga_jual">Harga Jual</label>
        <input autocomplete="off" type="text" name="harga_jual" id="harga_jual" class="form-control">
        <small class="text-danger" id="msg_harga_jual"></small>
    </div>
</div>

<script>
    new Choices($('#nama_barang')[0])
    new Choices($('#alasan')[0])

    $("#nilai_buku").maskMoney({
        allowNegative: true
    });

    $("#harga_jual").maskMoney({
        allowNegative: true
    });
</script>
