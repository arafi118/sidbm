<form action="/pengaturan/asuransi/{{ $kec->id }}" method="post" id="FormAsuransi">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6">
            <div class="input-group input-group-static my-3">
                <label for="nama_asuransi">Nama Asuransi</label>
                <input autocomplete="off" type="text" name="nama_asuransi" id="nama_asuransi" class="form-control"
                    value="{{ $kec->nama_asuransi_p }}">
                <small class="text-danger" id="msg_nama_asuransi"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="my-2">
                <label class="form-label" for="jenis_asuransi">Jenis Asuransi</label>
                <select class="form-control" name="jenis_asuransi" id="jenis_asuransi">
                    <option {{ $kec->pengaturan_asuransi == '1' ? 'selected' : '' }} value="1">Pokok</option>
                    <option {{ $kec->pengaturan_asuransi == '2' ? 'selected' : '' }} value="2">Pokok dan Jasa
                    </option>
                </select>
                <small class="text-danger" id="msg_jenis_asuransi"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-static my-3">
                <label for="usia_maksimal">Usia Maksimal (Tahun)</label>
                <input autocomplete="off" type="number" name="usia_maksimal" id="usia_maksimal" class="form-control"
                    value="{{ $kec->usia_mak }}">
                <small class="text-danger" id="msg_usia_maksimal"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-static my-3">
                <label for="presentase_premi">Presentase Premi</label>
                <input autocomplete="off" type="number" name="presentase_premi" id="presentase_premi"
                    class="form-control" value="{{ $kec->besar_premi }}">
                <small class="text-danger" id="msg_presentase_premi"></small>
            </div>
        </div>
    </div>
</form>

<div class="d-flex justify-content-end">
    <button type="button" id="SimpanAsuransi" data-target="#FormAsuransi"
        class="btn btn-sm btn-github mb-0 btn-simpan">
        Simpan Perubahan
    </button>
</div>
