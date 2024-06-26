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

    $jasa_pinjaman = ($pinj->pros_jasa / 100) * $pinj->alokasi;
@endphp

<form action="/hapus_pemanfaat/{{ $pinj->id }}" method="post" id="FormPenghapusanPinjamanAnggota">
    @csrf

    <input type="hidden" name="id_pinjaman" id="id_pinjaman" value="{{ $pinj->id }}">
    <input type="hidden" name="jasa_pinjaman" id="jasa_pinjaman" value="{{ $jasa_pinjaman }}">
    <input type="hidden" name="pokok_pinjaman" id="pokok_pinjaman" value="{{ $pinj->alokasi }}">
    <div class="row">
        <div class="col-md-4 my-3">
            <div class="input-group input-group-static">
                <label for="alokasi_pemanfaat">Alokasi</label>
                <input type="text" id="alokasi_pemanfaat" name="alokasi_pemanfaat" class="form-control" readonly
                    value="{{ number_format($pinj->alokasi, 2) }}">
            </div>
        </div>
        <div class="col-md-4 my-3">
            <div class="input-group input-group-static">
                <label for="kom_angs_pokok">Kom. Angsuran Pokok</label>
                <input type="text" id="kom_angs_pokok" name="kom_angs_pokok" class="form-control" readonly
                    value="{{ number_format($kom_pokok, 2) }}">
            </div>
        </div>
        <div class="col-md-4 my-3">
            <div class="input-group input-group-static">
                <label for="kom_angs_jasa">Kom. Angsuran Jasa</label>
                <input type="text" id="kom_angs_jasa" name="kom_angs_jasa" class="form-control" readonly
                    value="{{ number_format($kom_jasa, 2) }}">
            </div>
        </div>
        <div class="col-md-4 my-3">
            <div class="input-group input-group-static">
                <label for="tgl_penghapusan">Tgl Penghapusan</label>
                <input autocomplete="off" type="text" name="tgl_penghapusan" id="tgl_penghapusan"
                    class="form-control date" value="{{ date('d/m/Y') }}">
                <small class="text-danger" id="msg_tgl_penghapusan"></small>
            </div>
        </div>
        <div class="col-md-4 my-3">
            <div class="input-group input-group-static">
                <label for="jumlah_penghapusan_pokok">Jumlah Penghapusan (pokok)</label>
                <input type="text" id="jumlah_penghapusan_pokok" name="jumlah_penghapusan_pokok"
                    class="form-control money" value="{{ number_format($pinj->alokasi - $kom_pokok, 2) }}">
            </div>
        </div>
        <div class="col-md-4 my-3">
            <div class="input-group input-group-static">
                <label for="jumlah_penghapusan_jasa">Jumlah Penghapusan (jasa)</label>
                <input type="text" id="jumlah_penghapusan_jasa" name="jumlah_penghapusan_jasa"
                    class="form-control money" value="{{ number_format($jasa_pinjaman - $kom_jasa, 2) }}">
            </div>
        </div>
    </div>
</form>

<script>
    $(".money").maskMoney();

    $(".date").flatpickr({
        dateFormat: "d/m/Y"
    })
</script>
