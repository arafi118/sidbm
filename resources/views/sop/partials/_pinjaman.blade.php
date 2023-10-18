<form action="/pengaturan/pinjaman/{{ $kec->id }}" method="post" id="FormPinjaman">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-4">
            <div class="input-group input-group-static my-3">
                <label for="default_jasa">Default Jasa (%)</label>
                <input autocomplete="off" type="number" name="default_jasa" id="default_jasa" class="form-control"
                    value="{{ $kec->def_jasa }}">
                <small class="text-danger" id="msg_default_jasa"></small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group input-group-static my-3">
                <label for="default_jangka">Default Jangka (%)</label>
                <input autocomplete="off" type="number" name="default_jangka" id="default_jangka" class="form-control"
                    value="{{ $kec->def_jangka }}">
                <small class="text-danger" id="msg_default_jangka"></small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="my-2">
                <label class="form-label" for="pembulatan">Pembulatan</label>
                <select class="form-control" name="pembulatan" id="pembulatan">
                    <option {{ $kec->pembulatan == '500' ? 'selected' : '' }} value="500">500</option>
                    <option {{ $kec->pembulatan == '1000' ? 'selected' : '' }} value="1000">1000</option>
                </select>
                <small class="text-danger" id="msg_pembulatan"></small>
            </div>
        </div>
    </div>
</form>

<div class="d-flex justify-content-end">
    <button type="button" id="SimpanPinjaman" data-target="#FormPinjaman"
        class="btn btn-sm btn-github mb-0 btn-simpan">
        Simpan Perubahan
    </button>
</div>
