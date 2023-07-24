@php
use App\Utils\Inventaris as Inv;
@endphp
<div class="col-sm-12">
    <div class="my-2">
        <label class="form-label" for="nama_barang">Nama Barang</label>
        <select class="form-control" name="nama_barang" id="nama_barang">
            <option value="">-- Pilih Nama Barang --</option>
            @foreach ($inventaris as $inv)
            <option value="{{ $inv->id }}">
                {{ $inv->nama_barang }} {{ Inv::nilaiBuku($tgl_transaksi, $inv->id) }}
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
            <option value="jual">Jual</option>
        </select>
        <small class="text-danger" id="msg_alasan"></small>
    </div>
</div>
<div class="col-sm-4">
    <div class="input-group input-group-static my-3">
        <label for="jumlah">Jumlah</label>
        <input autocomplete="off" type="number" name="jumlah" id="jumlah" class="form-control">
        <small class="text-danger" id="msg_jumlah"></small>
    </div>
</div>
<div class="col-sm-4">
    <div class="input-group input-group-static my-3">
        <label for="nilai_buku">Nilai Buku</label>
        <input autocomplete="off" type="text" name="nilai_buku" id="nilai_buku" class="form-control">
        <small class="text-danger" id="msg_nilai_buku"></small>
    </div>
</div>

<script>
    new Choices($('#nama_barang')[0])
    new Choices($('#alasan')[0])

    $("#nilai_buku").maskMoney({
        allowNegative: true
    });

</script>
